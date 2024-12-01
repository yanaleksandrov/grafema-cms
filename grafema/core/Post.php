<?php
namespace Grafema;

use Grafema\Patterns\Registry;

/**
 * Core class used for interacting with post types.
 */
class Post {

	/**
	 * Add post.
	 *
	 * @param $type
	 * @param $args
	 * @return Error|int
	 * @since 2025.1
	 */
	public static function add( string $type, array $args ): Error|int {
		if ( ! Post\Type::exist( $type ) ) {
			return new Error( 'post-add', I18n::_t( 'Post type is not registered.' ) );
		}

		$author  = Sanitizer::absint( $args['author'] ?? '' );
		$content = Sanitizer::trim( $args['content'] ?? '' );
		$title   = Sanitizer::text( $args['title'] ?? '' );
		$status  = Sanitizer::text( $args['status'] ?? 'draft' );
		$slug    = Sanitizer::slug( $args['slug'] ?? $title );

		Db::insert( $type, compact( 'author', 'title', 'content', 'status' ) );

		$postId  = Sanitizer::absint( Db::id() );
		$dbTable = Sanitizer::tablename( $type );

		if ( $postId ) {
			$slugId = Slug::add( $postId, $dbTable, $slug );
		}

		return $postId;
	}

	/**
	 * Get post type by ID.
	 *
	 * @param  string     $type
	 * @param  string|int $value
	 * @param  string     $by
	 * @return array
	 * @since 2025.1
	 */
	public static function get( string $type, string|int $value, string $by = 'ID' ): array {
		if ( ! Post\Type::exist( $type ) ) {
			return [];
		}

		$post = Db::get( $type, '*', [ $by => $value ] );

		// add data for media files
		$sizes = Registry::get( 'images' );

		if ( $type === 'media' && is_array( $post ) && is_array( $sizes ) ) {
			$file_path = sprintf( '%s%s', GRFM_PATH, $post['slug'] ?? '' );
			foreach ( $sizes as $index => $size ) {
				$width  = intval( $size['width'] ?? 0 );
				$height = intval( $size['height'] ?? 0 );

				if ( ! $width || ! $height ) {
					continue;
				}

				$file_resized           = str_replace( '/i/original/', sprintf( '/i/%sx%s/', $width, $height ), $file_path );
				$sizes[ $index ]['url'] = Url::fromPath( $file_resized );
			}
		}

		return array_merge( $post, [ 'sizes' => $sizes ] );
	}

	/**
	 * Remove post type by field.
	 *
	 * @param $type
	 * @param $value
	 * @param string $by
	 * @return \PDOStatement
	 * @since 2025.1
	 */
	public static function delete( $type, $value, string $by = 'ID' ): \PDOStatement {
		return Db::delete( $type, [ $by => $value ] );
	}
}

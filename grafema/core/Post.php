<?php
namespace Grafema;

use Grafema\Patterns\Registry;
use Grafema\Post\Type;

class Post {

	private function __construct(
		public int $id = 0,
		public string $title = '',
		public string $content = '',
		public int $author = 0,
		public int $comments = 0,
		public int $views = 0,
		public string $status = '',
		public string $discussion = '',
		public string $password = '',
		public int $parent = 0,
		public int $position = 0,
		public string $createdAt = '',
		public string $updatedAt = '',
		public string $type = '',
		public string $table = '',
		public string $slug = '',
		public array $fields = []
	) {}

	/**
	 * Add new post.
	 *
	 * @param string $type
	 * @param array $args
	 *
	 * @return Error|Post|null
	 *
	 * @since 2025.1
	 */
	public static function add( string $type, array $args ): Error|Post|null {
		$type = Type::get( $type );
		if ( ! $type instanceof Type ) {
			return new Error( 'post-add', I18n::_t( 'Post type is not registered.' ) );
		}

		[ $content, $title, $status, $author, $slug, $fields ] = ( new Sanitizer(
			$args,
			[
				'content' => 'text',
				'title'   => 'text',
				'status'  => 'text',
				'author'  => 'absint:0',
				'slug'    => 'slug:$title',
				'fields'  => 'array',
			]
		) )->values();

		$user = User::current();
		if ( ! $author && $user instanceof User ) {
			$author = $user->ID;
		}

		// insert to DB
		Db::insert( $type->table, compact( 'author', 'title', 'content', 'status' ) );

		$post = self::get( $type->key, Db::id() );
		if ( $post instanceof Post ) {
			$post->type  = $type->key;
			$post->table = $type->table;

			/**
			 * Add slug just if post type is public.
			 *
			 * @since 2025.1
			 */
			if ( $type->public === true ) {
				$post->slug = Slug::add( $post->id, $type->table, $slug );
			}

			if ( $fields ) {
				( new Field( $post ) )->import( $fields );
			}
		}

		return $post;
	}

	public static function getBySlug( string $value ): Error|Post|null {
		$slug = Slug::get( $value );
		if ( ! empty( $slug['entity_table'] ) ) {
			return self::get( Sanitizer::tablename( $slug['entity_table'] ), $slug['entity_id'] );
		}
		return null;
	}

	/**
	 * Get post by field.
	 *
	 * @param string $type
	 * @param int|string $value
	 * @param string $field
	 * @return Error|Post|null
	 *
	 * @since 2025.1
	 */
	public static function get( string $type, int|string $value, string $field = 'id' ): Error|Post|null {
		$type = Type::get( $type );
		if ( ! $type instanceof Type ) {
			return new Error( 'post-get', I18n::_t( 'Post type is not registered.' ) );
		}

		$data = Db::get( $type->table, '*', [ $field => $value ] );

		// add data for media files
		$sizes = Registry::get( 'images' );
		if ( $type === 'media' && is_array( $data ) && is_array( $sizes ) ) {
			$file_path = sprintf( '%s%s', GRFM_PATH, $data['slug'] ?? '' );
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

		foreach ( $data as $key => $value ) {
			unset( $data[ $key ] );
			$data[ Sanitizer::camelcase( $key ) ] = $value;
		}

		$data['slug']   = $type->public === true ? Slug::getByEntity( $data['id'], $type->table ) : '';
		$data['fields'] = [];

		return new Post( ...$data );
	}

	/**
	 * Remove post by field.
	 *
	 * @param string $type
	 * @param mixed $value
	 * @param string $by
	 * @return bool
	 *
	 * @since 2025.1
	 */
	public static function delete( string $type, mixed $value, string $by = 'id' ): bool {
		return Db::delete( $type, [ $by => $value ] )->rowCount() > 0;
	}
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\I18n;
use Grafema\Post\Post;
use Grafema\Debug;
use Grafema\Json;
use Grafema\Patterns;
use Grafema\File\File;
use Grafema\File\Image;
use Grafema\Url;

class Media extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'media';

	/**
	 * Get all items.
	 *
	 * @url    GET api/user
	 */
	public function index(): array
	{
		return [
			'method' => 'GET user list',
		];
	}

	/**
	 * Get item by ID.
	 *
	 * @url    GET api/user/$id
	 */
	public function view(): array
	{
		return [
			'method' => 'GET user by ID',
		];
	}

	/**
	 * Create item.
	 *
	 * @url    POST api/user
	 */
	public function create(): array
	{
		return [
			'method' => 'POST create user',
		];
	}

	/**
	 * Update item by ID.
	 *
	 * @url    PUT api/user/$id
	 */
	public function update(): array
	{
		return [
			'method' => 'PUT update user by ID',
		];
	}

	/**
	 * Remove item by ID.
	 *
	 * @url    DELETE api/user/$id
	 */
	public function delete(): array
	{
		return [
			'method' => 'DELETE remove user by ID',
		];
	}

	/**
	 * Upload new file to media.
	 *
	 * @since 1.0.0
	 */
	public static function upload(): array
	{
		$files = [];
		if ( $_FILES && is_array( $_FILES ) ) {
			foreach ( $_FILES as $file ) {
				// upload original image
				$original_file = File::upload(
					$file,
					function( $file ) {
						$file->set_directory( 'i/original' );
					}
				);

				// now make smaller copies
				$file_path = $original_file['path'] ?? '';
				if ( $file_path ) {
					$image = new Image();
					$sizes = Patterns\Registry::get( 'jb.images' );
					if ( is_array( $sizes ) && $sizes !== [] ) {
						foreach ( $sizes as $size ) {
							$mime   = $size['mime'] ?? null;
							$width  = intval( $size['width'] ?? 0 );
							$height = intval( $size['height'] ?? 0 );

							if ( ! $width || ! $height ) {
								continue;
							}

							$file_resized = sprintf( '/i/%s/', implode( 'x', [ $width, $height ] ) );
							$file_resized = str_replace( '/i/original/', $file_resized, $file_path );

							$image->fromFile( $file_path )->thumbnail( $width, $height )->toFile( $file_resized, $mime );
						}
					}

					$files[] = $original_file;
				}
			}
		}

		$posts = [];
		if ( $files ) {
			foreach ( $files as $file ) {
				$post_id = Post::add(
					'media',
					[
						'status' => 'publish',
						'slug'   => $file['slug'],
						'fields' => [
							'mime' => $file['mime'],
						],
					]
				);

				if ( $post_id ) {
					$posts[] = Post::get( 'media', $post_id );
				}
			}
		}

		echo Json::encode(
			[
				'status'    => 200,
				'benchmark' => Debug::timer( 'getall' ),
				'data'      => [
					[
						'fragment' => sprintf( I18n::__( '%d files have been successfully uploaded to the library' ), count( $files ) ),
						'target'   => 'body',
						'method'   => 'notify',
						'custom'   => [
							'type'     => count( $posts ) > 0 ? 'success' : 'error',
							'duration' => 5000,
						],
					],
					[
						'fragment' => $posts,
						'target'   => 'body',
						'method'   => 'alpine',
					],
				],
			]
		);
	}

	/**
	 * Upload files from external url.
	 *
	 * @since 1.0.0
	 */
	public static function grab(): array {
		$files = [];
		$urls  = Url::extract( $_POST['urls'] ?? '' );
		if ( $urls ) {
			foreach ( $urls as $url ) {
				$files[] = ( new File() )->to( GRFM_UPLOADS . 'i/' )->grab( $url );
			}
		}
		return $files;
	}
}

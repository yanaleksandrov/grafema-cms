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
use Grafema\Patterns;
use Grafema\File\File;
use Grafema\File\Image;

class Media extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'media';

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
				if ( ! $file_path ) {
					continue;
				}

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

		return [
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
		];
	}
}

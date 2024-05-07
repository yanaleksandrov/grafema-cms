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
use Grafema\Sanitizer;
use Grafema\Url;

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
		$posts = [];
		$files = Sanitizer::array( $_FILES ?? [] );
		foreach ( $files as $file ) {
			// upload original image
			$uploadedFile = ( new File() )->to( GRFM_UPLOADS . 'i/original/' )->upload( $file );

			if ( ! $uploadedFile instanceof File ) {
				continue;
			}

			// now make smaller copies
			$filepath = Sanitizer::path( $uploadedFile->path ?? '' );
			if ( ! $filepath ) {
				continue;
			}

			$sizes = Patterns\Registry::get( 'images' );
			$sizes = Sanitizer::array( $sizes );
			foreach ( $sizes as $size ) {
				[ $name, $mime, $width, $height ] = (
					new Sanitizer(
						$size,
						[
							'name'   => 'text',
							'mime'   => 'mime',
							'width'  => 'absint',
							'height' => 'absint',
						]
					)
				)->values();

				if ( ! $mime || ! $width || ! $height ) {
					continue;
				}

				$file_resized = sprintf( '/i/%s/', implode( 'x', [ $width, $height ] ) );
				$file_resized = str_replace( '/i/original/', $file_resized, $filepath );

				( new Image() )->fromFile( $filepath )->thumbnail( $width, $height )->toFile( $file_resized, $mime );
			}

			$post_id = Post::add(
				'media',
				[
					'status' => 'publish',
					'slug'   => $uploadedFile->data['basename'],
					'fields' => [
						'mime' => $uploadedFile->data['mime'],
					],
				]
			);

			if ( $post_id ) {
				$posts[] = Post::get( 'media', $post_id );
			}
		}

		return [
			'notice'   => I18n::_s( '%d files have been successfully uploaded to the library', count( $files ) ),
			'uploaded' => count( $posts ) > 0,
			'posts'    => $posts,
		];
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
				$file = ( new File() )->to( GRFM_UPLOADS . 'i/' )->grab( $url );
				print_r( $file );
			}
		}

		return [
			'files'      => $files,
			'notice'     => I18n::_s( '%d files have been successfully uploaded to the library', count( $files ) ),
			'filesCount' => count( $files ),
		];
	}
}

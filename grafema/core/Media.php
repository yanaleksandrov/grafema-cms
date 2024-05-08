<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Grafema;

use Grafema\File\Image;
use Grafema\Post\Post;

/**
 * Grafema administration Media API.
 *
 * @since 1.0.0
 */
class Media {

	/**
	 * Uploads the file transmitted in the form to the Grafema downloads folder
	 * and creates an entry about the file in the database (adds the file to the library).
	 *
	 * It works with elements of the global variable $_FILES, the function needs to specify
	 * an array of file data and the function itself will upload the file to the Grafema
	 * downloads folder. If the file type is an image, it will create smaller copies of it.
	 *
	 * @param array $file Array that represents a `$_FILES` upload array.
	 * @return Errors|int
	 * @since 1.0.0
	 */
	public static function upload( array $file ): int|Errors {
		// TODO: add checking user capabilities

		$targetDir = sprintf( '%si/original/', GRFM_UPLOADS );

		// upload original image
		$originalFile = Files::upload( $file, $targetDir, function( $file ) {
			$sizes = Patterns\Registry::get( 'images' );
			$types = [ 'image/jpeg', 'image/gif', 'image/png', 'image/bmp', 'image/webp', 'image/avif', 'image/tiff', 'image/x-icon' ];

			// now make smaller copies for images
			if ( in_array( $file->mime, $types, true ) && is_array( $sizes ) ) {
				foreach ( $sizes as $size ) {
					$width  = Sanitizer::absint( $size['width'] ?? 0 );
					$height = Sanitizer::absint( $size['height'] ?? 0 );
					if ( ! $width || ! $height ) {
						continue;
					}

					// TODO: add file storage variations
					$filepathResize = str_replace(
						'/i/original/',
						sprintf( '/i/%s/', implode( 'x', [ $width, $height ] ) ),
						$file->path
					);

					( new Image() )->fromFile( $file->path )->thumbnail( $width, $height )->toFile( $filepathResize, $file->mime );
				}
			}
		} );

		if ( $originalFile instanceof Errors ) {
			return $originalFile;
		}

		return Post::add(
			'media',
			[
				'status' => 'publish',
				'title'  => $originalFile->basename,
				'slug'   => $originalFile->basename,
				'fields' => [
					'mime' => $originalFile->mime,
				],
			]
		);
	}

	public static function grab( string $url ) {

	}
}

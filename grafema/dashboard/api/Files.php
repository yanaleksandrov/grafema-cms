<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Dashboard\Form;
use Grafema\File\File;
use Grafema\File\Csv;
use Grafema\Sanitizer;
use Grafema\View;

class Files extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'files';

	/**
	 * Upload files from external url.
	 *
	 * @since 1.0.0
	 */
	public static function upload(): File|array
	{
		$files = $_FILES ?? [];
		if ( $files ) {
			foreach ( $files as $file ) {
				$uploadedFile = ( new File() )->to( GRFM_UPLOADS . 'i/' )->upload( $file );

				if ( ! $uploadedFile instanceof File ) {
					continue;
				}

				$filepath = Sanitizer::path( $uploadedFile->path ?? '' );
				$rows     = Csv::import( $filepath );

				View::include(
					GRFM_DASHBOARD . 'forms/grafema-posts-import-fields.php',
					[
						'samples'  => $rows[0] ?? [],
						'filepath' => $filepath,
					]
				);

				return [
					'fields' => Form::view( 'posts-import-fields', true ),
				];
			}
		}
		return [];
	}
}

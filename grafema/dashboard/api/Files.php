<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\File\File;
use Grafema\File\Csv;
use Grafema\Sanitizer;
use Grafema\Url;
use Grafema\I18n;
use Grafema\Post\Type;
use Grafema\Post\Status;

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
				$_file = ( new File() )->to( GRFM_UPLOADS . 'i/' )->upload( $file );

				if ( ! $_file instanceof File ) {
					continue;
				}

				$filepath = Sanitizer::path( $_file->path ?? '' );
				$rows     = Csv::import( $filepath );
				$samples  = $rows[0] ?? [];

				$fields = [
					[
						'type'   => 'group',
						'label'  => I18n::__( 'Required Data' ),
						'fields' => [
							[
								'name'        => 'type',
								'type'        => 'select',
								'instruction' => sprintf( I18n::__( 'Sample: <samp>%s</samp>' ), 'pages' ),
								'attributes'  => [
									'x-select' => '',
									'class'    => 'dg g-1 ga-2',
								],
								'options' => Type::get(),
							],
							[
								'name'        => 'status',
								'type'        => 'select',
								'instruction' => I18n::__( 'Set default post status, if not specified' ),
								'attributes'  => [
									'x-select' => '',
									'class'    => 'dg g-1 ga-2',
								],
								'options' => Status::get(),
							],
							[
								'name'        => 'author',
								'type'        => 'select',
								'instruction' => I18n::__( 'Set author, if not specified' ),
								'attributes'  => [
									'x-select' => '',
									'class'    => 'dg g-1 ga-2',
								],
								'options' => [
									'1' => 'Yan Aleksandrov',
								],
							],
						],
					],
					[
						'type'   => 'group',
						'label'  => I18n::__( 'Map Data' ),
						'fields' => [],
					],
				];

				foreach ( $samples as $index => $sample ) {
					$fields[1]['fields'][] = [
						'name'        => 'map[' . $index . ']',
						'type'        => 'select',
						'instruction' => I18n::_s( 'Sample: %s', '<samp>' . $sample . '</samp>' ),
						'options'     => [
							''     => I18n::__( 'No import' ),
							'main' => [
								'label'   => I18n::__( 'Main fields' ),
								'options' => [
									'name'     => I18n::__( 'Post ID' ),
									'author'   => I18n::__( 'Author ID' ),
									'views'    => I18n::__( 'Views count' ),
									'type'     => I18n::__( 'Type' ),
									'title'    => I18n::__( 'Title' ),
									'content'  => I18n::__( 'Content' ),
									'created'  => I18n::__( 'Created at' ),
									'modified' => I18n::__( 'Modified at' ),
									'status'   => I18n::__( 'Status' ),
								],
							],
						],
						'attributes' => [
							'x-select' => '',
							'class'    => 'dg g-1 ga-2',
						],
					];
				}

				$fields[] = [
					'name'     => 'custom',
					'type'     => 'custom',
					'callback' => fn () => '<input type="hidden" value="' . $filepath . '" name="filename">',
				];

				\Dashboard\Form::register(
					'import-posts-fields',
					[],
					function ( $form ) use ( $fields ) {
						$form->addFields( $fields );
					}
				);

				$data = [
					[
						'target' => 'input[type="file"]',
						'method' => 'value',
					],
					[
						'fragment' => \Dashboard\Form::view( 'import-posts-fields', true ),
						'target'   => 'body',
						'method'   => 'alpine',
					],
				];
				print_r( $_file );
			}
		}
		return [];
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

		return [
			'files'      => $files,
			'notice'     => I18n::_s( '%d files have been successfully uploaded to the library', count( $files ) ),
			'filesCount' => count( $files ),
		];
	}
}

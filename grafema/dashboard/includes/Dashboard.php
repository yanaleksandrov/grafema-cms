<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

use Grafema\Db;
use Grafema\Dir;
use Grafema\Api;
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Post\Status;
use Grafema\Post\Type;
use Grafema\Url;
use Grafema\User;
use Grafema\Debug;
use Grafema\Users\Roles;
use Grafema\View;
use Grafema\File\Csv;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @since 1.0.0
 */
class Dashboard extends Grafema\App\App
{
	use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{
		/**
		 * Add roles and users.
		 *
		 * @since 1.0.0
		 */
		Roles::register(
			'admin',
			I18n::__( 'Administrator' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
				'manage_options',
				'manage_update',
				'manage_import',
				'manage_export',
				'themes_install',
				'themes_switch',
				'themes_delete',
				'plugins_install',
				'plugins_activate',
				'plugins_delete',
				'users_create',
				'users_edit',
				'users_delete',
			]
		);

		Roles::register(
			'editor',
			I18n::__( 'Editor' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
			]
		);

		Roles::register(
			'author',
			I18n::__( 'Author' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
			]
		);

		Roles::register(
			'subscriber',
			I18n::__( 'Subscriber' ),
			[
				'read',
			]
		);

		/**
		 * Set up default post types: "pages" & "media".
		 *
		 * @since 1.0.0
		 */
		Type::register(
			'pages',
			[
				'labels' => [
					'name'        => I18n::__( 'Page' ),
					'name_plural' => I18n::__( 'Pages' ),
					'add'         => I18n::__( 'Add New' ),
					'edit'        => I18n::__( 'Edit Page' ),
					'update'      => I18n::__( 'Update Page' ),
					'view'        => I18n::__( 'View Page' ),
					'view_plural' => I18n::__( 'View Pages' ),
					'search'      => I18n::__( 'Search Pages' ),
					'all_items'   => I18n::__( 'All Pages' ),
					'published'   => I18n::__( 'Page published' ),
					'scheduled'   => I18n::__( 'Page scheduled' ),
					'updated'     => I18n::__( 'Page updated' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => true,
				'searchable'   => true,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_bar'  => true,
				'position'     => 20,
				'menu_icon'    => 'ph ph-folders',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
			]
		);

		Type::register(
			'media',
			[
				'labels' => [
					'name'        => I18n::__( 'Storage' ),
					'name_plural' => I18n::__( 'Storage' ),
					'add'         => I18n::__( 'Upload' ),
					'edit'        => I18n::__( 'Edit Media' ),
					'update'      => I18n::__( 'Update Attachment' ),
					'view'        => I18n::__( 'View Attachment' ),
					'view_plural' => I18n::__( 'View Attachments' ),
					'search'      => I18n::__( 'Search Attachments' ),
					'all_items'   => I18n::__( 'Library' ),
					'published'   => I18n::__( 'Attachment published.' ),
					'scheduled'   => I18n::__( 'Attachment scheduled.' ),
					'updated'     => I18n::__( 'Attachment updated.' ),
				],
				'description'  => '',
				'public'       => true,
				'hierarchical' => true,
				'searchable'   => 0,
				'show_ui'      => true,
				'show_in_menu' => true,
				'show_in_bar'  => true,
				'position'     => 30,
				'menu_icon'    => 'ph ph-dropbox-logo',
				'capabilities' => ['types_edit'],
				'supports'     => ['title', 'editor', 'thumbnail', 'fields'],
				'taxonomies'   => [],
				'can_export'   => true,
			]
		);

		/**
		 * Override response
		 *
		 * @since 1.0.0
		 */
		Hook::add(
			'grafema_api_response',
			function ( $data, $slug ) {
				switch ( $slug ) {
					case 'user/sign-up':
						$isUser = $data instanceof User;
						$data   = [
							[
								'target'   => $isUser ? 'body' : '',
								'fragment' => $isUser ? Url::sign_in() : '',
								'method'   => $isUser ? 'redirect' : '',
								'data'     => $data,
							],
						];
						break;
					case 'files/grab':
						$data = [
							[
								'fragment' => sprintf( I18n::__( '%d files have been successfully uploaded to the library' ), count( $data ) ),
								'target'   => 'body',
								'method'   => 'notify',
								'custom'   => [
									'type'     => count( $data ) > 0 ? 'success' : 'error',
									'duration' => 5000,
								],
							],
						];
						break;
					case 'posts/import':
						ob_start();
						View::part(
							'templates/states/completed',
							[
								'title'       => sprintf( I18n::__( 'Import is completed' ), count( $data ) ),
								'instruction' => sprintf( I18n::__( '%d posts was successfully imported. Do you want <a href="%s">to launch a new import?</a>' ), count( $data ), '/dashboard/import' ),
							]
						);
						$fragment = ob_get_clean();

						$data = [
							[
								'fragment' => $fragment,
								'target'   => 'body',
								'method'   => 'alpine',
							],
						];
						break;
					case 'upload/file':
						$filepath = $data->path ?? '';
						if ( $filepath ) {
							$rows    = Csv::import( $filepath );
							$samples = $rows[0] ?? [];

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
									'instruction' => sprintf( I18n::__( 'Sample: <samp>%s</samp>' ), $sample ),
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

							Form::register(
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
									'fragment' => Form::view( 'import-posts-fields', true ),
									'target'   => 'body',
									'method'   => 'alpine',
								],
							];
						}
						break;
					case 'extensions':
						$data = [
							[
								'fragment' => $data,
								'target'   => 'body',
								'method'   => 'alpine',
							],
						];
						break;
					default:
						break;
				}

				return $data;
			},
			10,
			2
		);

		/**
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 1.0.0
		 */
		Api::create( sprintf( '%sapi', GRFM_DASHBOARD ), '/api' );

		/**
		 * Now the code is exclusively for the administrative panel.
		 * Define a constants.
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_DASHBOARD', true );

		/**
		 * Include CSS styles & JS scripts.
		 *
		 * @since 1.0.0
		 */
		$styles = ['phosphor', 'colorist', 'datepicker', 'drooltip', 'flags', 'prism', 'slimselect', 'main'];
		foreach ( $styles as $style ) {
			Asset::enqueue( $style, '/dashboard/assets/css/' . $style . '.css', [], GRFM_VERSION );
		}

		$scripts = ['index', 'ajax', 'slimselect', 'drooltip', 'alpine.min', 'dragula.min', 'croppr.min', 'prism.min'];
		foreach ( $scripts as $script ) {
			$data = [];
			if ( $script === 'index' ) {
				$data['data'] = [
					'apiurl' => 'https://cms.codyshop.ru/api/',
					// TODO: move to a later
					'query'  => sprintf( '%s %s %sQ', Debug::timer( 'getall' ), Debug::memory_peak(), Db::queries() ),
				];
			}

			if ( $script === 'index' ) {
				// $data['dependencies'] = [ 'dragula-min-js' ];
			}
			Asset::enqueue( $script, '/dashboard/assets/js/' . $script . '.js', $data );
		}

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Hook::add( 'grafema_dashboard_header', fn () => Asset::plug( '*.css' ) );
		Hook::add( 'grafema_dashboard_footer', fn () => Asset::plug( '*.js' ) );

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Menu::init();

		/**
		 * Register new forms
		 *
		 * @since 1.0.0
		 */
		$forms = (new Dir\Dir( GRFM_DASHBOARD . 'forms' ))->getFiles( '*.php' );
		foreach ( $forms as $form ) {
			require_once $form;
		}
	}
}

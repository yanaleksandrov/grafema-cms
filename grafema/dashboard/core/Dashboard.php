<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Dashboard;

use Grafema\Db;
use Grafema\Dir;
use Grafema\Api;
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Post\Type;
use Grafema\Debug;
use Grafema\Users\Roles;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @since 1.0.0
 */
class Dashboard extends \Grafema\App\App
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
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 1.0.0
		 */
		Api::create( sprintf( '%sapi', GRFM_DASHBOARD ), '/api' );

		// TODO: stop in none dashboard pages!

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
					'grafema' => [
						// TODO: move to a later
						'query'    => sprintf( '%s %s %sQ', Debug::timer( 'getall' ), Debug::memory_peak(), Db::queries() ),
						'apiurl'   => 'https://cms.codyshop.ru/api/',
						'posts'    => '',
						'showMenu' => false,
					]
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
		//require_once GRFM_DASHBOARD . 'forms/grafema-user-profile.php';
		//require_once GRFM_DASHBOARD . 'forms/grafema-user-sign-in.php';
	}
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

use Grafema\{Api, Db, Dir, Debug, Hook, Html, I18n, Is, Plugins, Post\Type, Route, Url, User, Users\Roles, View, Csrf};

use Dashboard\Constants;

/**
 * Setup system core constants.
 *
 * @since 1.0.0
 */
const GRFM_PATH                   = __DIR__ . '/';
const GRFM_VERSION                = '1.0.0';
const GRFM_REQUIRED_PHP_VERSION   = '8.1';
const GRFM_REQUIRED_MYSQL_VERSION = '5.6';

/**
 * Include required files: app configuration & autoloader.
 *
 * @since 1.0.0
 */
array_map(function ($include) {
	$include_path = sprintf( '%s%s.php', GRFM_PATH, $include );
	if (file_exists($include_path)) {
		require_once $include_path;
	}
}, ['config', 'autoloader']);

/**
 * Create a single entry point to the website.
 *
 * @since 1.0.0
 */
(function() {
	// save as .htaccess
	if ( ! file_exists( GRFM_PATH . '.htaccess' ) ) {
		file_put_contents(
			GRFM_PATH . '.htaccess',
			'<<<HTACCESS
Options +FollowSymLinks
# Options +SymLinksIfOwnerMatch
Options -Indexes
DirectoryIndex index.php index.html
AddDefaultCharset UTF-8

<IfModule mod_rewrite.c>
    RewriteEngine on

    # for 301-redirect http to https
    # RewriteCond %{HTTPS} !=on
    # RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,QSA,L]

    RewriteBase /
    RewriteCond $1 !^(index\.php|uploads|robots\.txt|favicon\.ico)
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php/$1 [L,QSA]
    # or for fastCGI
    # RewriteRule . /index.php [L]
</IfModule>

<IfModule mod_expires.c>
    <filesmatch ".(jpg|jpeg|gif|png|webp|svg|ico|css|js|woff|woff2)$">
        ExpiresActive on
        ExpiresDefault "access plus 1 month"
    </filesmatch>
</IfModule>
            HTACCESS'
		);
	}
})();

/**
 * Launch debug mode & run benchmark.
 *
 * @since 1.0.0
 */
Debug::launch();
Debug::timer();

/**
 * Generate CSRF token.
 *
 * @since 1.0.0
 */
( new Csrf\Csrf(
	new Csrf\Providers\NativeHttpOnlyCookieProvider()
) )->generate( 'token' );

/**
 * Launch the installer if Grafema is not installed.
 *
 * @since 1.0.0
 */
if ( ! Is::installed() ) {
	\Dashboard\Install::init();
	exit;
}

/**
 * Launch database connection.
 *
 * @since  1.0.0
 */
Db::init();

/**
 * Check for the required PHP version, and the MySQL extension or a database drop-in.
 * Dies if requirements are not met.
 *
 * @since  1.0.0
 */
(function() {
	$serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
	if ( ! in_array( $serverProtocol, ['HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3'] ) ) {
		$serverProtocol = 'HTTP/1.0';
	}

	$php_version               = strval( phpversion() );
	$php_version_is_compatible = version_compare( GRFM_REQUIRED_PHP_VERSION, $php_version, '<=' );
	if ( ! $php_version_is_compatible ) {
		header( sprintf( '%s 500 Internal Server Errors', $serverProtocol ), true, 500 );
		header( 'Content-Type: text/html; charset=utf-8' );

		printf(
			I18n::__( 'Your server is running PHP version "%1$s" but Grafema %2$s requires at least %3$s.' ),
			$php_version,
			GRFM_VERSION,
			GRFM_REQUIRED_PHP_VERSION
		);

		exit;
	}

	$db_version               = strval( Db::version() );
	$db_version_is_compatible = version_compare( GRFM_REQUIRED_MYSQL_VERSION, $db_version, '<=' );
	if ( ! $db_version_is_compatible ) {
		header( sprintf( '%s 500 Internal Server Errors', $serverProtocol ), true, 500 );
		header( 'Content-Type: text/html; charset=utf-8' );

		printf(
			I18n::__( 'Your server is running DataBase version %1$s but Grafema %2$s requires at least %3$s.' ),
			$db_version,
			GRFM_VERSION,
			GRFM_REQUIRED_MYSQL_VERSION
		);

		exit;
	}
})();

$route = new Route();
$route->mount('', function() use ( $route ) {

	/**
	 * Define auxiliary constants necessary for the application and make them available in any part.
	 *
	 * @since 1.0.0
	 */
	Constants::init();

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
	 * Set up current user.
	 *
	 * @since 1.0.0
	 */
	User::current();

	/**
	 * Load installed and launch active plugins.
	 *
	 * @since 1.0.0
	 */
	$plugins = Plugins\Manager::init( function ( $plugins ) {
		$paths = (new Dir\Dir( GRFM_PLUGINS ))->getFiles( '*.php', 1 );

		if ( ! $paths ) {
			return null;
		}
		$plugins->register( $paths );
	} );
	//$plugins->launch();
	//$plugins->install();
	//$plugins->uninstall();
	//$plugins->activate();
	//$plugins->deactivate();

	/**
	 * Triggered after Grafema plugins is loaded & ready for use.
	 *
	 * @since 1.0.0
	 */
	Hook::apply( 'grafema_plugins_loaded' );

	/**
	 * Add core API endpoints.
	 * Important! If current request is request to API, stop code execution after Api::create().
	 *
	 * @since 1.0.0
	 */
	Api::create( sprintf( '%sapi', GRFM_DASHBOARD ), '/api' );

	http_response_code( 200 );

	/**
	 * Load private administrative panel.
	 *
	 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
	 * @since 1.0.0
	 */
	$dashboard = sprintf( '%s(.*)', str_replace( GRFM_PATH, '/', GRFM_DASHBOARD ) );

	$route->all( $dashboard, function( $slug ) use ( $route ) {

		/**
		 * Run the installer if Grafema is not installed.
		 *
		 * @since 1.0.0
		 */
		if ( $slug !== 'install' && ! Is::installed() ) {
			View::redirect(
				Url::install()
			);
			exit;
		}

		/**
		 * Out from dashboard if user is not logged.
		 * Also leave access for the registration and password recovery pages.
		 *
		 * @since 1.0.0
		 */
		if ( ! in_array( $slug, ['sign-in', 'sign-up', 'reset-password'], true ) && ! User::logged() && Is::installed() ) {
			View::redirect(
				Url::sign_in()
			);
			exit;
		}

		/**
		 * Not allow some slugs for logged user, they are reserved.
		 *
		 * @since 1.0.0
		 */
		$black_list_slugs = ['install', 'sign-in', 'sign-up', 'reset-password'];
		if ( in_array( $slug, $black_list_slugs, true ) && User::logged() ) {
			View::redirect(
				Url::site( 'dashboard' )
			);
			exit;
		}

		/**
		 * Run the installer if Grafema is not installed.
		 *
		 * @since 1.0.0
		 */
		Dashboard\Dashboard::init();

		/**
		 * The administrative panel also has a single entry point.
		 *
		 * @since 1.0.0
		 */
		echo ( new Html() )->beautify(
			View::get(
				GRFM_DASHBOARD . 'index',
				[
					'route' => $route,
					'slug'  => $slug,
				]
			)
		);

		/**
		 * Grafema dashboard is fully loaded.
		 *
		 * @param string $slug Current page slug.
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_dashboard_loaded', $slug );
	} );

	/**
	 * None dashboard pages: website frontend output.
	 *
	 * @param string $slug Current page slug.
	 * @since 1.0.0
	 */
	$route->get( '(.*)', function ( $slug ) use ( $route ) {

		/**
		 * Run the installer if Grafema is not installed.
		 *
		 * @since 1.0.0
		 */
		if ( Is::installed() && $slug === 'install' ) {
			View::redirect( Url::site( 'dashboard' ) );
			exit;
		}

		printf( '%s %s %sQ', Debug::timer( 'getall' ), Debug::memory_peak(), Db::queries() );
	} );
});

$route->run();
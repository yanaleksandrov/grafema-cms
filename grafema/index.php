<?php
use Grafema\{
	Db,
	Dir,
	Debug,
	Hook,
	Html,
	I18n,
	Is,
	Part,
	Plugins,
	Route,
	Url,
	User,
	View,
	Post
};

/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	define( 'GRFM_PATH', __DIR__ . '/' );
}

// TODO: move to Debug class
ini_set( 'error_reporting', E_ALL );
ini_set( 'display_errors', 1 );
ini_set( 'display_startup_errors', 1 );

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
 * Launch new database connection.
 *
 * @since  1.0.0
 */
new Db();

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
		header( sprintf( '%s 500 Internal Server Error', $serverProtocol ), true, 500 );
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
		header( sprintf( '%s 500 Internal Server Error', $serverProtocol ), true, 500 );
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

/**
 * Launch debug mode & run benchmark
 *
 * @since 1.0.0
 */
Debug::check();
Debug::timer();

/**
 *
 *
 * @since 1.0.0
 */
Part::register(
	'dashboard',
	[
		GRFM_DASHBOARD,
		...(new Dir\Dir( GRFM_PLUGINS ))->getFolders(),
	]
);

/**
 * Run the installer if Grafema is not installed.
 *
 * @since 1.0.0
 */
Install::init();

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
			<IfModule mod_rewrite.c>
				RewriteEngine On
				RewriteBase /

				RewriteCond %{REQUEST_FILENAME} !-f
				RewriteCond %{REQUEST_FILENAME} !-d
				RewriteRule ^(.*)$ index.php?/$1 [L]
			</IfModule>
            HTACCESS'
		);
	}
})();

/**
 * Define auxiliary constants necessary for the application and make them available in any part.
 *
 * @since 1.0.0
 */
$route = new Route();
$route->before(
	'GET|POST|PUT|DELETE',
	'/.*',
	function () {
		Constants::init();
	}
);
$route->run();

/**
 * Set up current user.
 *
 * @since 1.0.0
 */
User::current();

/**
 * Load and launch installed and active plugins.
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
$plugins->launch();
// $plugins->install();
// $plugins->uninstall();
// $plugins->activate();
// $plugins->deactivate();

/**
 * Triggered after Grafema plugins is loaded.
 *
 * @since 1.0.0
 */
Hook::apply( 'grafema_plugins_loaded' );

/**
 * Set up default post types: "pages" & "media"
 *
 * @since 1.0.0
 */
Post\Type::register(
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

Post\Type::register(
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
 * Include all dashboard functions.
 *
 * @since 1.0.0
 */
Dashboard::init();

/**
 * Grafema is fully loaded, but before any headers are sent.
 *
 * @since 1.0.0
 */
Hook::apply( 'grafema_loaded' );

/**
 * Load private administrative panel.
 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
 *
 * @since 1.0.0
 */
$dashboard = str_replace( GRFM_PATH, '/', GRFM_DASHBOARD );
$route     = new Route();

$route->get( sprintf( '%s(.*)', $dashboard ), function ( $slug ) use ( $route ) {
	http_response_code( 200 );

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
	if ( ! in_array( $slug, ['sign-in', 'sign-up', 'reset-password'], true )
		&& ! User::logged() && Is::installed()
	) {
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
	 * The administrative panel also has a single entry point.
	 *
	 * @since 1.0.0
	 */
	ob_start();
	View::output(
		GRFM_DASHBOARD . 'index.php',
		[
			'route' => $route,
			'slug'  => $slug,
		]
	);
	echo ( new Html() )->beautify( ob_get_clean() );

	/**
	 * Grafema dashboard is fully loaded.
	 *
	 * @param string $slug Current page slug.
	 *
	 * @since 1.0.0
	 */
	Hook::apply( 'grafema_dashboard_loaded', $slug );

	exit;
} );
$route->run();

// ready for test
// $count      = 10;
// $start_time = microtime( true );
// for ( $i = 1; $i <= $count; $i++ ) {
//	if ( $i === $count ) {
//
//	} else {
//
//	}
// }
// echo 'Time:  ' . number_format( ( microtime( true ) - $start_time ), 6 ) . " Seconds\n";

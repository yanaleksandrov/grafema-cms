<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

use Grafema\{
	Db,
	Dir,
	Debug,
	Hook,
	Html,
	I18n,
	Is,
	Plugins,
	Route,
	Url,
	User,
	View,
	Csrf,
};

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

/**
 * Define auxiliary constants necessary for the application and make them available in any part.
 *
 * @since 1.0.0
 */
$route = new Route();
$route->before( 'GET|POST|PUT|DELETE', '/.*', function () {
	Constants::init();
});
$route->run();

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
 * Include all dashboard functions & launch API.
 *
 * @since 1.0.0
 */
\Dashboard\Dashboard::init();

/**
 * Load private administrative panel.
 *
 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
 * TODO: move routers to dashboard.
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
	if ( ! in_array( $slug, ['sign-in', 'sign-up', 'reset-password'], true ) && ! User::logged() && Is::installed() ) {
//		var_dump( $slug );
//		exit;
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
} );

$route->get( '(.*)', function ( $slug ) use ( $route ) {
	http_response_code( 200 );

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

$route->run();
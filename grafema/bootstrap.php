<?php

use Grafema\{
    Db,
    Dir,
    Debug,
    I18n,
    Is,
    Themes,
    Plugins,
    User,
    Csrf
};

/**
 * Setup system core constants.
 *
 * @since 2025.1
 */
const GRFM_PATH                   = __DIR__ . '/';
const GRFM_VERSION                = '2025.1';
const GRFM_REQUIRED_PHP_VERSION   = '8.1';
const GRFM_REQUIRED_MYSQL_VERSION = '5.6';

/**
 * Include required files: app environment, autoload & configurations.
 *
 * @since 2025.1
 */
require_once GRFM_PATH . 'env.php';
require_once GRFM_PATH . 'autoload.php';
require_once GRFM_PATH . 'config.php';

/**
 * Launch debug mode & run benchmark.
 *
 * @since 2025.1
 */
Debug::launch();
Debug::timer();

/**
 * Generate CSRF token.
 *
 * @since 2025.1
 */
( new Csrf\Csrf(
    new Csrf\Providers\NativeHttpOnlyCookieProvider()
) )->generate('token');

/**
 * Launch the installer if Grafema is not installed.
 *
 * @since 2025.1
 */
if (! Is::installed()) {
    Dashboard\Install::init();
    exit;
}

/**
 * Check for the required PHP version, and the MySQL extension or a database drop-in.
 * Dies if requirements are not met.
 *
 * @since  2025.1
 */
(function () {
    $serverProtocol = $_SERVER['SERVER_PROTOCOL'] ?? '';
    if (! in_array($serverProtocol, ['HTTP/1.1', 'HTTP/2', 'HTTP/2.0', 'HTTP/3'])) {
        $serverProtocol = 'HTTP/1.0';
    }

    $php_version               = strval(phpversion());
    $php_version_is_compatible = version_compare(GRFM_REQUIRED_PHP_VERSION, $php_version, '<=');
    if (! $php_version_is_compatible) {
        header(sprintf('%s 500 Internal Server Error', $serverProtocol), true, 500);
        header('Content-Type: text/html; charset=utf-8');

	    I18n::f(
			'Your server is running PHP version ":phpVersion" but Grafema :grafemaVersion requires at least :phpRequiredVersion.',
			$php_version,
			GRFM_VERSION,
			GRFM_REQUIRED_PHP_VERSION
	    );

        exit;
    }

    $db_version               = strval(Db::version());
    $db_version_is_compatible = version_compare(GRFM_REQUIRED_MYSQL_VERSION, $db_version, '<=');
    if (! $db_version_is_compatible) {
        header(sprintf('%s 500 Internal Server Error', $serverProtocol), true, 500);
        header('Content-Type: text/html; charset=utf-8');

	    I18n::f(
		    'Your server is running PHP version ":dbVersion" but Grafema :grafemaVersion requires at least :dbRequiredVersion.',
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
 * @since 2025.1
 */
Dashboard\Constants::init();

/**
 * Set up current user.
 *
 * @since 2025.1
 */
User::current();

/**
 * Load installed and launch active plugins & themes.
 *
 * @since 2025.1
 */
Plugins::register(function () {
    return ( new Dir(GRFM_PLUGINS) )->getFiles('*/*.php');
});

Themes::register(function () {
    return ( new Dir(GRFM_THEMES) )->getFiles('*/*.php');
});

require_once GRFM_PATH . 'migrations.php';
require_once GRFM_PATH . 'routes.php';

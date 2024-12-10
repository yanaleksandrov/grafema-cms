<?php

use Grafema\{
    Api,
    Db,
    Dir,
    Option,
    Debug,
    Hook,
    Html,
    I18n,
    Is,
    Themes,
    Plugins,
    Post\Type,
    Route,
    Url,
    User,
    View,
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
 * Include required files: app configuration & autoload.
 *
 * @since 2025.1
 */
require_once GRFM_PATH . 'config.php';
require_once GRFM_PATH . 'autoload.php';

/**
 * Launch database connection.
 *
 * @since  2025.1
 */
Db::init();

/**
 * Create a single entry point to the website.
 *
 * @since 2025.1
 */
(function () {
    // save as .htaccess
    if (! file_exists(GRFM_PATH . '.htaccess')) {
        file_put_contents(
            GRFM_PATH . '.htaccess',
            'Options +FollowSymLinks
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
    <filesmatch ".(jpg|jpeg|gif|png|webp|svg|ico|css|js|woff|woff2|manifest|webmanifest)$">
        ExpiresActive on
        ExpiresDefault "access plus 1 month"
    </filesmatch>
</IfModule>'
        );
    }
})();

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

        printf(
            I18n::_t('Your server is running PHP version "%1$s" but Grafema %2$s requires at least %3$s.'),
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

        printf(
            I18n::_t('Your server is running DataBase version %1$s but Grafema %2$s requires at least %3$s.'),
            $db_version,
            GRFM_VERSION,
            GRFM_REQUIRED_MYSQL_VERSION
        );

        exit;
    }
})();

/**
 * Setting up the priority rule for translations.
 *
 * @since 2025.1
 */
I18n::configure(
    [
        GRFM_CORE      => GRFM_DASHBOARD,
        GRFM_DASHBOARD => GRFM_DASHBOARD,
        GRFM_PLUGINS   => GRFM_PLUGINS . ':dirname',
        GRFM_THEMES    => GRFM_THEMES . ':dirname',
    ],
    'i18n/%s'
);

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

/**
 * Add core API endpoints.
 * Important! If current request is request to API, stop code execution after Api::create().
 *
 * @since 2025.1
 */
Api::create('/api', sprintf('%sapp/Api', GRFM_DASHBOARD));

require_once GRFM_PATH . 'migrations.php';
require_once GRFM_PATH . 'routes.php';

//      Plugins::install();
//      Plugins::uninstall();
//      Plugins::activate();
//      Plugins::deactivate();

//      Grafema\Slug::migrate();
//      //Grafema\Meta::migrate();
//      Grafema\Attr::migrate( 'users', 'user' );

$user  = User::current();
$field = new Grafema\Field($user);
$meta  = new Grafema\Meta($user);
$attr  = new Grafema\Attr($user);

//      for ( $i = 1; $i <= 50; $i++ ) {
//          $dataTypes = [
//              'is_open_%d'     => (bool) mt_rand( 0, 1 ),
//              'pi_number_%d'   => 1 + (mt_rand() / mt_getrandmax()) * (1000 - 1),
//              'context_%d'     => 'Hello, world!',
//              'price'          => rand( 1, 10000000 ),
//              'date_%d'        => date('Y-m-d', strtotime(rand(2000, 2024) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT))),
//              'time_%d'        => date('H:i:s', strtotime(rand(0, 23) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT))),
//              'datetime_%d'    => date('Y-m-d H:i:s', strtotime(rand(2000, 2024) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT) . ' ' . str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT))),
//          ];
//
//          $randomKey   = array_rand( $dataTypes );
//          $randomValue = $dataTypes[ $randomKey ];
//          $key = sprintf( $randomKey, $i );
//
//          $field->add( $key, $randomValue, false );
//          Grafema\Post::add( 'pages', [
//              'author'  => 1,
//              'title'   => "Random title with #{$i}",
//              'content' => "Random content with title of number #{$i}",
//          ] );
//      }
//      $startTime = microtime(true);
//      echo '<pre>';
//      print_r( $attr->get( 'time' ) );
//var_dump( $attr->get( 'number', 'pi_number_556877' ) );
//  var_dump( $attr->get( 'number', 'price_23' ) );
//print_r( $field->get() );
//      var_dump( $field->add( 'test', 'My name is', false ) );
//  var_dump( $meta->get( 'datetime', 'datetime_499955' ) );
//print_r( Grafema\Slug::get( 'random-title-with-981530' ) );
//      echo "Time:  " . number_format(( microtime(true) - $startTime), 5) . " Seconds\n";
//      echo '</pre>';

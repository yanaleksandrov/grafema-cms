<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
use Grafema\{Api, Db, Dir, Option, Debug, Hook, Html, I18n, Is, Plugins, Post\Type, Route, Url, User, Users\Roles, View, Csrf};

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
 * Include required files: app configuration & autoloader.
 *
 * @since 2025.1
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
 * @since 2025.1
 */
(function() {
	// save as .htaccess
	if ( ! file_exists( GRFM_PATH . '.htaccess' ) ) {
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
) )->generate( 'token' );

/**
 * Launch the installer if Grafema is not installed.
 *
 * @since 2025.1
 */
if ( ! Is::installed() ) {
	Dashboard\Install::init();
	exit;
}

/**
 * Launch database connection.
 *
 * @since  2025.1
 */
Db::init();

/**
 * Check for the required PHP version, and the MySQL extension or a database drop-in.
 * Dies if requirements are not met.
 *
 * @since  2025.1
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
			I18n::_t( 'Your server is running PHP version "%1$s" but Grafema %2$s requires at least %3$s.' ),
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
			I18n::_t( 'Your server is running DataBase version %1$s but Grafema %2$s requires at least %3$s.' ),
			$db_version,
			GRFM_VERSION,
			GRFM_REQUIRED_MYSQL_VERSION
		);

		exit;
	}
})();

// TODO: disable route for files
try {
	$route = new Route();
	$route->mount('', function() use ( $route ) {

		/**
		 * Define auxiliary constants necessary for the application and make them available in any part.
		 *
		 * @since 2025.1
		 */
		Dashboard\Constants::init();

		/**
		 * Add roles and users.
		 *
		 * @since 2025.1
		 */
		Roles::register(
			'admin',
			I18n::_t( 'Administrator' ),
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
			I18n::_t( 'Editor' ),
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
			I18n::_t( 'Author' ),
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
			I18n::_t( 'Subscriber' ),
			[
				'read',
			]
		);

		/**
		 * Set up default post types: "pages" & "media".
		 *
		 * @since 2025.1
		 */
		Type::register(
			'pages',
			[
				'labels' => [
					'name'        => I18n::_t( 'Page' ),
					'name_plural' => I18n::_t( 'Pages' ),
					'add'         => I18n::_t( 'Add New' ),
					'edit'        => I18n::_t( 'Edit Page' ),
					'update'      => I18n::_t( 'Update Page' ),
					'view'        => I18n::_t( 'View Page' ),
					'view_plural' => I18n::_t( 'View Pages' ),
					'search'      => I18n::_t( 'Search Pages' ),
					'all_items'   => I18n::_t( 'All Pages' ),
					'published'   => I18n::_t( 'Page published' ),
					'scheduled'   => I18n::_t( 'Page scheduled' ),
					'updated'     => I18n::_t( 'Page updated' ),
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
					'name'        => I18n::_t( 'Storage' ),
					'name_plural' => I18n::_t( 'Storage' ),
					'add'         => I18n::_t( 'Upload' ),
					'edit'        => I18n::_t( 'Edit Media' ),
					'update'      => I18n::_t( 'Update Attachment' ),
					'view'        => I18n::_t( 'View Attachment' ),
					'view_plural' => I18n::_t( 'View Attachments' ),
					'search'      => I18n::_t( 'Search Attachments' ),
					'all_items'   => I18n::_t( 'Library' ),
					'published'   => I18n::_t( 'Attachment published.' ),
					'scheduled'   => I18n::_t( 'Attachment scheduled.' ),
					'updated'     => I18n::_t( 'Attachment updated.' ),
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
		 * @since 2025.1
		 */
		User::current();

		/**
		 * Load installed and launch active plugins.
		 *
		 * @since 2025.1
		 */
		$plugins = new Plugins\Manager( function ( $plugins ) {
			$paths = ( new Dir\Dir( GRFM_PLUGINS ) )->getFiles( '*.php', 1 );

			if ( ! $paths ) {
				return null;
			}
			$plugins->register( $paths );
		} );
		$plugins->launch();
		//$plugins->install();
		//$plugins->uninstall();
		//$plugins->activate();
		//$plugins->deactivate();

		/**
		 * Triggered after Grafema plugins is loaded & ready for use.
		 *
		 * @since 2025.1
		 */
		Hook::apply( 'grafema_plugins_loaded' );

		/**
		 * Load installed and launch active themes.
		 *
		 * @since 2025.1
		 */
		$themes = new Plugins\Manager( function ( $themes ) {
			$paths = (new Dir\Dir( GRFM_THEMES ))->getFiles( '*.php', 1 );

			if ( ! $paths ) {
				return null;
			}
			$themes->register( $paths );
		} );
		$themes->launch();
		//$themes->install();
		//$themes->uninstall();
		//$themes->activate();
		//$themes->deactivate();

		// set response code
		http_response_code( 200 );

		/**
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 2025.1
		 */
		Api::create( sprintf( '%sapi', GRFM_DASHBOARD ), '/api' );

		$query     = new Grafema\Query();
		$dashboard = trim( str_replace( GRFM_PATH, '/', GRFM_DASHBOARD ), '/' );

		/**
		 * Load private administrative panel.
		 *
		 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
		 * @since 2025.1
		 */
		$route->all( sprintf( '/%s/{slug}', $dashboard ), function( $slug ) use ( $query, $dashboard, $route ) {
			$query->set( 'slug', sprintf( '%s/%s', $dashboard, $slug ) );
			$query->set( match ( $slug ) {
				'sign-in'        => 'isSignIn',
				'sign-up'        => 'isSignUp',
				'reset-password' => 'isResetPassword',
				default          => 'isDashboard',
			}, true );

			if ( $query->is404 ) {
				http_response_code( 404 );
			}

			/**
			 * Run the installer if Grafema is not installed.
			 *
			 * @since 2025.1
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
			 * @since 2025.1
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
			 * @since 2025.1
			 */
			$black_list_slugs = ['install', 'sign-in', 'sign-up', 'reset-password'];
			if ( in_array( $slug, $black_list_slugs, true ) && User::logged() ) {
				View::redirect(
					Url::site( 'dashboard' )
				);
				exit;
			}

			/**
			 * Launch dashboard.
			 *
			 * @since 2025.1
			 */
			require_once GRFM_DASHBOARD . 'core/Dashboard.php';

			/**
			 * The administrative panel also has a single entry point.
			 *
			 * @since 2025.1
			 */
			$content = ( new Html() )->beautify(
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
			 * @param string $content Current page content.
			 * @param string $slug    Current page slug.
			 * @since 2025.1
			 */
			echo Hook::apply( 'grafema_dashboard_loaded', $content, $slug );
		});

		/**
		 * None dashboard pages: website frontend output.
		 *
		 * @param string $slug Current page slug.
		 * @since 2025.1
		 */
		$route->all('/{slug}', function( $slug ) use ( $query ) {
			$query->set( 'slug', $slug );
			if ( empty( $slug ) ) {
				$query->set( 'isHome', true );
			}

			if ( $query->is404 ) {
				http_response_code( 404 );
			}

			/**
			 * Run the installer if Grafema is not installed.
			 *
			 * @since 2025.1
			 */
			if ( Is::installed() && $slug === 'install' ) {
				View::redirect( Url::site( 'dashboard' ) );
				exit;
			}

			?>
<!DOCTYPE html>
<html lang="<?php echo I18n::locale(); ?>">
<head>
	<title>Menu</title>
	<meta charset="<?php Option::attr( 'charset', 'UTF-8' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/assets/images/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/assets/images/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/assets/images/favicons/favicon-16x16.png">
	<link rel="manifest" href="/dashboard/assets/images/favicons/site.webmanifest">
	<link rel="mask-icon" href="/dashboard/assets/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="theme-color" content="#ffffff">
<?php
	/**
	 * Prints scripts or data before the closing body tag on the dashboard.
	 *
	 * @since 2025.1
	 */
	Hook::apply( 'grafema_header' );
	?>
</head>
<body>
<?php
/**
 * Prints scripts or data before the closing body tag on the dashboard.
 *
 * @since 2025.1
 */
Hook::apply( 'grafema_footer' );
?>
</body>
</html>
			<?php
I18n::f( ':queriesQ :memory :memory_peak', Db::queries(), Debug::timer( 'getall' ), Debug::memory_peak() );
		});
	});

	$route->run();
} catch ( Error $e ) {
	echo Debug::print( $e );
}

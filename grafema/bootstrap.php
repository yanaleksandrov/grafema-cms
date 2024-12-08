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
foreach (
	[
		'config',
		'autoload',
		//'routes'
	] as $filename ) {
	require_once sprintf( '%s%s.php', GRFM_PATH, $filename );
}
//array_map( function ( $filename ) {
//	require_once sprintf( '%s%s.php', GRFM_PATH, $filename );
//}, [ 'config', 'autoload', 'routes' ] );

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
		header( sprintf( '%s 500 Internal Server Error', $serverProtocol ), true, 500 );
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
		header( sprintf( '%s 500 Internal Server Error', $serverProtocol ), true, 500 );
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
		 * Add roles and users.
		 *
		 * @since 2025.1
		 */
		User\Roles::register(
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

		User\Roles::register(
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

		User\Roles::register(
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

		User\Roles::register(
			'subscriber',
			I18n::_t( 'Subscriber' ),
			[
				'read',
			]
		);

		/**
		 * Set up default post types: "pages", "media" & "api-keys".
		 *
		 * @since 2025.1
		 */
		Type::register(
			key: 'pages',
			labelName: I18n::_t( 'Page' ),
			labelNamePlural: I18n::_t( 'Pages' ),
			labelAllItems: I18n::_t( 'All Pages' ),
			labelAdd: I18n::_t( 'Add New' ),
			labelEdit: I18n::_t( 'Edit Page' ),
			labelUpdate: I18n::_t( 'Update Page' ),
			labelView: I18n::_t( 'View Page' ),
			labelSearch: I18n::_t( 'Search Pages' ),
			labelSave: I18n::_t( 'Save Page' ),
			public: true,
			hierarchical: false,
			searchable: true,
			showInMenu: true,
			showInBar: true,
			canExport: true,
			canImport: true,
			capabilities: ['types_edit'],
			menuIcon: 'ph ph-folders',
			menuPosition: 20,
		);

		Type::register(
			key: 'media',
			labelName: I18n::_t( 'Storage' ),
			labelNamePlural: I18n::_t( 'Storage' ),
			labelAllItems: I18n::_t( 'Library' ),
			labelAdd: I18n::_t( 'Upload' ),
			labelEdit: I18n::_t( 'Edit Media' ),
			labelUpdate: I18n::_t( 'Update Media' ),
			labelView: I18n::_t( 'View Media' ),
			labelSearch: I18n::_t( 'Search Media' ),
			labelSave: I18n::_t( 'Save Media' ),
			public: true,
			hierarchical: false,
			searchable: false,
			showInMenu: true,
			showInBar: false,
			canExport: true,
			canImport: true,
			capabilities: ['types_edit'],
			menuIcon: 'ph ph-dropbox-logo',
			menuPosition: 30,
		);

		Type::register(
			key: 'api-keys',
			labelName: I18n::_t( 'API Key' ),
			labelNamePlural: I18n::_t( 'API Keys' ),
			labelAllItems: I18n::_t( 'All API Keys' ),
			labelAdd: I18n::_t( 'Add New Key' ),
			labelEdit: I18n::_t( 'Edit Key' ),
			labelUpdate: I18n::_t( 'Update Key' ),
			labelView: I18n::_t( 'View Key' ),
			labelSearch: I18n::_t( 'Search Keys' ),
			labelSave: I18n::_t( 'Save Key' ),
			public: false,
			hierarchical: false,
			searchable: false,
			showInMenu: false,
			showInBar: false,
			canExport: true,
			canImport: true,
			capabilities: ['types_edit'],
			menuIcon: 'ph ph-key',
			menuPosition: 30,
		);

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
		Plugins::register( function() {
			return ( new Dir( GRFM_PLUGINS ) )->getFiles( '*/*.php' );
		} );

		Themes::register( function() {
			return ( new Dir( GRFM_THEMES ) )->getFiles( '*/*.php' );
		} );
//		Plugins::install();
//		Plugins::uninstall();
//		Plugins::activate();
//		Plugins::deactivate();

		// set response code
		http_response_code( 200 );
		header( 'Content-Type: text/html; charset=utf-8' );

//		Grafema\Slug::migrate();
//		//Grafema\Meta::migrate();
//		Grafema\Attr::migrate( 'users', 'user' );

		$user  = User::current();
		$field = new Grafema\Field( $user );
		$meta  = new Grafema\Meta( $user );
		$attr  = new Grafema\Attr( $user );

//		for ( $i = 1; $i <= 50; $i++ ) {
//			$dataTypes = [
//				'is_open_%d'     => (bool) mt_rand( 0, 1 ),
//				'pi_number_%d'   => 1 + (mt_rand() / mt_getrandmax()) * (1000 - 1),
//				'context_%d'     => 'Hello, world!',
//				'price'          => rand( 1, 10000000 ),
//				'date_%d'        => date('Y-m-d', strtotime(rand(2000, 2024) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT))),
//				'time_%d'        => date('H:i:s', strtotime(rand(0, 23) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT))),
//				'datetime_%d'    => date('Y-m-d H:i:s', strtotime(rand(2000, 2024) . '-' . str_pad(rand(1, 12), 2, '0', STR_PAD_LEFT) . '-' . str_pad(rand(1, 31), 2, '0', STR_PAD_LEFT) . ' ' . str_pad(rand(0, 23), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT) . ':' . str_pad(rand(0, 59), 2, '0', STR_PAD_LEFT))),
//			];
//
//			$randomKey   = array_rand( $dataTypes );
//			$randomValue = $dataTypes[ $randomKey ];
//			$key = sprintf( $randomKey, $i );
//
//			$field->add( $key, $randomValue, false );
//			Grafema\Post::add( 'pages', [
//				'author'  => 1,
//				'title'   => "Random title with #{$i}",
//				'content' => "Random content with title of number #{$i}",
//			] );
//		}
//		$startTime = microtime(true);
//		echo '<pre>';
//		print_r( $attr->get( 'time' ) );
		//var_dump( $attr->get( 'number', 'pi_number_556877' ) );
	//	var_dump( $attr->get( 'number', 'price_23' ) );
		//print_r( $field->get() );
//		var_dump( $field->add( 'test', 'My name is', false ) );
	//	var_dump( $meta->get( 'datetime', 'datetime_499955' ) );
		//print_r( Grafema\Slug::get( 'random-title-with-981530' ) );
//		echo "Time:  " . number_format(( microtime(true) - $startTime), 5) . " Seconds\n";
//		echo '</pre>';

		/**
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 2025.1
		 */
		Api::create( '/api', sprintf( '%sapi', GRFM_DASHBOARD ) );

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
				View::redirect( Url::install() );
				exit;
			}

			/**
			 * Out from dashboard if user is not logged.
			 * Also leave access for the registration and password recovery pages.
			 *
			 * @since 2025.1
			 */
			if ( ! in_array( $slug, ['sign-in', 'sign-up', 'reset-password'], true ) && ! User::logged() && Is::installed() ) {
				View::redirect( Url::sign_in() );
				exit;
			}

			/**
			 * Not allow some slugs for logged user, they are reserved.
			 *
			 * @since 2025.1
			 */
			$black_list_slugs = ['install', 'sign-in', 'sign-up', 'reset-password'];
			if ( in_array( $slug, $black_list_slugs, true ) && User::logged() ) {
				View::redirect( Url::site( 'dashboard' ) );
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
			http_response_code( 200 );
			header( 'Content-Type: text/html; charset=utf-8' );
//			$content = ( new Html() )->beautify(
//				View::get(
//					GRFM_DASHBOARD . 'index',
//					[
//						'route' => $route,
//						'slug'  => $slug,
//					]
//				)
//			);
			$content = View::get(
				GRFM_DASHBOARD . 'index',
				[
					'route' => $route,
					'slug'  => $slug,
				]
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
<script type="text/javascript">
    new Promise(resolve => {
        function userActionHandler() {
            resolve();

            document.removeEventListener('mousemove', userActionHandler);
            document.removeEventListener('touchstart', userActionHandler);
            document.removeEventListener('scroll', userActionHandler);
        }

        document.addEventListener('mousemove', userActionHandler);
        document.addEventListener('touchstart', userActionHandler);
        document.addEventListener('scroll', userActionHandler);
    }).then(function() {
        (function(m, e, t, r, i, k, a) {
            m[i] = m[i] || function() {
                (m[i].a = m[i].a || []).push(arguments)
            };
            m[i].l = 1 * new Date();
            for (var j = 0; j < document.scripts.length; j++) {
                if (document.scripts[j].src === r) {
                    return;
                }
            }
            k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
        })(window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

        ym(45044579, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true
        });
    });
</script>
</body>
</html>
			<?php
printf( '%dQ %s %s', Db::queries(), Debug::timer( 'getall' ), Debug::memory_peak() );
		});
	});

	$route->run();
} catch ( Error $e ) {
	echo Debug::print( $e );
}

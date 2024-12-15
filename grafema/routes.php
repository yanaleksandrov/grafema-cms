<?php
use Grafema\Plugins;
use Grafema\Themes;
use Grafema\Option;
use Grafema\Route;
use Grafema\Debug;
use Grafema\Slug;
use Grafema\Post;
use Grafema\Hook;
use Grafema\View;
use Grafema\User;
use Grafema\I18n;
use Grafema\Url;
use Grafema\Dir;
use Grafema\Is;
use Grafema\Db;

try {
	/**
	 * Triggered before initializing the route.
	 *
	 * @since 2025.1
	 */
	Hook::apply( 'grafema_route_init' );

	/**
	 * Load private administrative panel.
	 *
	 * TODO: The dashboard must to be connected only if the current user is logged in & Is::ajax query.
	 * @since 2025.1
	 */
	$dashboardSlug  = trim( str_replace( GRFM_PATH, '/', GRFM_DASHBOARD ), '/' );
	$dashboardRoute = sprintf( '/%s/{slug}', $dashboardSlug );

	Route::any( $dashboardRoute, function( $slug ) use ( $dashboardSlug ) {
		$query = new Grafema\Query();

		$query->set( 'slug', sprintf( '%s/%s', $dashboardSlug, $slug ) );
		$query->set( match ( $slug ) {
			'sign-in'        => 'isSignIn',
			'sign-up'        => 'isSignUp',
			'reset-password' => 'isResetPassword',
			default          => 'isDashboard',
		}, true );

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
		 * The administrative panel also has a single entry point.
		 *
		 * @since 2025.1
		 */
		$content = View::get(
			'index',
			[
				'slug' => $slug,
			]
		);

		/**
		 * Grafema dashboard is fully loaded.
		 *
		 * @param string $content Current page content.
		 * @param string $slug    Current page slug.
		 *
		 * @since 2025.1
		 */
		echo Hook::apply( 'grafema_dashboard_loaded', $content, $slug );
	});

	/**
	 * None dashboard pages: website frontend output.
	 *
	 * @param string $slug Current page slug.
	 *
	 * @since 2025.1
	 */
	Route::get('/{slug}', function( $slug ) {
		$query = new Grafema\Query();

		$slug = Slug::get( $slug );

		$entityId    = $slug['entity_id'] ?? 0;
		$entityTable = $slug['entity_table'] ?? '';
		if ( ! $slug && ( ! $entityId || ! $entityTable ) ) {
			Route::trigger404();
		}

		$entity = Post::get( $entityTable, $entityId );
		echo '<pre>';
		var_dump( $entityTable );
		print_r( $slug );
		print_r( $entity );
		var_dump(\Grafema\Error::get());
		echo '</pre>';

		if ( empty( $slug ) ) {
			$query->set( 'isHome', true );
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

//	Route::set404( function() {
//		header( 'HTTP/1.1 404 Not Found' );
//
//		echo 'Page not found 404';
//	} );

	/**
	 * Launch routing.
	 *
	 * @since 2025.1
	 */
	Route::run();

} catch ( \Error|\Exception $e ) {

	/**
	 * Refine the errors output.
	 *
	 * @since 2025.1
	 */
	View::print( GRFM_DASHBOARD . 'errors', Debug::parse( $e ) );
	exit;
}

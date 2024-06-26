<?php
namespace Dashboard;

use Grafema\Hook;
use Grafema\Route;
use Grafema\Asset;
use Grafema\View;
use Grafema\Url;
use Grafema\Html;
use Grafema\Api;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @package Grafema
 */
final class Install extends \Grafema\App\App {

	use Singleton;

	/**
	 * Class constructor
	 *
	 * @return void|bool
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * Define declare the necessary constants
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_INSTALL', true );

		/**
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 1.0.0
		 */
		Api::create( sprintf( '%sdashboard/api', GRFM_PATH ), '/api' );

		/**
		 * Register new routes
		 *
		 * @since 1.0.0
		 */
		$this->route();
	}

	/**
	 * Add router
	 *
	 * @since 1.0.0
	 */
	private function route(): void {
		$route = new Route();
		$route->get(
			'(.*)',
			function( $slug ) use ( $route ) {
				http_response_code( 200 );

				/**
				 * Redirect to installer wizard if Grafema is not installed.
				 *
				 * @since 1.0.0
				 */
				if ( $slug !== 'install' ) {
					View::redirect( Url::site( 'install' ) );
					exit;
				}

				/**
				 * Run the installer wizard.
				 *
				 * @since 1.0.0
				 */
				$styles = [ 'main', 'phosphor' ];
				foreach ( $styles as $style ) {
					Asset::enqueue( $style, Url::site( 'dashboard/assets/css/' . $style . '.css' ) );
				}

				$scripts = [ 'index', 'ajax', 'alpine.min' ];
				foreach ( $scripts as $script ) {
					$data = [];
					if ( $script === 'index' ) {
						$data = [
							'data' => [
								'apiurl' => 'https://cms.codyshop.ru/api/',
							],
						];
					}
					Asset::enqueue( $script, Url::site( 'dashboard/assets/js/' . $script . '.js' ), $data );
				}

				/**
				 * Include assets before calling hooks, but after they are registered.
				 *
				 * @since 1.0.0
				 */
				Hook::add( 'grafema_dashboard_header', fn () => Asset::plug( '*.css' ) );
				Hook::add( 'grafema_dashboard_footer', fn () => Asset::plug( '*.js' ) );

				/**
				 * The administrative panel also has a single entry point.
				 *
				 * @since 1.0.0
				 */
				echo ( new Html() )->beautify( View::get( GRFM_PATH . 'dashboard/install' ) );

				die();
			}
		);

		$route->run( fn() => die() );
	}
}

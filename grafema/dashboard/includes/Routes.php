<?php
use Grafema\Route;
use Grafema\Json;
use Grafema\Debug;
use Grafema\I18n;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @package Grafema
 */
final class Routes {

	use Singleton;

	/**
	 * Class constructor
	 */
	public function __construct() {
		$route = new Route();
		$route->mount(
			'/api',
			function() use ( $route ) {
				$route->before(
					'GET|POST|DELETE',
					'/.*',
					function() {
						header( 'Content-Type: application/json' );
						if ( empty( $_REQUEST['nonce'] ) || ! is_string( $_REQUEST['nonce'] ) ) {
							echo Json::encode(
								[
									'status'    => 200,
									'benchmark' => Debug::timer( 'getall' ),
									'error'     => I18n::__( 'Ajax queries not allows without nonce' ),
								]
							);
							die();
						}
					}
				);
			}
		);
		$route->run(
			function() {
				die();
			}
		);
	}
}

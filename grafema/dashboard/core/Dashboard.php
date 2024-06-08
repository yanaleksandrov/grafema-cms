<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Dashboard;

use Grafema\Db;
use Grafema\Dir;
use Grafema\Api;
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Post\Type;
use Grafema\Debug;
use Grafema\Users\Roles;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @since 1.0.0
 */
class Dashboard extends \Grafema\App\App
{
	use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct()
	{

		/**
		 * Now the code is exclusively for the administrative panel.
		 * Define a constants.
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_DASHBOARD', true );

		/**
		 * Include CSS styles & JS scripts.
		 *
		 * @since 1.0.0
		 */
		$styles = ['phosphor', 'colorist', 'datepicker', 'drooltip', 'flags', 'prism', 'slimselect', 'main'];
		foreach ( $styles as $style ) {
			Asset::enqueue( $style, '/dashboard/assets/css/' . $style . '.css', [], GRFM_VERSION );
		}

		$scripts = ['index', 'ajax', 'slimselect', 'drooltip', 'alpine.min', 'dragula.min', 'croppr.min', 'prism.min'];
		foreach ( $scripts as $script ) {
			$data = [];
			if ( $script === 'index' ) {
				$data['data'] = [
					// TODO: move to a later
					'query'    => sprintf( '%s %s %sQ', Debug::timer( 'getall' ), Debug::memory_peak(), Db::queries() ),
					'apiurl'   => 'https://cms.codyshop.ru/api/',
					'posts'    => '',
					'showMenu' => false,
				];
			}

			if ( $script === 'index' ) {
				// $data['dependencies'] = [ 'dragula-min-js' ];
			}
			Asset::enqueue( $script, '/dashboard/assets/js/' . $script . '.js', $data );
		}

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Hook::add( 'grafema_dashboard_header', fn () => Asset::plug( '*.css' ) );
		Hook::add( 'grafema_dashboard_footer', fn () => Asset::plug( '*.js' ) );

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 1.0.0
		 */
		Menu::init();

		/**
		 * Register new forms
		 *
		 * @since 1.0.0
		 */
		$forms = (new Dir\Dir( GRFM_DASHBOARD . 'forms' ))->getFiles( '*.php' );
		foreach ( $forms as $form ) {
			require_once $form;
		}
		//require_once GRFM_DASHBOARD . 'forms/grafema-user-profile.php';
		//require_once GRFM_DASHBOARD . 'forms/grafema-user-sign-in.php';
	}
}

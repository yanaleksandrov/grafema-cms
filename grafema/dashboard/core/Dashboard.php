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
use Grafema\Is;
use Grafema\Dir;
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Debug;
use Grafema\Patterns\Singleton;
use Grafema\Url;

/**
 *
 *
 * @since 2025.1
 */
class Dashboard extends \Grafema\App\App
{
	use Singleton;

	/**
	 * Class constructor.
	 *
	 * @since 2025.1
	 */
	public function __construct()
	{

		/**
		 * Now the code is exclusively for the administrative panel.
		 * Define a constants.
		 *
		 * @since 2025.1
		 */
		$this->define( 'GRFM_IS_DASHBOARD', true );

		/**
		 * Include CSS styles & JS scripts.
		 *
		 * @since 2025.1
		 */
		$styles  = ['phosphor'];
		$scripts = ['index', 'ajax', 'alpine'];
		if ( ! Is::install() ) {
			$styles  = ['phosphor', 'colorist', 'datepicker', 'drooltip', 'flags', 'prism', 'slimselect', 'main'];
			$scripts = ['grafema', 'ajax', 'slimselect', 'drooltip', 'alpine', 'dragula', 'croppr', 'prism'];
		}

		foreach ( $styles as $style ) {
			if ( ! Is::debug() ) {
				$style = sprintf( '%s.min', $style );
			}
			Asset::enqueue( $style, Url::dashboard( '/assets/css/' . $style . '.css' ) );
		}

		foreach ( $scripts as $script ) {
			$data = [];
			if ( $script === 'grafema' ) {
				$data['data'] = [
					// TODO: move to a later
					'query'    => I18n::_f( '%s %s %sQ', Debug::timer( 'getall' ), Debug::memory_peak(), Db::queries() ),
					'apiurl'   => 'https://cms.codyshop.ru/api/',
					'posts'    => '',
					'showMenu' => false,
				];
			}

			if ( ! Is::debug() ) {
				$script = sprintf( '%s.min', $script );
			}
			Asset::enqueue( $script, Url::dashboard( '/assets/js/' . $script . '.js' ), $data );
		}

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 2025.1
		 */
		Hook::add( 'grafema_dashboard_header', fn () => Asset::render( '*.css' ) );
		Hook::add( 'grafema_dashboard_footer', fn () => Asset::render( '*.js' ) );

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 2025.1
		 */
		Menu::init();

		/**
		 * Register new forms
		 *
		 * @since 2025.1
		 */
		$forms = (new Dir\Dir( GRFM_DASHBOARD . 'forms' ))->getFiles( '*.php' );
		foreach ( $forms as $form ) {
			require_once $form;
		}
		require_once GRFM_DASHBOARD . 'forms/grafema-user-profile.php';
		require_once GRFM_DASHBOARD . 'forms/grafema-user-sign-in.php';
	}
}

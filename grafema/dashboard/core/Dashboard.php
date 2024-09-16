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
use Grafema\Asset;
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Debug;
use Grafema\Url;

/**
 *
 *
 * @since 2025.1
 */
(new class extends \Grafema\App\App {

	public function __construct() {

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
		if ( ! Is::install() ) {
			$styles  = ['phosphor', 'air-datepicker', 'colorist', 'datepicker', 'drooltip', 'flags', 'slimselect', 'dialog', 'grafema', 'controls', 'utility', 'notifications', 'nav-editor'];
		}
		foreach ( $styles as $style ) {
			if ( ! Is::debug() ) {
				$style = sprintf( '%s.min', $style );
			}
			Asset::enqueue( $style, Url::dashboard( '/assets/css/' . $style . '.css' ) );
		}

		Hook::add( 'grafema_dashboard_footer', function() {
			$scripts = ['index', 'ajax', 'alpine'];
			if ( ! Is::install() ) {
				$scripts = ['grafema', 'air-datepicker', 'ajax', 'datepicker', 'slimselect', 'drooltip', 'dragula', 'croppr', 'dialog', 'storage', 'notifications', 'alpine', 'sortable'];
			}

			foreach ( $scripts as $script ) {
				$data = [];
				if ( $script === 'grafema' ) {
					$data['data'] = Hook::apply(
						'grafema_dashboard_data',
						[
							'apiurl'         => Url::site( '/api/' ),
							'items'          => [],
							'lang'           => I18n::locale(),
							'dateFormat'     => 'j M, Y',
							'weekStart'      => 1,
							'showMenu'       => false,
							'showFilter'     => false,
							'notifications'  => [
								'ctrlS' => I18n::_t_attr( 'Grafema saves the changes automatically, so there is no need to press ⌘ + S' ),
							],
							'uploaderDialog' => [
								'title' => I18n::_t( 'Upload Files' ),
								'class' => 'dialog--md',
							],
							'emailDialog' => [
								'title' => I18n::_t( 'Email Settings' ),
								'class' => 'dialog--xl dialog--right',
							],
							'postEditorDialog' => [
								'title' => I18n::_t( 'Post Editor' ),
								'class' => 'dialog--lg dialog--right',
							],
							'takeSelfieDialog' => [
								'title' => I18n::_t( 'Take A Selfie' ),
								'class' => 'dialog--sm',
							],
						]
					);
				}

				if ( ! Is::debug() ) {
					$script = sprintf( '%s.min', $script );
				}
				Asset::enqueue( $script, Url::dashboard( '/assets/js/' . $script . '.js' ), $data );
			}
		}, 10 );

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 2025.1
		 */
		Hook::add( 'grafema_dashboard_header', fn () => Asset::render( '*.css' ), 5 );
		Hook::add( 'grafema_dashboard_footer', fn () => Asset::render( '*.js' ), 20 );

		/**
		 * Add benchmark result.
		 *
		 * @since 2025.1
		 */
		Hook::add( 'grafema_dashboard_loaded', function( $content ) {
			return str_replace(
				'0Q 0.001s 999kb',
				I18n::_f( ':queries\Q :memory :memory_peak', Db::queries(), Debug::timer( 'getall' ), Debug::memory_peak() ),
				$content
			);
		} );

		/**
		 * Include assets before calling hooks, but after they are registered.
		 *
		 * @since 2025.1
		 */
		Menu::init();
	}
});
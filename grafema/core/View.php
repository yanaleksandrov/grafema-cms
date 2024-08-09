<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Grafema;

/**
 * Template class.
 *
 * @since 2025.1
 */
class View
{
	/**
	 * @param $location
	 * @param int $status
	 * @param string $x_redirect_by
	 * @return bool|Errors
	 */
	public static function redirect( $location, $status = 302, $x_redirect_by = 'Grafema' )
	{
		/**
		 * Filters the redirect location.
		 *
		 * @since 2.1.0
		 *
		 * @param string $location the path or URL to redirect to
		 * @param int    $status   the HTTP response status code to use
		 */
		$location = Hook::apply( 'grafema_redirect', $location, $status );

		/**
		 * Filters the redirect HTTP response status code to use.
		 *
		 * @since 2.3.0
		 *
		 * @param int    $status   the HTTP response status code to use
		 * @param string $location the path or URL to redirect to
		 */
		$status = Hook::apply( 'grafema_redirect_status', $status, $location );

		if ( ! $location ) {
			return false;
		}

		if ( $status < 300 || 399 < $status ) {
			return new Errors( 'view-redirect', I18n::__( 'HTTP redirect status code must be a redirection code, 3xx.' ) );
		}

		/**
		 * Filters the X-Redirect-By header.
		 *
		 * Allows applications to identify themselves when they're doing a redirect.
		 *
		 * @since 5.1.0
		 *
		 * @param string $x_redirect_by the application doing the redirect
		 * @param int    $status        status code to use
		 * @param string $location      the path to redirect to
		 */
		$x_redirect_by = Hook::apply( 'x_redirect_by', $x_redirect_by, $status, $location );
		if ( is_string( $x_redirect_by ) ) {
			header( "X-Redirect-By: {$x_redirect_by}" );
		}

		header( "Location: {$location}", true, $status );

		return true;
	}

	/**
	 * Loads a template part into a template.
	 *
	 * Using: View::print( 'templates/content', [
	 *          'key' => 'value'
	 *        ] );.
	 *
	 * Provides a simple mechanism to overload reusable sections of code.
	 * The template is included using require, not require_once, so you
	 * may include the same template part multiple times.
	 *
	 * TODO: add object cache
	 * TODO: include 404 error part if nothing found (possible no needs)
	 * TODO: potential problem, - if the same file override several different plugins, which one should I use?
	 *
	 * @since 2025.1
	 *
	 * @param string $template The path to the view file.
	 * @param array  $args     Optional. Additional arguments passed to the template. Default empty array.
	 *
	 * @since 2025.1
	 */
	public static function print( string $template, array $args = [] )
	{
		$filepath = sprintf( '%s.php', $template );
		$filepath = match (true) {
			file_exists( $filepath )         => $filepath,
			Is::dashboard() || Is::install() => sprintf('%sdashboard/%s.php', GRFM_PATH, $template),
			default                          => GRFM_THEMES . ( $theme_domain ?? Option::get( 'theme' ) ) . $template,
		};

		$filepath = Sanitizer::path( $filepath );
		if ( file_exists( $filepath ) ) {

			/**
			 * Override template file.
			 *
			 * @since 2025.1
			 *
			 * @param string $filepath Path to existing template part.
			 */
			$filepath = Hook::apply( 'grafema_view_part', $filepath, $template, $args );

			extract( $args, EXTR_SKIP );

			require $filepath;
		}
	}

	/**
	 * Get template part.
	 *
	 * @param string $template
	 * @param array $args
	 * @return string
	 */
	public static function get( string $template, array $args = [] ): string {
		ob_start();
		self::print( $template, $args );
		return ob_get_clean();
	}
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
spl_autoload_register(
	function ( $class ) {
		$filepath = sprintf( '%s%s.php', GRFM_PATH, $class );

		// TODO: is so bad, fix it!
		$filepath = str_replace(
			['\\', '/Grafema/', '/Dashboard/'],
			['/', '/core/', '/dashboard/core/'],
			$filepath
		);

		if ( $class === 'Grafema2025' ) {
			echo '<pre>';
			var_dump( $class );
			var_dump( $filepath );
			echo '</pre>';
		}

		if ( file_exists( $filepath ) ) {
			require_once $filepath;
		}
	}
);

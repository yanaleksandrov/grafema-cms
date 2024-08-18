<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/pages.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

?>
<div class="grafema-main">
	<?php
	View::print(
		'templates/table/header',
		[
			'title' => I18n::__( 'Pages' ),
		]
	);

    ( new Dashboard\Tables\Pages() )->render();

	View::print( 'templates/modals/post' );
    ?>
</div>

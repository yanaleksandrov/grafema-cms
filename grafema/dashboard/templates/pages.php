<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/pages.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

?>
<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Pages' ),
		]
	);

    ( new Dashboard\Tables\Pages() )->render();

	View::part( 'templates/modals/post' );
    ?>
</div>

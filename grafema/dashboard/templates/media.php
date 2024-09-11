<?php
use Grafema\Hook;
use Grafema\View;

/**
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/media.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Hook::add( 'grafema_dashboard_footer', function() {
	View::print( 'templates/dialogs/media-editor' );
	View::print( 'templates/dialogs/media-uploader' );
} );
?>
<div class="grafema-main">
	<?php ( new Dashboard\Table( new Dashboard\MediaTable() ) )->print(); ?>
    <div x-intersect="$ajax('media/get').then(response => items = response.posts)"></div>
</div>

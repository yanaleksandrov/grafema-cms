<?php
use Dashboard\Form;
use Grafema\I18n;

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
?>
<div class="grafema-main">
	<?php ( new Dashboard\Table( new Dashboard\MediaTable() ) )->print(); ?>
    <div x-intersect="$ajax('media/get').then(response => items = response.posts)"></div>
	<?php Form::print( 'grafema-media-editor', GRFM_DASHBOARD . 'forms/grafema-media-editor.php' ); ?>
</div>

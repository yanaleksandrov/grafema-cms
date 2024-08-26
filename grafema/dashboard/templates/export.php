<?php
/**
 * Export website content page
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/export.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main p-7 bg-gray-lt">
	<div class="mw-600 m-auto">
		<?php echo Dashboard\Form::view( 'grafema-posts-export', path: GRFM_DASHBOARD . 'forms/grafema-posts-export.php' ); ?>
	</div>
</div>

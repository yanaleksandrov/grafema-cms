<?php
/*
 * Export website content page
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/export.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main p-7 bg-gray-lt">
	<div class="mw-600 m-auto">
		<?php echo Dashboard\Form::view( 'grafema-posts-export' ); ?>
	</div>
</div>

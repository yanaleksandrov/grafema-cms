<?php
/*
 * Import posts from CSV file.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/import.php
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
		<?php echo Dashboard\Form::view( 'posts/import', path: GRFM_DASHBOARD . 'forms/grafema-posts-import.php' ); ?>
	</div>
</div>

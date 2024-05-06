<?php
/*
 * Import posts from CSV file.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/import.php
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
		<?php echo Dashboard\Form::view( 'posts/import' ); ?>
	</div>
</div>

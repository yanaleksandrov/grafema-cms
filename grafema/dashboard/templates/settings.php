<?php
/**
 * Grafema dashboard settings page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/settings.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
    <!-- tabs start -->
	<?php echo Dashboard\Form::view( 'grafema-settings', path: GRFM_DASHBOARD . 'forms/grafema-settings.php' ); ?>
    <!-- tabs end -->
</div>

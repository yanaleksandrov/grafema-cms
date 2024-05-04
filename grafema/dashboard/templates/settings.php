<?php
/**
 * Grafema dashboard settings page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/settings.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
    <!-- tabs start -->
	<?php echo Form::view( 'jb-settings' ); ?>
    <!-- tabs end -->
</div>

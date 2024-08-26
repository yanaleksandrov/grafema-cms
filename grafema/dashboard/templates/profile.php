<?php
/**
 * User profile page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/profile.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php echo Dashboard\Form::view( 'grafema-user-profile', path: GRFM_DASHBOARD . 'forms/grafema-user-profile.php' ); ?>
</div>

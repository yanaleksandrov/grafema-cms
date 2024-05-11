<?php
/*
 * User profile page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/profile.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php echo Dashboard\Form::view( 'grafema-user-profile' ); ?>
</div>

<?php
use Grafema\I18n;
use Grafema\Url;

/*
 * User sing up page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/sign-up.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-360">
	<div class="df jcc">
        <img src="/dashboard/assets/images/logo-decorate.svg" width="240" height="140" alt="Grafema CMS">
	</div>
	<?php echo Dashboard\Form::view( 'grafema-user-sign-up' ); ?>
	<div class="fs-14 t-center t-muted mt-3">
		<?php
		I18n::tf(
			'Already have an account? %sSign in%s',
			'<a href="' . Url::site( 'dashboard/sign-in' ) . '">',
			'</a>'
		);
		?>
	</div>
</div>

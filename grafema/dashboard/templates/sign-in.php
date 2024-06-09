<?php
use Grafema\I18n;
use Grafema\Url;

/*
 * User sing in page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/sign-in.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-360">
	<div class="df jcc mb-4">
        <img src="/dashboard/assets/images/logo-decorate.svg" width="240" height="140" alt="Grafema CMS">
	</div>
	<?php echo Dashboard\Form::view( 'grafema-user-sign-in' ); ?>
	<div class="fs-14 t-center t-muted mt-3">
		<?php
		I18n::tf(
		    'Don\'t have an account yet? %sSign up%s',
			'<a href="' . Url::site( 'dashboard/sign-up' ) . '">',
			'</a>'
        );
        ?>
	</div>
</div>

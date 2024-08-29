<?php
use Grafema\I18n;
use Grafema\Url;

/**
 * Reset user password form.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/reset-password.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-360">
	<a href="<?php echo Url::site(); ?>" class="df jcc mb-4" target="_blank">
        <img src="<?php echo Url::site( 'dashboard/assets/images/logo-decorate.svg' ); ?>" width="212" height="124" alt="Grafema CMS">
	</a>
	<?php echo Dashboard\Form::view( 'grafema-user-reset-password', path: GRFM_DASHBOARD . 'forms/grafema-user-reset-password.php' ); ?>
	<div class="t-center t-muted mt-3">
		<?php
		I18n::tf(
			'I remembered the password, %ssend%s me to the sign in page',
			'<a href="' . Url::site( 'dashboard/sign-in' ) . '">',
			'</a>'
		);
        ?>
	</div>
</div>

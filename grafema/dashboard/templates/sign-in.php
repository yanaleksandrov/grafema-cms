<?php
use Grafema\I18n;
use Grafema\Url;

/**
 * User sing in page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/sign-in.php
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
	<?php Dashboard\Form::print( 'grafema-user-sign-in', path: GRFM_DASHBOARD . 'forms/grafema-user-sign-in.php' ); ?>
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

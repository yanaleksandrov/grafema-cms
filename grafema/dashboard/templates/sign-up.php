<?php
use Grafema\I18n;
use Grafema\Url;

/**
 * User sign up page.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/sign-up.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-360">
	<div class="df jcc mb-4">
        <img src="<?php echo Url::site( '/dashboard/assets/images/logo-decorate.svg' ); ?>" width="200" height="117" alt="Grafema CMS">
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

<?php
/**
 * Reset user password form.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/reset-password.php
 *
 * @version     1.0.0
 */

use Grafema\I18n;
use Grafema\Url;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="mw-360">
	<div class="df jcc">
        <img src="/dashboard/assets/images/logo-decorate.svg" width="240" height="140" alt="Grafema CMS">
	</div>
	<?php echo Dashboard\Form::view( 'grafema-user-reset-password' ); ?>
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

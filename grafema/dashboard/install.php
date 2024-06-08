<?php
use Grafema\Hook;
use Grafema\I18n;

/*
 * Grafema install wizard.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/install.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo locale_get_default(); ?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php I18n::t( 'Install Grafema' ); ?></title>
        <link rel="icon" href="/dashboard/assets/favicon.ico">
		<link rel="icon" href="/dashboard/assets/favicon.svg" type="image/svg+xml">
		<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/assets/favicon/apple-touch-icon.png">
		<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/assets/favicon/favicon-32x32.png">
		<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/assets/favicon/favicon-16x16.png">
		<?php
		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_dashboard_header' );
		?>
	</head>
	<body class="df jcc p-8">
        <div class="mw-360">
            <div class="mb-8 df jcc">
                <img src="/dashboard/assets/images/logo-decorate.svg" width="240" height="140" alt="Grafema CMS">
            </div>
            <?php echo Dashboard\Form::view( 'system-install' ); ?>
        </div>
		<?php
		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 1.0.0
		 */
		Hook::apply( 'grafema_dashboard_footer' );
		?>
	</body>
</html>

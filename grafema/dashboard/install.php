<?php
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Url;

/*
 * Grafema install wizard.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/install.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<!DOCTYPE html>
<html lang="<?php echo I18n::locale(); ?>">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php I18n::t( 'Install Grafema' ); ?></title>
        <link rel="icon" href="<?php echo Url::site( '/dashboard/assets/favicon.ico' ); ?>">
		<link rel="icon" href="<?php echo Url::site( '/dashboard/assets/favicon.svg' ); ?>" type="image/svg+xml">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php echo Url::site( '/dashboard/assets/favicon/apple-touch-icon.png' ); ?>">
		<link rel="icon" type="image/png" sizes="32x32" href="<?php echo Url::site( '/dashboard/assets/favicon/favicon-32x32.png' ); ?>">
		<link rel="icon" type="image/png" sizes="16x16" href="<?php echo Url::site( '/dashboard/assets/favicon/favicon-16x16.png' ); ?>">
		<?php
		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 2025.1
		 */
		Hook::apply( 'grafema_dashboard_header' );
		?>
	</head>
	<body class="df jcc p-6" x-data="grafema">
        <div class="mw-400">
            <div class="df jcc">
                <img src="<?php echo Url::site( '/dashboard/assets/images/logo-decorate.svg' ); ?>" width="200" height="117" alt="Grafema CMS">
            </div>
            <?php Dashboard\Form::print( GRFM_DASHBOARD . 'forms/grafema-system-install.php' ); ?>
        </div>
		<?php
		/**
		 * Prints scripts or data before the closing body tag on the dashboard.
		 *
		 * @since 2025.1
		 */
		Hook::apply( 'grafema_dashboard_footer' );
		?>
	</body>
</html>

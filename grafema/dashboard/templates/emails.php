<?php
use Grafema\View;
use Grafema\Hook;

/**
 * Emails.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/emails.php
 *
 * @version 2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

Hook::add( 'grafema_dashboard_footer', function() {
	View::print( 'templates/dialogs/emails-editor' );
} );
?>
<div class="grafema-main">
	<?php ( new Dashboard\Table( new Dashboard\EmailsTable() ) )->print(); ?>
</div>

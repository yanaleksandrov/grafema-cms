<?php
use Grafema\View;

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
?>
<div class="grafema-main">
	<?php
	( new Dashboard\Table( new Dashboard\EmailsTable() ) )->print();

	View::print( 'templates/modals/email' );
	?>
</div>

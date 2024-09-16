<?php
use Dashboard\Form;

/**
 * Terms editor.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/terms.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<div class="terms">
		<div class="terms-side">
			<?php Form::print( GRFM_DASHBOARD . 'forms/grafema-terms-editor.php' ); ?>
		</div>
		<div class="terms-main">
			<?php ( new Dashboard\Table( new Dashboard\TermsTable() ) )->print(); ?>
		</div>
	</div>
</div>

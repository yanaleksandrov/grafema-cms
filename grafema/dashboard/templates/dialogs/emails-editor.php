<?php
use Grafema\View;

/**
 * Template for output emails editor.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/dialogs/emails-editor.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>

<!-- email editor template start -->
<template id="tmpl-email-editor" x-init="$dialog.init(() => emailDialog)">
	<div class="email">
		<div class="email-form">
			<?php Dashboard\Form::print( GRFM_DASHBOARD . 'forms/grafema-emails-creator.php' ); ?>
		</div>
		<div class="email-preview">
			<?php
			View::print(
				GRFM_DASHBOARD . 'templates/mails/wrappers',
				[
					'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password',
				]
			);
			?>
		</div>
	</div>
</template>

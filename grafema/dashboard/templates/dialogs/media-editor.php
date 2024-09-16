<?php
/**
 * Template for output media editor.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/dialogs/media-editor.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>

<!-- media editor template start -->
<template id="tmpl-media-editor" x-init="$dialog.init(() => $ajax('media/get'))">
	<?php Dashboard\Form::print( GRFM_DASHBOARD . 'forms/grafema-media-editor.php' ); ?>
</template>

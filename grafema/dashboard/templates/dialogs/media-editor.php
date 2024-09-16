<?php
use Grafema\I18n;

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
	<div class="media-editor">
		<div class="media-editor-main">
			<img class="media-editor-image" :src="$store.dialog.url" :alt="$store.dialog.filename" :width="$store.dialog.width" :height="$store.dialog.height">
		</div>
		<div class="media-editor-side">
			<div class="dg g-1 fs-12">
				<div><strong><?php I18n::t( 'Uploaded on' ); ?>:</strong> <span x-text="$store.dialog.created"></span></div>
				<div><strong><?php I18n::t( 'Uploaded by' ); ?>:</strong> <span x-text="$store.dialog.author"></span></div>
				<div><strong><?php I18n::t( 'File name' ); ?>:</strong> <span x-text="$store.dialog.filename"></span></div>
				<div><strong><?php I18n::t( 'File type' ); ?>:</strong> <span x-text="$store.dialog.mime"></span></div>
				<div><strong><?php I18n::t( 'File size' ); ?>:</strong> <span x-text="$store.dialog.sizeHumanize"></span></div>
				<div><strong><?php I18n::t( 'Length' ); ?>:</strong> 2 minutes, 48 seconds</div>
			</div>
			<?php Dashboard\Form::print( GRFM_DASHBOARD . 'forms/grafema-media-editor.php' ); ?>
		</div>
	</div>
</template>

<?php
use Dashboard\Form;
use Grafema\I18n;

/**
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/media.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php ( new Dashboard\Table( new Dashboard\MediaTable() ) )->print(); ?>
    <div x-intersect="$ajax('media/get').then(response => items = response.posts)"></div>
	<div class="modal" id="grafema-modals-media-editor" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
		<div class="modal__dialog modal__dialog--xl" role="document">
			<div class="modal__content" @click.outside="$modal.close()">
				<div class="modal__header">
					<h6 class="modal__title"><?php I18n::t( 'Media details' ); ?></h6>
					<button type="button" class="modal__close" @click="$modal.close()"></button>
				</div>
				<?php Form::print( 'grafema-media-editor', GRFM_DASHBOARD . 'forms/grafema-media-editor.php' ); ?>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Popup for upload new files to storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/modals/post.php
 *
 * @package     Grafema\Templates
 * @version     2025.1
 */
use Dashboard\Form;
use Grafema\I18n;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="modal" id="grafema-modals-uploader" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
    <div class="modal__dialog" role="document">
        <div class="modal__content" @click.outside="$modal.close()">
            <div class="modal__header">
                <h6 class="modal__title"><?php I18n::t( 'Upload new files' ); ?></h6>
                <button type="button" class="modal__close" @click="$modal.close()"></button>
            </div>
			<?php
			/**
			 * Files uploader modal.
			 *
			 * @since 2025.1
			 */
			Form::print( 'grafema-files-uploader' );
			?>
        </div>
    </div>
</div>

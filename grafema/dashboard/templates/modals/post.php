<?php
/**
 * Add or update posts.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/modals/post.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */

use Grafema\I18n;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="modal" id="grafema-modals-post" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
    <div class="modal__dialog modal__dialog--right" role="document">
        <div class="modal__content" @click.outside="$modal.close()">
            <div class="modal__header t-muted fw-600">
                <div class="df aic g-1 fs-12" @click="$copy('123')">
                    <i class="ph ph-share-network"></i> <?php I18n::t( 'Copy link' ); ?>
                </div>
                <div class="df aic g-1 fs-12" @click="$copy('123')">
                    <i class="ph ph-folders"></i> <?php I18n::t( 'Duplicate' ); ?>
                </div>
                <div class="df aic g-1 fs-12 t-red" @click="$copy('123')">
                    <i class="ph ph-trash"></i> <?php I18n::t( 'Move to trash' ); ?>
                </div>
                <button type="button" class="modal__close" @click="$modal.close()"></button>
            </div>
			<?php

			/**
			 * Form for create or update posts.
			 *
			 * @since 1.0.0
			 */
            echo Dashboard\Form::view( 'grafema-posts-creator' );
            ?>
        </div>
    </div>
</div>

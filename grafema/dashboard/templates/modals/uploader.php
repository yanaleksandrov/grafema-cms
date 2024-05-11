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
use Grafema\View;

if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="modal" id="grafema-modals-uploader" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
    <div class="modal__dialog" role="document">
        <div class="modal__content" @click.outside="$modal.close()">
            <div class="modal__header">
                <h6 class="modal__title"><?php I18n::e( 'Upload new files' ); ?></h6>
                <button type="button" class="modal__close" @click="$modal.close()"></button>
            </div>

            <div class="p-7 dg g-3">
				<?php
				View::part(
					'templates/form/uploader',
					[
						'instruction' => I18n::__( 'Click to upload or drag & drop' ),
						'attributes'  => [
							'@change'  => '$ajax("media/upload", $el.files, xhr => percent = xhr.percent).then(xhr => console.log(xhr.posts))',
							'multiple' => true,
						],
					]
				);
				View::part(
					'templates/form/textarea',
					[
						'label'      => I18n::__( 'Or upload from URL' ),
						'tooltip'    => I18n::__( 'Each URL must be from a new line' ),
						'attributes' => [
							'name'         => 'urls',
							'x-model.fill' => 'urls',
							'required'      => false,
							'placeholder'   => I18n::__( 'Input file URL(s)' ),
							'@change'       => '$ajax("media/grab", {urls}).then(response => files = response)',
							'x-textarea'    => 99,
						],
					]
				);
				?>
                <div class="progress" x-show="percent > 0" :style="{ '--grafema-progress': `${percent}%` }"></div>
            </div>
        </div>
    </div>
</div>

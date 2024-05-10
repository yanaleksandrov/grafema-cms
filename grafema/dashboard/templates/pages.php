<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/pages.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

?>
<div class="grafema-main">
	<?php
	View::part(
		'templates/table/header',
		[
			'title' => I18n::__( 'Pages' ),
		]
	);

    ( new Dashboard\Tables\Pages() )->render();
    ?>
</div>

<div class="modal" id="grafema-posts-creator" tabindex="-1" role="dialog" aria-hidden="true" x-cloak>
	<div class="modal__dialog modal__dialog--right" role="document">
		<div class="modal__content" @click.outside="$modal.close()">
			<div class="modal__header t-muted fw-600">
                <div class="df aic g-1 fs-12" @click="$copy('123')">
                    <i class="ph ph-share-network"></i> <?php I18n::e( 'Copy link' ); ?>
                </div>
                <div class="df aic g-1 fs-12" @click="$copy('123')">
                    <i class="ph ph-folders"></i> <?php I18n::e( 'Duplicate' ); ?>
                </div>
                <div class="df aic g-1 fs-12" @click="$copy('123')">
                    <i class="ph ph-trash"></i> <?php I18n::e( 'Delete' ); ?>
                </div>
				<button type="button" class="modal__close" @click="$modal.close()"></button>
			</div>
			<div class="modal__body bg-white p-0">
				<?php echo Dashboard\Form::view( 'grafema-posts-creator' ); ?>
			</div>
			<div class="modal__footer bg-white">
				<button type="button" class="btn btn--outline" @click="$modal.close()">Cancel</button>
				<button type="button" class="btn btn--primary" @click="$modal.close()">Publish</button>
			</div>
		</div>
	</div>
</div>

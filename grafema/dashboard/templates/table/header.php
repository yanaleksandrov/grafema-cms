<?php
use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

/**
 * Table header
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/header.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $title, $badge, $show, $content ] = ( new Sanitizer(
	$args ?? [],
	[
		'title'   => 'trim',
		'badge'   => 'trim',
		'show'    => 'bool:true',
		'content' => 'trim',
	]
) )->values();

$show = true;
?>
<!-- table head start -->
<div class="table__header">
    <div class="mw df aic jcsb g-4 px-7 py-5">
		<?php if ( $title ) : ?>
            <h4>
	            <?php
	            echo $title;
	            if ( $badge ) {
					echo '<span class="badge">' . $badge . '</span>';
	            }
	            ?>
            </h4>
		<?php endif; ?>
        <div class="df aic g-1" x-show="!bulk">
			<?php if ( $show ) : ?>
				<?php
				View::print(
					'templates/form/details',
					[
						'label'       => I18n::_f( '%s Filters', '<i class="ph ph-funnel-simple"></i>' ),
						'instruction' => '',
						'class'       => 'btn btn--sm',
						'content'     => Dashboard\Form::view( 'grafema-posts-options', path: GRFM_DASHBOARD . 'forms/grafema-posts-options.php' ),
					]
				);
				?>
                <button class="btn btn--sm" @click="$modal.open('grafema-modals-uploader')">
                    <i class="ph ph-upload-simple"></i> <?php I18n::t( 'Add new file' ); ?>
                </button>
				<?php
				View::print(
					'templates/form/number',
					[
						'type'        => 'number',
						'name'        => 'page',
						'class'       => 'dg g-1 ml-8',
						'label'       => '',
						'description' => '',
						'attributes'  => [
							'value' => 3,
						],
					]
				);

				View::print(
					'templates/form/details',
					[
						'label'       => '<i class="ph ph-dots-three-outline-vertical"></i>',
						'instruction' => I18n::__( 'Test content' ),
						'class'       => 'btn btn--sm btn--icon',
						'content'     => Dashboard\Form::view( 'grafema-posts-options', path: GRFM_DASHBOARD . 'forms/grafema-posts-options.php' ),
					]
				);
				?>
			<?php endif; ?>
        </div>

        <div class="df aic g-1" x-show="bulk" x-cloak>
			<?php
			View::print(
				'templates/form/details',
				[
					'label'       => I18n::_f( '%s Bulk actions', '<i class="ph ph-magic-wand"></i>' ),
					'instruction' => I18n::__( 'Test content' ),
					'class'       => 'btn btn--sm',
					'content'     => Dashboard\Form::view( 'grafema-posts-actions', path: GRFM_DASHBOARD . 'forms/grafema-posts-actions.php' ),
				]
			);
			?>
            <button type="button" class="btn btn--sm t-red" x-bind="reset">
                <i class="ph ph-trash"></i> <?php I18n::t( 'Reset' ); ?>
            </button>
        </div>
    </div>
    <?php $content && print( $content ); ?>

</div>

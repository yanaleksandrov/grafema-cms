<?php
use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

/**
 * Table header
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/header.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $title, $show ] = ( new Sanitizer(
	$args ?? [],
	[
		'title' => 'trim',
		'show'  => 'bool:true',
	]
) )->values();

if ( ! $show ) {
    return;
}
?>
<!-- table head start -->
<div class="table__header">
	<div class="df aic jcsb">
		<?php if ( $title ) : ?>
			<div class="df aic g-4 mr-2">
				<h4><?php echo $title; ?></h4>
			</div>
		<?php endif; ?>
		<div class="df aic g-1">
			<?php
			View::part(
				'templates/form/number',
				[
					'name'        => 'page',
					'label'       => '',
					'description' => '',
					'attributes'  => [
						'value' => 3,
					],
				]
			);
			?>
			<a href="/dashboard/plugins-install" class="btn btn--outline">Install <span class="badge badge--sm">+2k</span></a>
			<button class="btn btn--outline"><i class="ph ph-funnel-simple"></i> Filters <span class="badge badge--sm">8</span></button>
			<?php
			View::part(
				'templates/form/details',
				[
					'label'       => '<i class="ph ph-magic-wand"></i>' . I18n::__( 'Bulk actions' ),
					'instruction' => I18n::__( 'Test content' ),
					'class'       => 'btn btn--outline',
					'content'     => Dashboard\Form::view( 'grafema-posts-actions' ),
				]
			);
			View::part(
				'templates/form/details',
				[
					'label'       => '<i class="ph ph-dots-three-outline-vertical"></i>',
					'instruction' => I18n::__( 'Test content' ),
					'class'       => 'btn btn--outline btn--icon',
					'content'     => Dashboard\Form::view( 'grafema-posts-options' ),
				]
			);
			?>
            <button class="btn btn--outline" @click="showUploader = !showUploader"><i class="ph ph-folder-simple-plus"></i> <?php I18n::e( 'Add new file' ); ?></button>
		</div>
	</div>
    <div class="dg g-3" x-show="showUploader" x-cloak>
		<?php
		View::part(
			'templates/form/uploader',
			[
				'instruction' => I18n::__( 'Click to upload or drag & drop' ),
				'attributes'  => [
					'@change'  => '$ajax("media/upload", $el.files, e => percent = e.percent).then()',
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

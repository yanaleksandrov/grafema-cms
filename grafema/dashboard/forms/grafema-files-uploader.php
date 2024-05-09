<?php
use Grafema\I18n;

/**
 * Form for build custom fields
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-files-uploader',
	[],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'    => 'computer',
					'type'    => 'tab',
					'label'   => I18n::__( 'Upload from computer' ),
					'caption' => '',
					'icon'    => 'ph ph-desktop-tower',
					'fields'  => [
						[
							'name'        => 'files',
							'type'        => 'uploader',
							'instruction' => I18n::__( 'Click to upload or drag & drop' ),
							'attributes'  => [
								'@change'  => '$ajax("media/upload", $el.files, e => percent = e.percent).then()',
								'multiple' => true,
							],
						],
						[
							'name'     => 'progress',
							'type'     => 'custom',
							'callback' => function () {
								ob_start();
								?>
								<div class="progress" x-show="percent > 0" :style="{ '--grafema-progress': `${percent}%` }"></div>
								<?php
								return ob_end_clean();
							},
						],
					],
				],
				[
					'name'    => 'external',
					'type'    => 'tab',
					'label'   => I18n::__( 'From external source' ),
					'caption' => '',
					'icon'    => 'ph ph-link',
					'fields'  => [
						[
							'name'        => 'urls',
							'type'        => 'textarea',
							'label'      => I18n::__( 'Or upload from URL' ),
							'tooltip'    => I18n::__( 'Each URL must be from a new line' ),
							'attributes' => [
								'x-model.fill' => 'urls',
								'required'      => false,
								'placeholder'   => I18n::__( 'Input file URL(s)' ),
								'@change'       => '$ajax("media/grab", {urls}).then(response => files = response)',
								'x-textarea'    => 99,
							],
						],
					],
				],
			]
		);
	}
);
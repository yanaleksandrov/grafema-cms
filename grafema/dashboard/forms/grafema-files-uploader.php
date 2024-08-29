<?php
use Grafema\I18n;

/**
 * Form for build custom fields
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-files-uploader',
	[
		'class'  => 'dg g-7 p-7',
	    'x-data' => '{percent: 0, uploader: null}'
    ],
	[
		[
			'name'        => 'files',
			'type'        => 'uploader',
			'instruction' => I18n::_t( 'Click to upload or drag & drop' ),
			'attributes'  => [
				'@change'  => '$ajax("media/upload", $el.files, e => percent = e.percent).then()',
				'multiple' => true,
			],
		],
		[
			'name'     => 'progress',
			'type'     => 'custom',
			'callback' => fn () => '<div class="progress" :style="{ \'--grafema-progress\': `${percent}%` }"></div>',
		],
		[
			'name'        => 'urls',
			'type'        => 'textarea',
			'label'      => I18n::_t( 'Or upload from URL' ),
			'tooltip'    => I18n::_t( 'Each URL must be from a new line' ),
			'attributes' => [
				'x-model.fill' => 'urls',
				'required'     => false,
				'placeholder'  => I18n::_t( 'Input file URL(s)' ),
				'@change'      => '$ajax("media/grab", {urls}).then(response => files = response)',
				'x-textarea'   => 19,
			],
		],
	]
);
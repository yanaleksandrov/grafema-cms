<?php
use Grafema\I18n;

/**
 * Form for build custom fields
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-files-uploader',
	[
		'class'  => 'dg g-7 p-7',
	    'x-data' => '{percent: 0, uploader: null}'
    ],
	[
		[
			'type'        => 'uploader',
			'name'        => 'files',
			'label'       => '',
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => I18n::_t( 'Click to upload or drag & drop' ),
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'@change'  => '$ajax("media/upload", $el.files, e => percent = e.percent).then()',
				'multiple' => true,
			],
		],
		[
			'name'     => 'progress',
			'type'     => 'custom',
			'callback' => fn () => '<div class="progress" :style="{\'--grafema-progress\':`${percent}%`}"></div>',
		],
		[
			'type'        => 'textarea',
			'name'        => 'urls',
			'label'       => I18n::_t( 'Or upload from external URL' ),
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => '',
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'placeholder' => I18n::_t( 'Each URL must be from a new line' ),
				'@change'     => '$ajax("media/grab", {urls}).then(response => files = response)',
				'x-textarea'  => 19,
			],
		],
	]
);
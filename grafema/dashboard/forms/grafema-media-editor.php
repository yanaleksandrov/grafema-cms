<?php
use Grafema\I18n;

/**
 * Media files editor.
 *
 * @since 2025.1
 */
return Dashboard\Form::enqueue(
	'grafema-media-editor',
	[
		'class' => 'dg g-3',
	],
	[
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => I18n::_t( 'Alternative Text' ),
			'class'         => 'dg g-2 gtc-3',
			'label_class'   => 'ga-1 fs-12 t-muted',
			'content_class' => 'ga-2',
			'fields'        => [
				[
					'type'        => 'text',
					'name'        => 'alt',
					'label'       => '',
					'class'       => 'field field--outline fs-13',
					'label_class' => '',
					'reset'       => 0,
					'before'      => '',
					'after'       => '',
					'instruction' => I18n::_f( '%sLearn how to describe the purpose of the image%s. Leave empty if the image is purely decorative.', '<a href="//www.w3.org/WAI/tutorials/images/decision-tree/" target="_blank">', '</a>' ),
					'tooltip'     => '',
					'copy'        => 0,
					'sanitizer'   => '',
					'validator'   => '',
					'conditions'  => [],
					'attributes'  => [
						':value' => '$store.dialog.filename',
					],
				],
			],
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => I18n::_t( 'Title' ),
			'class'         => 'dg g-2 gtc-3',
			'label_class'   => 'ga-1 fs-12 t-muted',
			'content_class' => 'ga-2',
			'fields'        => [
				[
					'type'        => 'text',
					'name'        => 'title',
					'label'       => '',
					'class'       => 'field field--outline fs-13',
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
						':value' => '$store.dialog.filename',
					],
				],
			],
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => I18n::_t( 'Caption' ),
			'class'         => 'dg g-2 gtc-3',
			'label_class'   => 'ga-1 fs-12 t-muted',
			'content_class' => 'ga-2',
			'fields'        => [
				[
					'type'        => 'text',
					'name'        => 'caption',
					'label'       => '',
					'class'       => 'field field--outline fs-13',
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
					'attributes'  => [],
				],
			],
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => I18n::_t( 'Description' ),
			'class'         => 'dg g-2 gtc-3',
			'label_class'   => 'ga-1 fs-12 t-muted',
			'content_class' => 'ga-2',
			'fields'        => [
				[
					'type'        => 'text',
					'name'        => 'description',
					'label'       => '',
					'class'       => 'field field--outline fs-13',
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
						':value' => '$store.dialog.content',
					],
				],
			],
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => I18n::_t( 'File URL' ),
			'class'         => 'dg g-2 gtc-3',
			'label_class'   => 'ga-1 fs-12 t-muted',
			'content_class' => 'ga-2',
			'fields'        => [
				[
					'type'        => 'text',
					'name'        => 'url',
					'label'       => '',
					'class'       => 'field field--outline fs-13',
					'label_class' => '',
					'reset'       => 0,
					'before'      => '',
					'after'       => '',
					'instruction' => '',
					'tooltip'     => '',
					'copy'        => true,
					'sanitizer'   => '',
					'validator'   => '',
					'conditions'  => [],
					'attributes'  => [
						':value'   => '$store.dialog.url',
						'readonly' => true,
					],
				],
			],
		],
	]
);
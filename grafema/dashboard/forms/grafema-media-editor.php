<?php
use Grafema\I18n;

/**
 * Media files editor.
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-media-editor',
	[
		'class' => 'dg gtc-8',
	],
	[
		[
			'type'        => 'search',
			'name'        => 's',
			'label'       => '',
			'class'       => 'field field--outline ga-5 px-4 py-6',
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
				'placeholder' => I18n::_t( 'e.g. search text' ),
			],
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => '',
			'class'         => 'ga-3 px-4 py-6 bg-gray-lt',
			'label_class'   => '',
			'content_class' => 'dg g-3',
			'fields'        => [
				[
					'type'     => 'custom',
					'name'     => 'manage',
					'callback' => function() {
						ob_start();
						?>
						<div class="dg g-1 fs-12">
							<div><strong><?php I18n::t( 'Uploaded on' ); ?>:</strong> July 9, 2024</div>
							<div><strong><?php I18n::t( 'Uploaded by' ); ?>:</strong> Yan Aleksandrov</div>
							<div><strong><?php I18n::t( 'File name' ); ?>:</strong> soft-inspirational-piano.mp3</div>
							<div><strong><?php I18n::t( 'File type' ); ?>:</strong> audio/mpeg</div>
							<div><strong><?php I18n::t( 'File size' ); ?>:</strong> 2MB</div>
							<div><strong><?php I18n::t( 'Length' ); ?>:</strong> 2 minutes, 48 seconds</div>
						</div>
						<?php
						return ob_get_clean();
					},
				],
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
							'instruction' => I18n::_f( '%sLearn how to describe the purpose of the image(opens in a new tab)%s. Leave empty if the image is purely decorative.', '<a href="//www.w3.org/WAI/tutorials/images/decision-tree/" target="_blank">', '</a>' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [],
						]
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
							'attributes'  => [],
						]
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
						]
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
							'attributes'  => [],
						]
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
								'value'    => 'https://market.codyshop.ru/wp-content/uploads/2024/09/chat.png',
								'readonly' => true,
							],
						]
					],
				],
			],
		],
	]
);
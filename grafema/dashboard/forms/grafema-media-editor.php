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
		'class' => 'dg acs gtc-7 md:gtc-1',
	],
	[
		[
			'type'     => 'custom',
			'name'     => 'image',
			'callback' => function() {
				?>
				<div class="df ais jcc ga-5 p-2 m-auto">
					<img :src="$store.dialog.url" :alt="$store.dialog.filename">
				</div>
				<?php
			},
		],
		[
			'type'          => 'group',
			'name'          => 'manage',
			'label'         => '',
			'class'         => 'ga-2 px-4 py-6 bg-gray-lt',
			'label_class'   => '',
			'content_class' => 'dg g-3',
			'fields'        => [
				[
					'type'     => 'custom',
					'name'     => 'manage',
					'callback' => function() {
						?>
						<div class="dg g-1 fs-12">
							<div><strong><?php I18n::t( 'Uploaded on' ); ?>:</strong> <span x-text="$store.dialog.created"></span></div>
							<div><strong><?php I18n::t( 'Uploaded by' ); ?>:</strong> <span x-text="$store.dialog.author"></span></div>
							<div><strong><?php I18n::t( 'File name' ); ?>:</strong> <span x-text="$store.dialog.filename"></span></div>
							<div><strong><?php I18n::t( 'File type' ); ?>:</strong> <span x-text="$store.dialog.mime"></span></div>
							<div><strong><?php I18n::t( 'File size' ); ?>:</strong> <span x-text="$store.dialog.sizeHumanize"></span></div>
							<div><strong><?php I18n::t( 'Length' ); ?>:</strong> 2 minutes, 48 seconds</div>
						</div>
						<?php
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
			],
		],
	]
);
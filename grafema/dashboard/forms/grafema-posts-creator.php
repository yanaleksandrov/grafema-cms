<?php
use Grafema\I18n;

/**
 * Form for create & edit posts.
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-posts-creator',
	[
		'class'           => 'dg g-8',
		'x-data'          => '{post: []}',
		'@submit.prevent' => 'submit()',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'   => 'permalinks',
					'type'   => 'group',
					'label'  => I18n::__( 'Permalink' ),
					'fields' => [
						[
							'name'       => 'permalink',
							'type'       => 'text',
							'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
							'class'      => 'dg g-1 ga-2',
							'attributes' => [
								'required' => true,
							],
						],
					],
				],
				[
					'name'   => 'thumbnail',
					'type'   => 'group',
					'label'  => I18n::__( 'Thumbnail' ),
					'fields' => [
						[
							'name'        => 'owners',
							'type'        => 'text',
							'label'       => I18n::__( 'Owner email address' ),
							'instruction' => I18n::__( 'This address is used for admin purposes. If you change this, an email will be sent to your new address to confirm it. The new address will not become active until confirmed.' ),
							'class'       => 'dg g-1 ga-2',
							'attributes'  => [
								'x-media' => '',
							],
						],
					],
				],
				[
					'name'   => 'summary',
					'type'   => 'group',
					'label'  => I18n::__( 'Summary' ),
					'fields' => [
						[
							'name'       => 'visibility',
							'type'       => 'select',
							'label'      => I18n::__( 'Visibility' ),
							'value'      => 'public',
							'attributes' => [
								'x-select' => '{"showSearch":1}',
							],
							'options' => [
								'public'  => I18n::__( 'Public' ),
								'pending' => I18n::__( 'Password protected' ),
								'private' => I18n::__( 'Private' ),
							],
						],
						[
							'name'       => 'status',
							'type'       => 'select',
							'label'      => I18n::__( 'Status' ),
							'value'      => 'publish',
							'attributes' => [
								'x-select' => '{"showSearch":1}',
							],
							'options' => [
								'publish' => I18n::__( 'Publish' ),
								'pending' => I18n::__( 'Pending' ),
								'draft'   => I18n::__( 'Draft' ),
							],
						],
						[
							'name'        => 'tagline',
							'type'        => 'text',
							'label'       => I18n::__( 'Template' ),
							'instruction' => I18n::__( 'Templates define the way content is displayed when viewing your site' ),
							'attributes'  => [
								'required'    => true,
								'placeholder' => I18n::__( 'e.g. Just another Grafema site' ),
							],
						],
						[
							'name'        => 'language',
							'type'        => 'select',
							'label'       => I18n::__( 'Author' ),
							'instruction' => I18n::__( 'Some description' ),
							'value'       => 'us',
							'attributes'  => [
								'x-select' => '{"showSearch":1}',
							],
							'options' => [
								'us' => [
									'image'   => 'assets/images/flags/us.svg',
									'content' => I18n::__( 'English - english' ),
								],
								'ru' => [
									'image'   => 'assets/images/flags/ru.svg',
									'content' => I18n::__( 'Russian - русский' ),
								],
								'he' => [
									'image'   => 'assets/images/flags/il.svg',
									'content' => I18n::__( 'עִבְרִית - Hebrew' ),
								],
							],
						],
					],
				],
				[
					'name'   => 'discussion',
					'type'   => 'group',
					'label'  => I18n::__( 'Discussion' ),
					'fields' => [
						[
							'name'       => 'discussion',
							'type'       => 'select',
							'value'      => 'public',
							'attributes' => [
								'x-select' => '',
							],
							'options' => [
								'open'        => I18n::__( 'Open' ),
								'close'       => I18n::__( 'Close' ),
								'temporarily' => I18n::__( 'Temporarily' ),
							],
						],
					],
				],
			]
		);
	}
);
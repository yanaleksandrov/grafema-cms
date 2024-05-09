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
					'name'    => 'permalinks',
					'type'    => 'group',
					'label'   => I18n::__( 'Permalink' ),
					'columns' => 1,
					'fields'  => [
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
					'name'   => 'summary',
					'type'   => 'group',
					'label'  => I18n::__( 'Summary' ),
					'fields' => [
						[
							'name'       => 'visibility',
							'type'       => 'select',
							'label'      => I18n::__( 'Visibility' ),
							'class'      => 'df aic fs-12 t-muted',
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
							'class'      => 'df aic fs-12 t-muted',
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
							'name'        => 'language',
							'type'        => 'select',
							'label'       => I18n::__( 'Author' ),
							'instruction' => '',
							'class'       => 'df aic fs-12 t-muted',
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
						[
							'name'        => 'date',
							'type'        => 'date',
							'label'       => I18n::__( 'Published on' ),
							'class'       => 'df aic fs-12 t-muted',
							'instruction' => '',
							'attributes'  => [
								'required'    => true,
								'placeholder' => I18n::__( 'e.g. Just another Grafema site' ),
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
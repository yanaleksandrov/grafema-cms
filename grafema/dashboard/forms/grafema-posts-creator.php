<?php
use Grafema\I18n;

/**
 * Form for create & edit posts.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-posts-creator',
	[
		'x-data'          => "{post: [], ...tab('main')}",
		'@submit.prevent' => '$ajax("posts/update")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'type'        => 'image',
					'name'        => 'avatar',
					'label'       => I18n::__( 'Upload avatar' ),
					'label_class' => '',
					'class'       => 'dg p-6 pb-3 bg-gray-lt',
					'description' => I18n::__( 'Click to upload or drag & drop' ),
					'tooltip'     => I18n::__( 'This is tooltip' ),
					'attributes'  => [
						'required' => false,
						'@change'  => '[...$refs.uploader.files].map(file => $ajax("upload/media").then(response => files.unshift(response[0])))',
					],
				],
				[
					'name'          => 'main',
					'type'          => 'tab',
					'label'         => I18n::__( 'General' ),
					'description'   => '',
					'icon'          => 'ph ph-pen',
					'class_menu'    => 'bg-gray-lt',
					'class_button'  => 'ml-7',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'    => 'excerpt',
							'type'    => 'group',
							'class'  => 'ga-1 fs-12 t-muted',
							'label'   => I18n::__( 'Title' ),
							'columns' => 1,
							'fields'  => [
								[
									'type'        => 'textarea',
									'label'       => '',
									'name'        => 'title',
									'value'       => '',
									'placeholder' => I18n::__( 'Add title...' ),
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => '',
									'attributes'  => [
										'rows' => 1,
									],
									'conditions'  => [],
								],
								[
									'name'       => 'permalink',
									'type'       => 'text',
									'before'     => '<code class="badge">https://cms.codyshop.ru/</code>',
									'class'      => 'dg g-1 ga-2',
									'attributes' => [
										'required' => true,
									],
								],
							],
						],
						[
							'name'    => 'excerpt',
							'type'    => 'group',
							'class'   => 'ga-1 fs-12 t-muted',
							'label'   => I18n::__( 'Excerpt' ),
							'columns' => 1,
							'fields'  => [
								[
									'type'        => 'textarea',
									'label'       => '',
									'name'        => 'excerpt',
									'value'       => '',
									'placeholder' => I18n::__( 'Write an excerpt (optional)...' ),
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'This section only applicable to post types that have excerpts enabled. Here you can write a one to two sentence description of the post.' ),
									'attributes'  => [
										'rows' => 1,
									],
									'conditions'  => [],
								],
							],
						],
						[
							'name'   => 'summary',
							'type'   => 'group',
							'class'  => 'ga-1 fs-12 t-muted',
							'label'  => I18n::__( 'Visibility & status' ),
							'fields' => [
								[
									'name'       => 'status',
									'type'       => 'select',
									'label'      => '',
									'class'      => '',
									'value'      => 'publish',
									'attributes' => [],
									'options' => [
										'publish' => I18n::__( 'Publish' ),
										'pending' => I18n::__( 'Pending' ),
										'draft'   => I18n::__( 'Draft' ),
									],
								],
								[
									'name'       => 'visibility',
									'type'       => 'select',
									'label'      => '',
									'class'      => '',
									'value'      => 'public',
									'attributes' => [],
									'options' => [
										'public'  => I18n::__( 'Public' ),
										'pending' => I18n::__( 'Password protected' ),
										'private' => I18n::__( 'Private' ),
									],
								],
							],
						],
						[
							'name'   => 'dates',
							'type'   => 'group',
							'class'  => 'ga-1 fs-12 t-muted',
							'label'  => I18n::__( 'Publication dates' ),
							'fields' => [
								[
									'name'        => 'date',
									'type'        => 'date',
									'label'       => '',
									'class'       => '',
									'instruction' => '',
									'before'      => I18n::_f( '%sFrom:%s', '<samp class="badge badge--blue-lt">', '</samp>' ),
									'attributes'  => [
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Just another Grafema site' ),
									],
								],
								[
									'name'        => 'date',
									'type'        => 'date',
									'label'       => '',
									'class'       => '',
									'instruction' => '',
									'before'      => I18n::_f( '%sTo:%s', '<samp class="badge badge--blue-lt">', '</samp>' ),
									'attributes'  => [
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Just another Grafema site' ),
									],
								],
							],
						],
						[
							'name'    => 'authors',
							'type'    => 'group',
							'class'   => 'ga-1 fs-12 t-muted',
							'label'   => I18n::__( 'Authors' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'        => 'language',
									'type'        => 'select',
									'label'       => '',
									'instruction' => '',
									'class'       => '',
									'value'       => 'us',
									'attributes'  => [
										'required' => true,
										'multiple' => true,
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
					],
				],
				[
					'name'          => 'comments',
					'type'          => 'tab',
					'label'         => I18n::__( 'Comments' ),
					'description'   => '',
					'icon'          => 'ph ph-chats',
					'class_button'  => '',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'    => 'discussion',
							'type'    => 'group',
							'class'   => 'ga-1 fs-12 t-muted',
							'label'   => I18n::__( 'Discussion' ),
							'columns' => 1,
							'fields'  => [
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
					],
				],
				[
					'name'          => 'submit',
					'type'          => 'tab',
					'label'         => '<button type="submit" class="btn btn--primary">Publish</button>',
					'description'   => '',
					'icon'          => '',
					'class_button'  => 'ml-auto mr-7',
					'class_content' => 'p-7',
					'fields'        => [],
				],
			]
		);
	}
);
<?php
use Grafema\I18n;

/**
 * Form for filter posts
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-posts-filter',
	[
		'class'    => 'dg g-7 p-8',
		'@change'  => '$ajax("posts/filter")',
		'x-sticky' => '',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'type'        => 'progress',
					'label'       => I18n::__( 'Storage' ),
					'name'        => 'progress',
					'value'       => 75,
					'placeholder' => '',
					'class'       => '',
					'label_class' => 'df aic fs-12 t-muted',
					'reset'       => 1,
					'required'    => 0,
					'copy'        => 0,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => I18n::__( '25% used of 2GB' ),
					'attributes'  => [
						'placeholder' => I18n::__( 'e.g. image name' ),
					],
					'conditions'  => [],
					'max'         => 100,
					'min'         => 0,
					'speed'       => 500,
				],
				[
					'type'        => 'search',
					'label'       => I18n::__( 'Search' ),
					'name'        => 's',
					'value'       => '',
					'placeholder' => '',
					'class'       => 'df aic fs-12 t-muted',
					'reset'       => 1,
					'required'    => 0,
					'copy'        => 0,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [
						'placeholder' => I18n::__( 'e.g. image name' ),
					],
					'conditions'  => [],
				],
				[
					'type'        => 'checkbox',
					'label'       => I18n::__( 'File types' ),
					'name'        => 'types',
					'value'       => '',
					'placeholder' => '',
					'class'       => 'df aic fs-12 t-muted',
					'reset'       => 1,
					'required'    => 0,
					'copy'        => 0,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [],
					'conditions'  => [],
					'options' => [
						'svg'    => sprintf( '%s%s', I18n::__( 'SVG' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
						'images' => sprintf( '%s%s', I18n::__( 'Images' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
						'video'  => sprintf( '%s%s', I18n::__( 'Video' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
						'audio'  => sprintf( '%s%s', I18n::__( 'Audio' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
						'zip'    => sprintf( '%s%s', I18n::__( 'ZIP' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
					],
				],
				[
					'type'        => 'select',
					'label'       => I18n::__( 'Author' ),
					'name'        => 'authors',
					'value'       => '',
					'placeholder' => '',
					'class'       => 'df aic fs-12 t-muted',
					'reset'       => 0,
					'required'    => 0,
					'copy'        => 0,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [],
					'conditions'  => [],
					'options' => [
						''                => I18n::__( 'Select an author' ),
						'user-registered' => I18n::__( 'New user registered' ),
					],
				],
			]
		);
	}
);
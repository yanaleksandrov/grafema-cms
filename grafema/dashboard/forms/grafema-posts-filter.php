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
					'label'       => I18n::_t( 'Storage' ),
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
					'instruction' => I18n::_t( '25% used of 2GB' ),
					'attributes'  => [
						'placeholder' => I18n::_t( 'e.g. image name' ),
					],
					'conditions'  => [],
					'max'         => 100,
					'min'         => 0,
					'speed'       => 500,
				],
				[
					'type'        => 'search',
					'label'       => I18n::_t( 'Search' ),
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
						'placeholder' => I18n::_t( 'e.g. image name' ),
					],
					'conditions'  => [],
				],
				[
					'type'        => 'checkbox',
					'label'       => I18n::_t( 'File types' ),
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
						'svg'    => sprintf( '%s%s', I18n::_t( 'SVG' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
						'images' => sprintf( '%s%s', I18n::_t( 'Images' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
						'video'  => sprintf( '%s%s', I18n::_t( 'Video' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
						'audio'  => sprintf( '%s%s', I18n::_t( 'Audio' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
						'zip'    => sprintf( '%s%s', I18n::_t( 'ZIP' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
					],
				],
				[
					'type'        => 'select',
					'label'       => I18n::_t( 'Author' ),
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
						''                => I18n::_t( 'Select an author' ),
						'user-registered' => I18n::_t( 'New user registered' ),
					],
				],
			]
		);
	}
);
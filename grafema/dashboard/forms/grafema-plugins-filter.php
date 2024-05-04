<?php
use Grafema\I18n;

/**
 * Plugins filter form.
 *
 * @since 1.0.0
 */
Form::register(
	'grafema-plugins-filter',
	[
		'class'    => 'dg g-7 p-8',
		'@change'  => '$ajax("filter/plugins")',
		'x-sticky' => '',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 's',
					'type'        => 'search',
					'label'       => I18n::__( 'Search extensions' ),
					'label_class' => 'df aic fs-12 t-muted mb-1',
					'attributes'  => [
						'placeholder' => I18n::__( 'e.g. commerce' ),
					],
				],
				[
					'name'        => 'categories[]',
					'type'        => 'checkbox',
					'label'       => I18n::__( 'Categories' ),
					'label_class' => 'df aic fs-12 t-muted mb-1',
					'reset'       => true,
					'instruction' => '',
					'attributes'  => [
						'name'  => 'categories',
						'value' => true,
					],
					'options' => [
						'commerce'  => sprintf( '%s%s', I18n::__( 'Commerce' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
						'analytics' => sprintf( '%s%s', I18n::__( 'Analytics' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
						'security'  => sprintf( '%s%s', I18n::__( 'Security' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
						'seo'       => sprintf( '%s%s', I18n::__( 'SEO' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
						'content'   => sprintf( '%s%s', I18n::__( 'Content' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
					],
				],
				[
					'name'        => 'rating[]',
					'type'        => 'checkbox',
					'label'       => I18n::__( 'Rating' ),
					'label_class' => 'df aic fs-12 t-muted mb-1',
					'reset'       => true,
					'instruction' => '',
					'attributes'  => [
						'name'  => 'rating',
						'value' => true,
					],
					'options' => [
						'commerce'  => sprintf( '%s%s', I18n::__( 'Show all' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
						'analytics' => sprintf( '%s%s', I18n::__( '1 star and higher' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
						'security'  => sprintf( '%s%s', I18n::__( '2 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
						'seo'       => sprintf( '%s%s', I18n::__( '3 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
						'content'   => sprintf( '%s%s', I18n::__( '4 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
					],
				],
			]
		);
	}
);
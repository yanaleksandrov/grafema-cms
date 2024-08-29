<?php
use Grafema\I18n;

/**
 * Plugins filter form.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-plugins-filter',
	[
		'class'    => 'dg g-7 p-8',
		'@change'  => '$ajax("filter/plugins")',
		'x-sticky' => '',
	],
	[
		[
			'name'        => 's',
			'type'        => 'search',
			'label'       => I18n::_t( 'Search extensions' ),
			'label_class' => 'df aic fs-12 t-muted mb-1',
			'attributes'  => [
				'placeholder' => I18n::_t( 'e.g. commerce' ),
			],
		],
		[
			'name'        => 'categories[]',
			'type'        => 'checkbox',
			'label'       => I18n::_t( 'Categories' ),
			'label_class' => 'df aic fs-12 t-muted mb-1',
			'reset'       => true,
			'instruction' => '',
			'attributes'  => [
				'name'  => 'categories',
				'value' => true,
			],
			'options' => [
				'commerce'  => sprintf( '%s%s', I18n::_t( 'Commerce' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
				'analytics' => sprintf( '%s%s', I18n::_t( 'Analytics' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
				'security'  => sprintf( '%s%s', I18n::_t( 'Security' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
				'seo'       => sprintf( '%s%s', I18n::_t( 'SEO' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
				'content'   => sprintf( '%s%s', I18n::_t( 'Content' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
			],
		],
		[
			'name'        => 'rating[]',
			'type'        => 'checkbox',
			'label'       => I18n::_t( 'Rating' ),
			'label_class' => 'df aic fs-12 t-muted mb-1',
			'reset'       => true,
			'instruction' => '',
			'attributes'  => [
				'name'  => 'rating',
				'value' => true,
			],
			'options' => [
				'commerce'  => sprintf( '%s%s', I18n::_t( 'Show all' ), '<span class="badge badge--blue-lt ml-auto">56</span>' ),
				'analytics' => sprintf( '%s%s', I18n::_t( '1 star and higher' ), '<span class="badge badge--blue-lt ml-auto">670</span>' ),
				'security'  => sprintf( '%s%s', I18n::_t( '2 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">35</span>' ),
				'seo'       => sprintf( '%s%s', I18n::_t( '3 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">147</span>' ),
				'content'   => sprintf( '%s%s', I18n::_t( '4 stars and higher' ), '<span class="badge badge--blue-lt ml-auto">74</span>' ),
			],
		],
	]
);
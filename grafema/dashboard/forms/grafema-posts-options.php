<?php
use Grafema\I18n;

/**
 * Posts options
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-posts-options',
	[
		'class'           => 'dg g-4 pt-2 p-4',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("items/options")',
	],
	[
		[
			'type'        => 'checkbox',
			'name'        => 'remember',
			'label'       => I18n::_t( 'Columns' ),
			'class'       => '',
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
				'value' => true,
			],
			'options' => [
				'author'     => I18n::_t( 'Author' ),
				'categories' => I18n::_t( 'Categories' ),
				'date'       => I18n::_t( 'Date' ),
			],
		],
		[
			'type'        => 'submit',
			'name'        => 'apply',
			'label'       => I18n::_f( '%s Apply', '<i class="ph ph-paper-plane-tilt"></i>' ),
			'class'       => '',
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
				'class' => 'btn btn--sm btn--primary',
			],
		],
	]
);
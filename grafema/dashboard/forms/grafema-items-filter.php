<?php
use Grafema\I18n;

/**
 * Posts actions.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-items-filter',
	[
		'class'           => 'table__filter',
		'x-show'          => 'showFilter === true',
		'x-cloak'         => true,
		'@submit.prevent' => '$ajax("items/filter")',
	],
	[
		[
			'type'        => 'search',
			'label'       => '',
			'name'        => 's',
			'value'       => '',
			'placeholder' => '',
			'class'       => 'field field--sm field--outline',
			'label_class' => '',
			'reset'       => 1,
			'required'    => 0,
			'copy'        => 0,
			'before'      => '',
			'after'       => '',
			'tooltip'     => '',
			'instruction' => '',
			'attributes'  => [
				'placeholder' => I18n::_t( 'e.g. search text' ),
			],
			'conditions'  => [],
		],
		[
			'type'       => 'submit',
			'name'       => 'submit',
			'label'      => I18n::_t( 'Apply filter' ),
			'attributes' => [
				'class' => 'btn btn--sm btn--primary',
			],
		],
	]
);
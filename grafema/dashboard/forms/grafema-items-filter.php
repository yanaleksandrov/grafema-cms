<?php
use Grafema\I18n;

/**
 * Posts actions.
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
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
			'uid'         => 's',
			'label'       => '',
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
				'placeholder' => I18n::_t( 'e.g. search text' ),
			],
		],
		[
			'type'        => 'submit',
			'uid'         => 'submit',
			'label'       => I18n::_t( 'Apply filter' ),
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
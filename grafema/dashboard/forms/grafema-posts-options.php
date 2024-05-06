<?php
use Grafema\I18n;

/**
 * Posts options
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-posts-options',
	[
		'class'           => 'dg g-4',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("items/options")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'perpage',
					'type'        => 'select',
					'label'       => I18n::__( 'Number of items per page' ),
					'label_class' => 'df aic fs-12 t-muted',
					'value'       => '',
					'reset'       => false,
					'attributes'  => [
						'x-select' => '',
					],
					'options' => [
						'25'  => 25,
						'50'  => 50,
						'100' => 100,
						'250' => 250,
						'500' => 500,
					],
				],
				[
					'name'        => 'remember',
					'type'        => 'checkbox',
					'label'       => I18n::__( 'Columns' ),
					'label_class' => 'df aic fs-12 t-muted',
					'reset'       => false,
					'instruction' => '',
					'attributes'  => [
						'value' => true,
					],
					'options' => [
						'author'     => I18n::__( 'Author' ),
						'categories' => I18n::__( 'Categories' ),
						'date'       => I18n::__( 'Date' ),
					],
				],
				[
					'name'       => 'apply',
					'type'       => 'submit',
					'label'      => I18n::__( 'Apply' ),
					'attributes' => [
						'class' => 'btn btn--primary',
					],
				],
			]
		);
	}
);
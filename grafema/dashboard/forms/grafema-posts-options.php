<?php
use Grafema\I18n;

/**
 * Posts options
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-posts-options',
	[
		'class'           => 'dg g-4 pt-2 p-4',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("items/options")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'remember',
					'type'        => 'checkbox',
					'label'       => I18n::_t( 'Columns' ),
					'label_class' => 'df aic fs-12 t-muted',
					'reset'       => false,
					'instruction' => '',
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
					'name'       => 'apply',
					'type'       => 'submit',
					'label'      => I18n::_f( '%s Apply', '<i class="ph ph-paper-plane-tilt"></i>' ),
					'attributes' => [
						'class' => 'btn btn--sm btn--primary',
					],
				],
			]
		);
	}
);
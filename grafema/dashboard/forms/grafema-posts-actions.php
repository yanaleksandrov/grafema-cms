<?php
use Grafema\I18n;

/**
 * Posts actions.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-posts-actions',
	[
		'class'           => 'dg g-4',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("items/options")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'action',
					'type'        => 'select',
					'label'       => I18n::_t( 'Number of items per page' ),
					'label_class' => 'df aic fs-12 t-muted',
					'value'       => '',
					'reset'       => false,
					'attributes'  => [
						'x-select' => '',
					],
					'options' => [
						'edit'  => I18n::_t( 'Edit' ),
						'trash' => I18n::_t( 'Move to trash' ),
					],
				],
				[
					'name'       => 'apply',
					'type'       => 'submit',
					'label'      => I18n::_t( 'Apply' ),
					'attributes' => [
						'class' => 'btn btn--primary',
					],
				],
			]
		);
	}
);
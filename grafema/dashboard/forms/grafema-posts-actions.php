<?php
use Grafema\I18n;

/**
 * Posts actions.
 *
 * @since 1.0.0
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
					'label'       => I18n::__( 'Number of items per page' ),
					'label_class' => 'df aic fs-12 t-muted',
					'value'       => '',
					'reset'       => false,
					'attributes'  => [
						'x-select' => '',
					],
					'options' => [
						'edit'  => I18n::__( 'Edit' ),
						'trash' => I18n::__( 'Move to trash' ),
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
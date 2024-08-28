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
		'class'           => 'df fww g-1',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("items/options")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'action',
					'type'        => 'select',
					'attributes'  => [
						'x-select' => true,
						'class'    => 'select select--sm select--outline',
					],
					'options' => [
						''      => I18n::_t( 'Bulk Actions' ),
						'edit'  => I18n::_t( 'Edit' ),
						'trash' => I18n::_t( 'Move to trash' ),
						'copy'  => I18n::_t( 'Copy' ),
					],
				],
				[
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
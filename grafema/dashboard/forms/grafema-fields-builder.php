<?php
/**
 * Form for build custom fields
 *
 * @since 1.0.0
 */
Form::register(
	'grafema-fields-builder',
	[
		'class'           => 'builder',
		'x-data'          => 'builder',
		'@submit.prevent' => 'submit()',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name' => 'builder',
					'type' => 'builder',
				],
			]
		);
	}
);
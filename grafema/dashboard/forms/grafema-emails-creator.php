<?php
use Grafema\I18n;

/**
 * Form for create & edit emails.
 *
 * @since 1.0.0
 */
Form::register(
	'grafema-emails-creator',
	[
		'class'          => 'dg g-6 p-6',
		'@submit.window' => '$ajax("import-email")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'       => 'title',
					'type'       => 'text',
					'label'      => I18n::__( 'Email heading' ),
					'attributes' => [
						'required'    => true,
						'placeholder' => I18n::__( 'Email heading' ),
					],
				],
				[
					'name'       => 'event',
					'type'       => 'select',
					'label'      => I18n::__( 'Event' ),
					'value'      => '',
					'attributes' => [
						'x-select' => '',
						'required' => true,
					],
					'options' => [
						''                => I18n::__( 'Select an event' ),
						'user-registered' => I18n::__( 'New user registered' ),
					],
				],
				[
					'name'       => 'subtitle',
					'type'       => 'text',
					'label'      => I18n::__( 'Subtitle' ),
					'attributes' => [
						'required'    => true,
						'placeholder' => I18n::__( 'Subtitle' ),
					],
				],
				[
					'name'        => 'content',
					'type'        => 'textarea',
					'label'       => I18n::__( 'Content' ),
					'instruction' => I18n::__( 'Enter recipients for this email. Each recipient email from a new line.' ),
					'attributes'  => [
						'rows'        => 5,
						'required'    => true,
						'placeholder' => I18n::__( 'N/A' ),
					],
				],
				[
					'name'        => 'recipients',
					'type'        => 'textarea',
					'label'       => I18n::__( 'Recipient(s)' ),
					'instruction' => I18n::__( 'Enter recipients for this email. Each recipient email from a new line.' ),
					'attributes'  => [
						'required'    => true,
						'placeholder' => I18n::__( 'N/A' ),
					],
				],
			]
		);
	}
);
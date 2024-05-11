<?php
use Grafema\I18n;

/**
 * Form for create & edit emails.
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
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
					'class'       => 'df aic fs-12 t-muted',
					'label'      => I18n::__( 'Email heading' ),
					'attributes' => [
						'required'    => true,
						'placeholder' => I18n::__( 'Email heading' ),
					],
				],
				[
					'name'       => 'event',
					'type'       => 'select',
					'class'       => 'df aic fs-12 t-muted',
					'label'      => I18n::__( 'Events' ),
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
					'class'       => 'df aic fs-12 t-muted',
					'label'      => I18n::__( 'Subtitle' ),
					'attributes' => [
						'required'    => true,
						'placeholder' => I18n::__( 'Subtitle' ),
					],
				],
				[
					'name'        => 'content',
					'type'        => 'textarea',
					'class'       => 'df aic fs-12 t-muted',
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
					'class'       => 'df aic fs-12 t-muted',
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
<?php
use Grafema\I18n;

/**
 * Form for create & edit emails.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-emails-creator',
	[
		'class'          => 'dg g-6 p-6',
		'@submit.window' => '$ajax("import-email")',
	],
	[
		[
			'name'       => 'title',
			'type'       => 'text',
			'class'       => 'df aic fs-12 t-muted',
			'label'      => I18n::_t( 'Email heading' ),
			'attributes' => [
				'required'    => true,
				'placeholder' => I18n::_t( 'Email heading' ),
			],
		],
		[
			'name'       => 'event',
			'type'       => 'select',
			'class'       => 'df aic fs-12 t-muted',
			'label'      => I18n::_t( 'Events' ),
			'value'      => '',
			'attributes' => [
				'x-select' => '',
				'required' => true,
			],
			'options' => [
				''                => I18n::_t( 'Select an event' ),
				'user-registered' => I18n::_t( 'New user registered' ),
			],
		],
		[
			'name'       => 'subtitle',
			'type'       => 'text',
			'class'       => 'df aic fs-12 t-muted',
			'label'      => I18n::_t( 'Subtitle' ),
			'attributes' => [
				'required'    => true,
				'placeholder' => I18n::_t( 'Subtitle' ),
			],
		],
		[
			'name'        => 'content',
			'type'        => 'textarea',
			'class'       => 'df aic fs-12 t-muted',
			'label'       => I18n::_t( 'Content' ),
			'instruction' => I18n::_t( 'Enter recipients for this email. Each recipient email from a new line.' ),
			'attributes'  => [
				'rows'        => 5,
				'required'    => true,
				'placeholder' => I18n::_t( 'N/A' ),
			],
		],
		[
			'name'        => 'recipients',
			'type'        => 'textarea',
			'class'       => 'df aic fs-12 t-muted',
			'label'       => I18n::_t( 'Recipient(s)' ),
			'instruction' => I18n::_t( 'Enter recipients for this email. Each recipient email from a new line.' ),
			'attributes'  => [
				'required'    => true,
				'placeholder' => I18n::_t( 'N/A' ),
			],
		],
	]
);
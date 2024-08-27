<?php
use Grafema\I18n;

/**
 * Reset password form
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-user-reset-password',
	[
		'class'           => 'dg g-6',
		'x-data'          => '{email: ""}',
		'@submit.prevent' => '$ajax("user/reset-password")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'title',
					'type'        => 'header',
					'label'       => I18n::_t( 'Reset password' ),
					'class'       => 't-center',
					'instruction' => I18n::_t( 'Enter the email address that you used to register. We will send you an email that will allow you to reset your password.' ),
				],
				[
					'name'       => 'email',
					'type'       => 'email',
					'label'      => I18n::_t( 'Your email' ),
					'attributes' => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Enter your email address' ),
						'x-autocomplete' => '',
						'x-model'        => 'email',
					],
				],
				[
					'name'       => 'sign-in',
					'type'       => 'submit',
					'label'      => I18n::_t( 'Send me instructions' ),
					'attributes' => [
						'class'     => 'btn btn--primary',
						'disabled'  => '',
						':disabled' => '!/\S+@\S+\.\S+/.test(email)',
					],
				],
			]
		);
	}
);
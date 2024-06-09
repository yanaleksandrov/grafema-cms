<?php
use Grafema\I18n;

/**
 * Sign Up form
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-user-sign-up',
	[
		'class'           => 'dg g-6',
		'x-data'          => '',
		'@submit.prevent' => "\$ajax('user/sign-up')",
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'type'        => 'header',
					'label'       => I18n::__( 'Create new account' ),
					'name'        => 'title',
					'class'       => '',
					'instruction' => I18n::__( 'After creating an account, more platform features will be available to you' ),
				],
				[
					'type'        => 'email',
					'label'       => I18n::__( 'User Email' ),
					'name'        => 'email',
					'value'       => '',
					'placeholder' => I18n::__( 'Enter user email' ),
					'class'       => '',
					'reset'       => 0,
					'required'    => 1,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => I18n::__( 'Notifications will be sent to this email' ),
					'attributes'  => [
						'@change' => "login = email.split('@')[0]",
					],
					'conditions'  => [],
				],
				[
					'type'        => 'text',
					'label'       => I18n::__( 'User Login' ),
					'name'        => 'login',
					'value'       => '',
					'placeholder' => I18n::__( 'Enter user login' ),
					'class'       => '',
					'reset'       => 0,
					'required'    => 1,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [],
					'conditions'  => [],
				],
				[
					'type'        => 'password',
					'label'       => I18n::__( 'Password' ),
					'name'        => 'password',
					'value'       => '',
					'placeholder' => I18n::__( 'Password' ),
					'class'       => '',
					'required'    => 1,
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [
						'x-autocomplete' => '',
					],
					'conditions' => [],
					'switcher'   => 1,
					'generator'  => 1,
					'indicator'  => 0,
					'characters' => [
						'lowercase' => 2,
						'uppercase' => 2,
						'special'   => 2,
						'length'    => 12,
						'digit'     => 2,
					],
				],
				[
					'name'       => 'signup',
					'type'       => 'submit',
					'label'      => I18n::__( 'Sign Up' ),
					'attributes' => [
						'class'     => 'btn btn--primary',
						'disabled'  => '',
						':disabled' => '![login, email, password].every(i => i.trim())',
					],
				],
			]
		);
	}
);
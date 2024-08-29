<?php
use Grafema\I18n;

/**
 * Sign Up form
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-user-sign-up',
	[
		'class'           => 'dg g-6',
		'x-data'          => '',
		'@submit.prevent' => "\$ajax('user/sign-up')",
	],
	[
		[
			'type'        => 'header',
			'label'       => I18n::_t( 'Create new account' ),
			'name'        => 'title',
			'class'       => '',
			'instruction' => I18n::_t( 'After creating an account, more platform features will be available to you' ),
		],
		[
			'type'        => 'email',
			'label'       => I18n::_t( 'User Email' ),
			'name'        => 'email',
			'value'       => '',
			'placeholder' => I18n::_t( 'Enter user email' ),
			'class'       => '',
			'reset'       => 0,
			'required'    => 1,
			'before'      => '',
			'after'       => '',
			'tooltip'     => '',
			'instruction' => I18n::_t( 'Notifications will be sent to this email' ),
			'attributes'  => [
				'@change' => "login = email.split('@')[0]",
			],
			'conditions'  => [],
		],
		[
			'type'        => 'text',
			'label'       => I18n::_t( 'User Login' ),
			'name'        => 'login',
			'value'       => '',
			'placeholder' => I18n::_t( 'Enter user login' ),
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
			'label'       => I18n::_t( 'Password' ),
			'name'        => 'password',
			'value'       => '',
			'placeholder' => I18n::_t( 'Password' ),
			'class'       => '',
			'required'    => 1,
			'tooltip'     => '',
			'instruction' => '',
			'attributes'  => [],
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
			'label'      => I18n::_t( 'Sign Up' ),
			'attributes' => [
				'class'     => 'btn btn--primary',
				'disabled'  => '',
				':disabled' => '![login, email, password].every(i => i.trim())',
			],
		],
	]
);
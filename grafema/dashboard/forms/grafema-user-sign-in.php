<?php
use Grafema\I18n;

/**
 * Sign In form
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-user-sign-in',
	[
		'class'           => 'dg g-6',
		'@submit.prevent' => '$ajax("user/sign-in").then()',
		'x-data'          => '',
	],
	[
		[
			'type'        => 'header',
			'label'       => I18n::_t( 'Welcome to Grafema' ),
			'name'        => 'title',
			'class'       => '',
			'instruction' => I18n::_t( 'Login to you account and enjoy exclusive features and many more' ),
		],
		[
			'type'        => 'text',
			'uid'         => 'login',
			'label'       => I18n::_t( 'Login or email' ),
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => '',
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'placeholder' => I18n::_t( 'Enter login or email' ),
			],
		],
		[
			'type'        => 'password',
			'uid'         => 'password',
			'label'       => I18n::_t( 'Password' ),
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => I18n::_f( 'Forgot your password? You can %sreset it here%s', '<a href="/dashboard/reset-password">', '</a>' ),
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'placeholder' => I18n::_t( 'Password' ),
				'required'    => 1,
			],
			'switcher'    => 1,
			'generator'   => 0,
			'indicator'   => 0,
			'characters'  => [],
		],
		[
			'type'        => 'checkbox',
			'uid'         => 'remember',
			'label'       => '',
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => '',
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'value' => true,
			],
			'options' => [
				'remember' => I18n::_t( 'Remember me on this device' ),
			],
		],
		[
			'type'        => 'submit',
			'uid'         => 'sign-in',
			'label'      => I18n::_t( 'Sign In' ),
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => '',
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				'class'     => 'btn btn--primary',
				'disabled'  => '',
				':disabled' => '!login.trim() || !password.trim()',
			],
		],
	]
);
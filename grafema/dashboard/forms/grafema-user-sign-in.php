<?php
use Grafema\I18n;

/**
 * Sign In form
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-user-sign-in',
	[
		'class'           => 'card card-border g-6 p-8',
		'@submit.prevent' => '$ajax("user/sign-in").then()',
		'x-data'          => '',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'type'        => 'header',
					'label'       => I18n::__( 'Welcome to Grafema' ),
					'name'        => 'title',
					'class'       => '',
					'instruction' => I18n::__( 'Login to you account and enjoy exclusive features and many more' ),
				],
				[
					'type'        => 'text',
					'label'       => I18n::__( 'Login or email' ),
					'name'        => 'login',
					'value'       => '',
					'placeholder' => I18n::__( 'Enter login or email' ),
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
					'reset'       => 0,
					'required'    => 1,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => I18n::_f( 'Forgot your password? You can %sreset it here%s', '<a href="/dashboard/reset-password">', '</a>' ),
					'attributes'  => [],
					'conditions'  => [],
					'switcher'    => 1,
					'generator'   => 0,
					'indicator'   => 0,
					'characters'  => [],
				],
				[
					'name'        => 'remember',
					'type'        => 'checkbox',
					'label'       => '',
					'label_class' => '',
					'reset'       => false,
					'instruction' => '',
					'attributes'  => [
						'value' => true,
					],
					'options' => [
						'remember' => I18n::__( 'Remember me on this device' ),
					],
				],
				[
					'name'       => 'sign-in',
					'type'       => 'submit',
					'label'      => I18n::__( 'Sign In' ),
					'attributes' => [
						'class'     => 'btn btn--primary',
						'disabled'  => '',
						':disabled' => '!login.trim() || !password.trim()',
					],
				],
			]
		);
	}
);
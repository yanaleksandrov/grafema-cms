<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\Api\Crud;
use Grafema\Sanitizer;
use Grafema\Url;
use Grafema\Validator;
use Grafema\View;
use Grafema\Mail\Mail;
use Grafema\I18n;
use Grafema\Errors;

class User extends \Grafema\Api\Handler
{
	use Crud;

	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'user';

	/**
	 * Create item.
	 *
	 * @url    POST api/user
	 */
	public function create(): array
	{
		return [
			'method' => 'POST create user',
		];
	}

	/**
	 * Get all items.
	 *
	 * @url    GET api/user
	 */
	public function index(): array
	{
		return [
			'method' => 'GET user list',
		];
	}

	/**
	 * Update item by ID.
	 *
	 * @url    PUT api/user/$id
	 */
	public function update(): array
	{
		return [
			'method' => 'PUT update user by ID',
		];
	}

	/**
	 * Remove item by ID.
	 *
	 * @url    DELETE api/user/$id
	 */
	public function delete(): array
	{
		return [
			'method' => 'DELETE remove user by ID',
		];
	}

	/**
	 * Check the compliance of the server with the minimum requirements.
	 *
	 * @url    GET api/user/sign-in
	 */
	public static function signIn(): Errors|\Grafema\User|array
	{
		$user = \Grafema\User::login( $_POST );
		if ( $user instanceof \Grafema\User ) {
			return [
				'logged'   => true,
				'redirect' => Url::site( 'dashboard' ),
			];
		}
		return $user;
	}

	/**
	 * Sign up user.
	 *
	 * @since 1.0.0
	 */
	public static function signUp(): array
	{
		return \Grafema\User::add( $_REQUEST ?? [] );
	}

	/**
	 * Reset user password.
	 *
	 * @since 1.0.0
	 */
	public static function resetPassword(): array
	{
		$email = trim( strval( $_REQUEST['email'] ?? '' ) );

		if ( empty( $email ) ) {
			return [
				[
					'delay'    => 0,
					'fragment' => I18n::__( 'Field can\'t be empty' ),
					'method'   => 'update',
					'target'   => '.email-error',
				],
				[
					'delay'    => 0,
					'fragment' => 'is-invalid',
					'method'   => 'addClass',
					'target'   => '[name="email"]',
				],
				[
					'delay'    => 4000,
					'fragment' => '',
					'method'   => 'update',
					'target'   => '.email-error',
				],
				[
					'delay'    => 4000,
					'fragment' => 'is-invalid',
					'method'   => 'removeClass',
					'target'   => '[name="email"]',
				],
			];
		}

		$user = \Grafema\User::get( $email, 'email' );
		if ( ! $user instanceof User ) {
			return [
				[
					'delay'    => 0,
					'fragment' => I18n::__( 'The user with this email was not found' ),
					'method'   => 'update',
					'target'   => '.email-error',
				],
				[
					'delay'    => 4000,
					'fragment' => '',
					'method'   => 'update',
					'target'   => '.email-error',
				],
			];
		} else {
			$mail_is_sent = Mail::send(
				$email,
				'Instructions for reset password',
				View::include(
					GRFM_DASHBOARD . 'templates/mails/wrapper.php',
					[
						'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
					]
				)
			);
			if ( $mail_is_sent ) {
				return [
					[
						'fragment' => true,
						'target'   => 'sent',
					],
				];
			}
		}
	}
}

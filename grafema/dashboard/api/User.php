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
use Grafema\View;
use Grafema\Mail;
use Grafema\Errors;
use Grafema\I18n;

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
	public static function signUp(): Errors|\Grafema\User|array
	{
		$user = \Grafema\User::add( $_REQUEST ?? [] );
		if ( $user instanceof \Grafema\User ) {
			return [
				'signed-up' => true,
				'redirect'  => Url::sign_in(),
			];
		}
		return $user;
	}

	/**
	 * Reset user password.
	 *
	 * @since 1.0.0
	 */
	public static function resetPassword(): Errors|\Grafema\User|array
	{
		$email = Sanitizer::email( $_REQUEST['email'] ?? '' );
		$user  = \Grafema\User::get( $email, 'email' );
		if ( $user instanceof \Grafema\User ) {
			$mail_is_sent = Mail::send(
				$email,
				I18n::__( 'Instructions for reset password' ),
				View::include(
					GRFM_DASHBOARD . 'templates/mails/wrapper.php',
					[
						'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
					]
				)
			);

			return [
				'mail-is-sent' => $mail_is_sent,
			];
		}
		return $user;
	}
}

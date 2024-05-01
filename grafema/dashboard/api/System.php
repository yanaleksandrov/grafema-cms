<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\Db;
use Grafema\App\App;
use Grafema\View;
Use Grafema\I18n;
use Grafema\Users\Roles;
use Grafema\Users\Users;
use Grafema\User;
use Grafema\Option;
use Grafema\Options;
use Grafema\Taxonomy;
use Grafema\Term;
use Grafema\File\File;
use Grafema\Comments;
use Grafema\Sanitizer;

class System extends \Grafema\Api\Handler
{

	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'system';

	/**
	 * Check the compliance of the server with the minimum requirements.
	 *
	 * @since 1.0.0
	 */
	public static function test(): array
	{
		$checks = [
			'connection' => false,
			'pdo'        => false,
			'curl'       => false,
			'mbstring'   => false,
			'gd'         => false,
			'memory'     => 128,
			'php'        => '8.1',
			'mysql'      => '5.6',
		];

		$database = (new Sanitizer(
			$_POST,
			[
				'database' => 'trim',
				'username' => 'trim',
				'password' => 'trim',
				'host'     => 'trim',
				'prefix'   => 'trim',
			]
		))->apply();

		Db::init( $database );

		$data = array_map(fn ($check, $value) => [
			$check => match ($check) {
				'php',
				'mysql',
				'memory',
				'connection' => App::check( $check, $value ),
				default      => App::has( $check ),
			}
		], array_keys($checks), $checks);

		return array_merge(...$data);
	}

	/**
	 * Run Grafema installation.
	 *
	 * @since 1.0.0
	 */
	public static function install(): array
	{
		echo '<pre>';
		print_r( $_POST );
		echo '</pre>';
		exit;
		$site        = $_POST['site'] ?? [];
		$database    = $_POST['db'] ?? [];
		$user        = $_POST['user'] ?? [];
		$name        = trim( strval( $site['name'] ?? '' ) );
		$description = trim( strval( $site['instruction'] ?? '' ) );
		$login       = trim( strval( $user['login'] ?? '' ) );
		$email       = trim( strval( $user['email'] ?? '' ) );
		$password    = trim( strval( $user['password'] ?? '' ) );
		$protocol    = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' );

		/**
		 * The check for connection to the database should have already been passed by this point.
		 * Therefore, just fill in the file config.php data and immediately connect it.
		 *
		 * @since 1.0.0
		 */
		$config = GRFM_PATH . 'config.php';
		if ( ! file_exists( $config ) ) {
			$file = new File( GRFM_PATH . 'config-sample.php' );
			$file->copy( 'config' )->rewrite(
				[
					'db.name'     => trim( strval( $database['name'] ?? '' ) ),
					'db.username' => trim( strval( $database['username'] ?? '' ) ),
					'db.password' => trim( strval( $database['password'] ?? '' ) ),
					'db.host'     => trim( strval( $database['host'] ?? 'localhost' ) ),
					'db.prefix'   => trim( strval( $database['prefix'] ?? 'grafema_' ) ),
				]
			);
		}

		require_once $config;

		Db::init();

		/**
		 * Creating the necessary tables in the database
		 *
		 * @since 1.0.0
		 */
		Term::migrate();
		Users::migrate();
		Options::migrate();
		Comments::migrate();
		Taxonomy::migrate();

		/**
		 * Fill same options
		 *
		 * @since 1.0.0
		 */
		Option::update(
			'site',
			[
				'url'     => $protocol . $_SERVER['SERVER_NAME'],
				'name'    => $name,
				'tagline' => $description,
			]
		);

		/**
		 * Add roles and users.
		 *
		 * @since 1.0.0
		 */
		Roles::add(
			'admin',
			I18n::__( 'Administrator' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
				'manage_options',
				'manage_update',
				'manage_import',
				'manage_export',
				'themes_install',
				'themes_switch',
				'themes_delete',
				'plugins_install',
				'plugins_activate',
				'plugins_delete',
				'users_create',
				'users_edit',
				'users_delete',
			]
		);

		Roles::add(
			'editor',
			I18n::__( 'Editor' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
				'other_types_publish',
				'other_types_edit',
				'other_types_delete',
				'private_types_publish',
				'private_types_edit',
				'private_types_delete',
				'manage_comments',
			]
		);

		Roles::add(
			'author',
			I18n::__( 'Author' ),
			[
				'read',
				'files_upload',
				'files_edit',
				'files_delete',
				'types_publish',
				'types_edit',
				'types_delete',
			]
		);

		Roles::add(
			'subscriber',
			I18n::__( 'Subscriber' ),
			[
				'read',
			]
		);

		$user = User::add(
			[
				'login'    => $login,
				'email'    => $email,
				'password' => $password,
				'roles'    => [ 'admin' ],
			]
		);

		if ( $user instanceof User ) {
			User::login( $login, $password );

			return [
				[
					'delay'    => 100,
					'fragment' => View::include(
						GRFM_PATH . 'dashboard/templates/installed.php',
						[
							'title'       => I18n::__( 'Grafema is installed' ),
							'instruction' => I18n::__( 'Grafema has been installed. Thank you, and enjoy!' ),
						]
					),
					'method'   => 'update',
					'target'   => 'form.card',
				],
			];
		}
		return $user;
	}
}

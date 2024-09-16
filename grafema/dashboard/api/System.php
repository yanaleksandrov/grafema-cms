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
use Grafema\Error;
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
	 * @since 2025.1
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
	 * @since 2025.1
	 */
	public static function install(): array
	{
		$protocol = ( ! empty( $_SERVER['HTTPS'] ) && 'off' !== strtolower( $_SERVER['HTTPS'] ) ? 'https://' : 'http://' );
		$siteurl  = $protocol . $_SERVER['SERVER_NAME'];

		// TODO: check sanitize rules & add validator
		[ $site, $userdata, $database ] = (new Sanitizer(
			$_POST,
			[
				'site.name'     => 'trim',
				'site.tagline'  => 'trim',
				'site.url'      => "trim:{$siteurl}|url",
				'user.login'    => 'trim',
				'user.email'    => 'email',
				'user.password' => 'trim',
				'db.database'   => 'trim',
				'db.username'   => 'trim',
				'db.password'   => 'trim',
				'db.host'       => 'trim',
				'db.prefix'     => 'snakecase',
			]
		))->values();

		/**
		 * The check for connection to the database should have already been passed by this point.
		 * Therefore, just fill in the file config.php data and immediately connect it.
		 *
		 * @since 2025.1
		 */
		$config = GRFM_PATH . 'config.php';
		if ( ! file_exists( $config ) ) {
			(new File( GRFM_PATH . 'config-sample.php' ))->copy( 'config' )->rewrite(
				array_combine(
					[
						'db.name',
						'db.username',
						'db.password',
						'db.host',
						'db.prefix'
					],
					$database
				)
			);
		}

		require_once $config;

		Db::init();

		/**
		 * Creating the necessary tables in the database
		 *
		 * @since 2025.1
		 */
		Term::migrate();
		Users::migrate();
		Options::migrate();
		Comments::migrate();
		Taxonomy::migrate();

		/**
		 * Fill same options
		 *
		 * @since 2025.1
		 */
		Option::update( 'site', $site );

		$user = User::add( $userdata );
		if ( $user instanceof User ) {
			User::login( $userdata );

			return [
				'installed' => $user instanceof User,
			];
		}

		return Error::get();
	}
}

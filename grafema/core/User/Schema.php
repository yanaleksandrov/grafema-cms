<?php
namespace Grafema\User;

use Grafema\DB;
use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\Validator;

/**
 * @since 2025.1
 */
class Schema {

	/**
	 * DataBase table name.
	 *
	 * @since 2025.1
	 */
	public static string $table = 'users';

	/**
	 *
	 *
	 * @param array $userdata
	 * @return array
	 *
	 * @since 2025.1
	 */
	public static function sanitize( array $userdata ): array {
		return ( new Sanitizer(
			$userdata,
			[
				'login'    => 'login',
				'password' => 'trim',
				'email'    => 'email',
				'showname' => 'ucfirst:$login',
				'nicename' => 'slug:$login|unique',
			]
		) )->extend(
			'unique',
			function( $value ) {
				$suffix = 1;
				while (Db::select(self::$table, 'ID', ['nicename' => $value . ($suffix > 1 ? "-$suffix" : '')])) {
					$suffix++;
				}
				return sprintf( '%s%s', $value, $suffix > 1 ? "-$suffix" : '' );
			}
		)->apply();
	}

	/**
	 *
	 *
	 * @param array $userdata
	 * @return array|Validator
	 *
	 * @since 2025.1
	 */
	public static function validate( array $userdata ): Validator|array {
		return ( new Validator(
			$userdata,
			[
				'login'    => 'lengthMin:3|lengthMax:60',
				'password' => 'required',
				'email'    => 'email|unique',
			]
		) )->extend(
			'email:unique',
			I18n::_t( 'Sorry, that email address or login is already used!' ),
			fn( $validator ) => ! self::exists(
				[
					'login' => $validator->fields['login'],
					'email' => $validator->fields['email'],
				]
			)
		)->apply();
	}

	/**
	 * Create new table into database.
	 *
	 * @since 2025.1
	 */
	public static function migrate(): void
	{
		$length          = DB_MAX_INDEX_LENGTH;
		$table           = DB_PREFIX . self::$table;
		$charset_collate = '';
		if ( DB_CHARSET ) {
			$charset_collate = 'DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charset_collate .= ' COLLATE ' . DB_COLLATE;
		}

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table} (
				ID            bigint(20)   unsigned NOT NULL auto_increment,
				login         varchar(60)  NOT NULL default '',
				password      varchar(255) NOT NULL default '',
				nicename      varchar(60)  NOT NULL default '',
				firstname     varchar(60)  NOT NULL default '',
				lastname      varchar(60)  NOT NULL default '',
				showname      varchar(255) NOT NULL default '',
				email         varchar(100) NOT NULL default '',
				locale        varchar(100) NOT NULL default '',
				registered    datetime     NOT NULL default NOW(),
				visited       datetime     NOT NULL default NOW(),
				PRIMARY KEY   (ID),
				KEY login_key (login),
				KEY nicename  (nicename),
				KEY email     (email)
			) {$charset_collate};"
		)->fetchAll();

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table}_fields (
				meta_id     bigint(20)   unsigned NOT NULL auto_increment,
				ID          bigint(20)   unsigned NOT NULL default '0',
				name        varchar(255) default NULL,
				value       longtext,
				PRIMARY KEY ( meta_id ),
				KEY user_id ( ID ),
				KEY name ( name({$length}) )
			) {$charset_collate};"
		)->fetchAll();

		Db::updateSchema();
	}
}

<?php
namespace Grafema;

use Grafema\Users\Roles;
use Grafema\Users\Users;
use Grafema\Helpers\Hash;

/**
 * User
 */
class User extends Users {

	/**
	 * Session key
	 *
	 * @since 1.0.0
	 * @var   string
	 */
	private static string $session_id = DB_PREFIX . 'user_logged';

	/**
	 * Current user data.
	 *
	 * @since 1.0.0
	 */
	private static $current;

	/**
	 * Capabilities that the individual user has been granted outside those inherited from their role.
	 *
	 * @var   array Array of key/value pairs where keys represent a capability name
	 *              and boolean values represent whether the user has that capability.
	 * @since 1.0.0
	 */
	public array $caps = [];

	public int $ID = 0;
	public string $login = '';
	public string $password = '';
	public string $showname = '';
	public string $nicename = '';
	public string $email = '';
	public string $registered = '';
	public string $visited = '';
	public array $fields = [];

	/**
	 * Insert a user into the database.
	 *
	 * The showname & nickname fields should not be left empty, because nickname
	 * is part of the URL of the user's page, and showname is displayed as the name.
	 * Therefore, we generate it based on the login.
	 *
	 * TODO: убедиться что функция возвращает максимум 2 заначения: либо ID юзера, либо Errors
	 *
	 * @param array $userdata {
	 *     @type int    $ID           User ID. If supplied, the user will be updated.
	 *     @type string $password     The plain-text user password.
	 *     @type string $login        The user's login username.
	 *     @type string $nicename     The URL-friendly username.
	 *     @type string $showname     The user's display name.
	 *     @type string $email        The user email address.
	 *     @type string $registered   Date the user registered. Format is 'Y-m-d H:i:s'.
	 *     @type string $visited      Date the user last time visit website. Format is 'Y-m-d H:i:s'.
	 * }
	 *
	 * @return User|Errors The newly created user's ID or an Errors object if the user could not be created.
	 * @since 1.0.0
	 */
	public static function add( array $userdata ): User|Errors {
		$userdata = ( new Sanitizer(
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

		// validate incoming user data
		$userdata = (
			new Validator(
				$userdata,
				[
					'login'    => 'lengthMin:3|lengthMax:60',
					'password' => 'required',
					'email'    => 'email|unique',
				]
			)
		)->extend(
			'email:unique',
			I18n::__( 'Sorry, that email address or login is already used!' ),
			fn( $validator ) => ! self::exists(
				[
					'login' => $validator->fields['login'],
					'email' => $validator->fields['email'],
				]
			)
		)->apply();

		if ( $userdata instanceof Validator ) {
			return new Errors( 'user-add', $userdata );
		}

		[ $login, $password ] = array_values( $userdata );
		$userdata['password'] = $password ? password_hash($password, PASSWORD_DEFAULT) : Hash::generate();

		$user_count = Db::insert( self::$table, $userdata )->rowCount();
		if ( $user_count !== 1 ) {
			return new Errors( 'user-add', I18n::__( 'Something went wrong, it was not possible to add a user.' ) );
		}

		$user = self::get( $login, 'login' );

		/**
		 * Fires immediately after a new user is registered.
		 *
		 * @since 1.0.0
		 *
		 * @param User $user data of created user
		 */
		Hook::apply( 'grafema_user_added', $user );

		return $user;
	}

	/**
	 * Retrieves user info by a given field.
	 *
	 * @param string|int    $value A value for $field. A user ID, slug, email address, or login name.
	 * @param string        $getBy The field to retrieve the user with. ID | login | email | nicename.
	 * @return Errors|User
	 * @since 1.0.0
	 */
	public static function get( string|int $value, string $getBy = 'ID' ): Errors|User {
		if ( empty( $value ) ) {
			return new Errors( 'user-get-empty', I18n::_f( 'You are trying to find a user with an empty %s.', $getBy ) );
		}

		if ( ! in_array( $getBy, [ 'ID', 'login', 'email', 'nicename' ], true ) ) {
			return new Errors( 'user-get-by', I18n::__( 'To get a user, use an ID, login, email or nicename.' ) );
		}

		$user = Db::select( self::$table, '*', [ $getBy => $value ], [ 'LIMIT' => 1 ] );
		$user = (array) ( $user[0] ?? [] );
		if ( $user ) {
			$userdata = new self();
			foreach ( $user as $field => $value ) {
				$userdata->$field = $value;
			}
			return $userdata;
		}

		return new Errors( 'user-get', I18n::__( 'User not found!' ) );
	}

	/**
	 * Update a user in the database.
	 * If no ID is found in the received array, the function passes the work to the add method.
	 *
	 * @param array $userdata
	 * @return Errors|User|int
	 * @since 1.0.0
	 */
	public static function update( array $userdata ): Errors|User|int
	{
		$user_id = isset( $userdata['ID'] ) ? (int) $userdata['ID'] : 0;
		if ( ! $user_id ) {
			return new Errors( 'user-update-invalid-id', I18n::__( 'Invalid user ID.' ) );
		}

		$user_id = Sanitizer::absint( $userdata['ID'] ?? 0 );
		if ( ! $user_id ) {
			return self::add( $userdata );
		}

		// login is the unchanged parameter of the user
		unset( $userdata['login'] );

		$user = Db::select( self::$table, 'ID', [ 'ID' => $user_id ], [ 'LIMIT' => 1 ] );
		if ( ! $user ) {
			return new Errors( 'user-update', I18n::__( 'User not found.' ) );
		}

		// sanitize incoming data and exclusion of extraneous data
		$userdata = self::dataSanitize( $userdata );

		return Db::update( self::$table, $userdata )->rowCount();
	}

	/**
	 * Remove user and optionally reassign posts and links to another user.
	 *
	 * If the $reassign parameter is not assigned to a User ID, then all posts will
	 * be deleted of that user. The action {@see 'delete_user'} that is passed the User ID
	 * being deleted will be run after the posts are either reassigned or deleted.
	 * The user meta will also be deleted that are for that User ID.
	 *
	 * @param  int   $user_id  User ID.
	 * @param  int   $reassign Optional. Reassign posts to new User ID.
	 * @return Errors|int      The number of remote users or false.
	 * @since 1.0.0
	 */
	public static function delete( int $user_id, int $reassign = 0 ) {
		$fields = [
			'ID' => abs( $user_id ),
		];

		if ( ! self::exists( $fields ) ) {
			return new Errors( 'user-delete', I18n::__( 'The user you are trying to delete does not exist.' ) );
		}

		if ( $reassign ) {

		}
		return Db::delete( self::$table, $fields )->rowCount();
	}

	/**
	 * Searches for users by the specified parameters
	 *
	 * @since  1.0.0
	 * @return bool Array of fields and values to search for users.
	 */
	public static function exists( array $fields ): bool {
		$users = Db::select( self::$table, '*', [ 'OR' => $fields ] );
		if ( $users ) {
			return true;
		}
		return false;
	}

	/**
	 * Returns whether a particular user has the specified capability.
	 *
	 * @since 1.0.0
	 *
	 * @param integer  $user_id      User ID.
	 * @param  string  $capabilities Capability name.
	 * @return   bool                Whether the user has the given capability.
	 */
	public static function can( int $user_id, $capabilities ) {
		$roles = [];
		$user  = self::current();
		if ( (int) $user->ID === $user_id ) {
			$roles = $user->roles ?? [];
		} else {
			$user = self::get( $user_id );
			if ( $user ) {
				$roles = $user->roles ?? [];
			}
		}

		if ( is_array( $roles ) ) {
			foreach ( $roles as $role ) {
				return Roles::has_cap( $role, $capabilities );
			}
		}
		return false;
	}

	/**
	 * Checks whether the user is with the specified role.
	 *
	 * @since 1.0.0
	 *
	 * @param integer  $user_id  User ID.
	 * @param  string  $role     Role name.
	 * @return   bool            The user has a role.
	 */
	public static function is( int $user_id, string $role ) {
		$roles = [];
		$user  = self::current();
		if ( $user->ID === $user_id ) {
			$roles = $user->roles ?? [];
		} else {
			$user = self::get( $user_id );
			if ( $user ) {
				$roles = $user->roles ?? [];
			}
		}
		return in_array( $role, $roles, true );
	}

	/**
	 * Получает данные текущего, зарегистрированного пользователя.
	 *
	 * @return Errors|false|User
	 * @throws \JsonException
	 * @since   1.0.0
	 */
	public static function current() {
		if ( self::$current ) {
			return self::$current;
		}

		Session::start();
		$user_id = abs( (int) Session::get( self::$session_id ) );
		if ( $user_id ) {
			self::$current = self::get( $user_id );
		}
		return self::$current;
	}

	/**
	 * Проверяет, залогинен ли пользователь в этом сеансе.
	 *
	 * @since   1.0.0
	 * @return   bool
	 */
	public static function logged() {
		Session::start();
		$user_id = abs( (int) Session::get( self::$session_id ) );
		if ( $user_id ) {
			return true;
		}
		return false;
	}

	/**
	 * Authorizes the user by password and login/email.
	 *
	 * @param array $userdata
	 * @return Errors|User
	 * @since  1.0.0
	 */
	public static function login( array $userdata ): Errors|User {
		$userdata = ( new Sanitizer(
			$userdata,
			[
				'login'    => 'login',
				'password' => 'trim',
				'remember' => 'bool',
			]
		) )->apply();

		$userdata = ( new Validator(
			$userdata,
			[
				'login'    => 'lengthMin:3|lengthMax:60',
				'password' => 'required',
			]
		) )->apply();

		if ( $userdata instanceof Validator ) {
			return new Errors( 'user-login', $userdata );
		}

		[ $login_or_email, $password, $remember ] = array_values( $userdata );

		$field = Is::email( $login_or_email ) ? 'email' : 'login';
		$user  = User::get( $login_or_email, $field );
		if ( $user instanceof User ) {
			if ( password_verify( $password, $user->password ) ) {
				if ( $remember ) {
					Session::start();
				} else {
					Session::start( 1 );
				}
				Session::set( self::$session_id, $user->ID );

				return self::$current = $user;
			}

			return new Errors( 'user-login', I18n::__( 'User password is incorrect.' ) );
		}

		return new Errors( 'user-login', I18n::__( 'User not found: invalid login or email.' ) );
	}

	/**
	 * Де-авторизует текущего пользователя.
	 *
	 * @since   1.0.0
	 */
	public static function logout(): void {
		Session::start();
		$user_id       = abs( (int) Session::get( self::$session_id ) );
		self::$current = [];
		if ( $user_id ) {
			self::update(
				[
					'ID' => $user_id,
				]
			);
		}
		Session::set( self::$session_id, null );
	}

	/**
	 * Sanitizer user data before inserting into the database.
	 *
	 * @param array $userdata
	 * @return array
	 * @since 1.0.0
	 */
	private static function dataSanitize( array $userdata ): array
	{
		$schema    = Db::schema( DB_PREFIX . self::$table );
		$_userdata = [];
		foreach ( $userdata as $column => $value ) {
			if ( isset( $schema[ $column ] ) ) {
				if ( is_array( $value ) ) {
					$_userdata[ $column ] = $value;
				} else {
					$_userdata[ $column ] = Esc::sql( $value, $schema[ $column ] );
				}
			}
		}
		return $_userdata;
	}
}

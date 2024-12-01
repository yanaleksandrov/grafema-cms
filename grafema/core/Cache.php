<?php

namespace Grafema;

use DateTime;

final class Cache {

	use Cache\Traits;

	/**
	 * Adds data to the cache.
	 *
	 * @param string $key
	 * @param mixed $value
	 * @param DateTime|null $expiry TODO: add string support, like "+1 day"
	 * @param string $group
	 * @return mixed
	 *
	 * @since 2025.1
	 */
	public static function add( string $key, mixed $value, string $group = 'default', ?DateTime $expiry = null ): mixed {
		if ( isset( self::$locks[ $group ][ $key ] ) ) {
			return false;
		}

		if ( isset( self::$cache[ $group ][ $key ] ) ) {
			return self::$cache[ $group ][ $key ]['value'];
		}

		self::$cache[ $group ][ $key ] = [
			'value'  => $value,
			'expiry' => $expiry?->getTimestamp(),
		];

		if ( $expiry instanceof DateTime ) {
			Db::insert(
				self::$table,
				[
					'key'        => $key,
					'value'      => $value,
					'expiration' => $expiry->getTimestamp(),
				]
			);
		}

		return $value;
	}

	/**
	 * Retrieves data from the cache.
	 *
	 * @param string $key
	 * @param callable|null $callback
	 * @param string $group
	 * @return mixed
	 *
	 * @since 2025.1
	 */
	public static function get( string $key, string $group = 'default', ?callable $callback = null ): mixed {
		if ( isset( self::$cache[ $group ][ $key ] ) ) {
			return self::$cache[ $group ][ $key ]['value'];
		}

		if ( is_callable( $callback ) ) {
			return self::add( $key, call_user_func( $callback ), $group );
		}

		return null;
	}

	/**
	 * Retrieves and removes data from the cache.
	 *
	 * @param string $key
	 * @param string $group
	 * @return mixed
	 *
	 * @since 2025.1
	 */
	public static function pull( string $key, string $group = 'default' ): mixed {
		$value = self::get( $key, $group );

		self::forget( $key );

		return $value;
	}

	/**
	 * Suspend the addition of data to the cache.
	 *
	 * @param string $key
	 * @param callable $callback
	 * @param string $group
	 * @since 2025.1
	 */
	public static function suspend( callable $callback, string $key, string $group = 'default' ) {
		self::$locks[ $group ][ $key ] = true;

		call_user_func( $callback );

		unset( self::$locks[ $group ][ $key ] );
	}

	/**
	 * Clears data from the cache.
	 *
	 * @param string $key
	 * @param string $group
	 * @return bool
	 * @since 2025.1
	 */
	public static function forget( string $key = '', string $group = 'default' ): bool {
		if ( $key ) {
			unset( self::$cache[ $group ][ $key ] );
		} else {
			self::$cache[ $group ] = [];
		}
		return true;
	}

	/**
	 * Increases the value of a key by a given amount.
	 *
	 * @param string $key
	 * @param int|float $amount
	 * @param string $group
	 * @return mixed
	 *
	 * @since 2025.1
	 */
	public static function increase( string $key, int|float $amount = 1, string $group = 'default' ): bool {
		if ( $key && is_numeric( self::$cache[ $group ][ $key ]['value'] ?? null ) ) {
			self::$cache[ $group ][ $key ]['value'] += $amount;

			return true;
		}
		return false;
	}

	/**
	 * Decreases the value of a key by a given amount.
	 *
	 * @param string $key
	 * @param int|float $amount
	 * @param string $group
	 * @return mixed
	 *
	 * @since 2025.1
	 */
	public static function decrease( string $key, int|float $amount = 1, string $group = 'default' ): bool {
		if ( $key && is_numeric( self::$cache[ $group ][ $key ]['value'] ?? null ) ) {
			self::$cache[ $group ][ $key ]['value'] -= $amount;

			return true;
		}
		return false;
	}
}

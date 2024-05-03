<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Csrf\Providers;

use Grafema\Csrf\Interfaces\Provider;

class NativeHttpOnlyCookieProvider implements Provider
{
	const HOUR_IN_SECONDS = 3600;

	/**
	 * Get a cookie value.
	 */
	public function get( string $key ): mixed
	{
		return $_COOKIE[$key] ?? null;
	}

	/**
	 * Set a cookie value.
	 */
	public function set( string $key, mixed $value ): void
	{
		setcookie( $key, $value, time() + self::HOUR_IN_SECONDS, '/', '', false, true );
	}
}

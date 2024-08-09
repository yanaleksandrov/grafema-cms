<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Grafema\Asset;

class Sanitizer {

	/**
	 * Sanitize ID of asset.
	 *
	 * @param string $uuid
	 * @return string
	 * @since 2025.1
	 */
	public static function id( string $uuid ): string
	{
		$uuid = str_replace( ['_', '.', ',', ' '], '-', $uuid );

		return trim( preg_replace( '/\W-/', '', $uuid ) );
	}

	/**
	 * Sanitize key of asset.
	 *
	 * @param string $key
	 * @return string
	 * @since 2025.1
	 */
	public static function key( string $key ): string
	{
		$key = str_replace( ['_', '-'], ' ', $key );

		return lcfirst( str_replace( ' ', '', ucwords( $key ) ) );
	}

	/**
	 * Sanitize asset url.
	 *
	 * @param string $url
	 * @return string
	 * @since 2025.1
	 */
	public static function url( string $url ): string
	{
		// remove leading and trailing whitespace
		$url = trim( $url );

		// normalize slashes
		$url = str_replace( ';//', '://', $url );

		// remove characters other than letters, numbers, hyphens, underscores, dots, and slashes
		$url = preg_replace( '/[^a-zA-Z0-9-_.:\/?]/', '', $url );

		// remove double and triple slashes but not :// after http protocol
		return preg_replace( '#([^:])//+#', '\1/', $url );
	}

	/**
	 * Sanitize array.
	 *
	 * @param mixed $data
	 * @return array
	 */
	public static function array( mixed $data ): array {
		return is_array( $data ) ? $data : [];
	}
}

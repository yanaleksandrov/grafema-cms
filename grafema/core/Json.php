<?php
namespace Grafema;

/**
 * Class Json
 *
 * Provides methods for encoding and decoding JSON data.
 * This class is intended for handling JSON serialization and deserialization
 * with options for customization, such as pretty printing and handling
 * character encoding. It ensures that JSON data is processed correctly
 * while providing meaningful error handling.
 *
 * @package Grafema
 */
final class Json {

	/**
	 * Converts value to JSON format.
	 *
	 * @param mixed $value
	 * @param bool $pretty       For easier reading and clarity.
	 * @param bool $ascii        For ASCII output and $html_safe for HTML escaping.
	 * @param bool $forceObjects Enforces the encoding of non-associative arrays as objects.
	 * @return string|Error
	 */
	public static function encode( mixed $value, bool $pretty = false, bool $ascii = true, bool $forceObjects = false ): string|Error {
		$flags = JSON_UNESCAPED_SLASHES                  // do not escape slashes by default
			| ( $ascii ? 0 : JSON_UNESCAPED_UNICODE )    // keep unicode unescaped if $ascii = false
			| ( $pretty ? JSON_PRETTY_PRINT : 0 )        // pretty print
			| ( $forceObjects ? JSON_FORCE_OBJECT : 0 ); // convert arrays to objects if specified

		$json = json_encode( $value, $flags );

		// check for encoding errors
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new Error( json_last_error_msg() );
		}

		return $json;
	}


	/**
	 * Parses JSON to PHP value.
	 *
	 * @param string $json
	 * @param bool $forceArrays Enforces the decoding of objects as arrays.
	 * @return mixed
	 */
	public static function decode( string $json, bool $forceArrays = false ): mixed {
		$flags  = $forceArrays ? JSON_OBJECT_AS_ARRAY : 0;
		$flags |= JSON_BIGINT_AS_STRING;

		$value = json_decode( $json, flags: $flags );

		// check for decoding errors
		if ( json_last_error() !== JSON_ERROR_NONE ) {
			return new Error( 'json-decode', json_last_error_msg() );
		}

		return $value;
	}

	/**
	 * Check that incoming data is valid json.
	 *
	 * @param mixed $data
	 * @return bool
	 */
	public static function isValid( mixed $data ): bool {
		return self::decode( $data ) instanceof Error;
	}
}

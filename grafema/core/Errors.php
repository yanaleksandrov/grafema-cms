<?php
namespace Grafema;

/**
 * Errors class.
 *
 * A class whose task is to simplify error handling (messages) when working with Grafema.
 * In order to start working with a class, you need to create an instance of it, and then add or remove errors (messages) to it.
 * The class applies the $message and $data parameters.
 *
 * These are strings and in general they are similar, but they separated by logic:
 *   $message - is a message for the user.
 *   $data    - is a instruction for the developer.
 *
 * @since 1.0.0
 */
class Errors extends Errors\Handler {

	/**
	 * Initialize the error.
	 *
	 * If `$code` is empty, the other parameters will be ignored.
	 * When `$code` is not empty, `$message` will be used even if it is empty.
	 *
	 * Though the class is constructed with a single error code and
	 * message, multiple codes can be added using the `add()` method.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code      Errors code.
	 * @param string|array $message Errors message.
	 */
	public function __construct( string|int $code, string|array $message = '' ) {
		(new Errors\Handler())->push( $code, $message );

		return $this;
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @param string|int $code      Errors code.
	 * @param string|array $message Errors message.
	 * @return void
	 *
	 * @since 1.0.0
	 */
	public static function add( string|int $code, string|array $message = '' ): void {
		(new Errors\Handler())->push( $code, $message );
	}

	/**
	 * Removes the specified error.
	 *
	 * This function removes all error messages associated with the specified
	 * error code, along with any error data for that code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Errors code.
	 */
	public static function delete( string|int $code ): void {
		(new Errors\Handler())->remove( $code );
	}

	/**
	 * Verify if the instance contains errors.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public static function exists(): bool {
		return (new Errors\Handler())->hasErrors();
	}

	/**
	 * Retrieve all or single error messages.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Retrieve messages matching code, if exists.
	 * @return array           Errors strings on success, or empty array on failure (if using code parameter).
	 */
	public static function get( string|int $code = '' ): array {
		return (new Errors\Handler())->getErrors( $code );
	}

	/**
	 * Retrieve all error codes.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of error codes, if available.
	 */
	public static function getCodes(): array {
		return (new Errors\Handler())->getErrorCodes();
	}
}

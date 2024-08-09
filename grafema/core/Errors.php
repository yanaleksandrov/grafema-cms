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
 * @since 2025.1
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
	 * @param string|int $code                Errors code.
	 * @param string|array|Validator $message Error single message or array of messages.
	 * @since 2025.1
	 */
	public function __construct( string|int $code, string|array|Validator $message = '' ) {
		self::add( $code, $message );

		return $this;
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @param string|int $code                Errors code.
	 * @param string|array|Validator $message Error single message or array of messages.
	 * @return void
	 * @since 2025.1
	 */
	public static function add( string|int $code, string|array|Validator $message = '' ): void {
		if ( $message instanceof Validator ) {
			foreach ( $message->errors as $key => $errors ) {
				(new Errors\Handler())->push( sprintf( '%s-%s', $code, $key ), $errors );
			}
		} else {
			(new Errors\Handler())->push( $code, $message );
		}
	}

	/**
	 * Removes the specified error.
	 *
	 * This function removes all error messages associated with the specified
	 * error code, along with any error data for that code.
	 *
	 * @since 2025.1
	 *
	 * @param string|int $code Errors code.
	 */
	public static function delete( string|int $code ): void {
		(new Errors\Handler())->remove( $code );
	}

	/**
	 * Verify if the instance contains errors.
	 *
	 * @since 2025.1
	 *
	 * @return bool
	 */
	public static function exists(): bool {
		return (new Errors\Handler())->hasErrors();
	}

	/**
	 * Retrieve all or single error messages.
	 *
	 * @since 2025.1
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
	 * @since 2025.1
	 *
	 * @return array List of error codes, if available.
	 */
	public static function getCodes(): array {
		return (new Errors\Handler())->getErrorCodes();
	}
}

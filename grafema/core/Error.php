<?php
namespace Grafema;

/**
 * Error class.
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
class Error {

	/**
	 * Stores the list of errors.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public array $errors = [];

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
	 * @param string|int $code    Error code.
	 * @param string     $message Error message.
	 */
	public function __construct( string|int $code = '', string $message = '' ) {
		if ( empty( $code ) ) {
			return;
		}
		$this->add( $code, $message );
	}

	/**
	 * Add an error or append additional message to an existing error.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code    Error code.
	 * @param string     $message Error message.
	 */
	public function add( string|int $code, string $message ): void {
		$this->errors[ $code ][] = $message;
	}

	/**
	 * Removes the specified error.
	 *
	 * This function removes all error messages associated with the specified
	 * error code, along with any error data for that code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Error code.
	 */
	public function remove( string|int $code ): void {
		if ( isset( $this->errors[ $code ] ) ) {
			unset( $this->errors[ $code ] );
		}
	}

	/**
	 * Retrieve all error codes.
	 *
	 * @since 1.0.0
	 *
	 * @return array List of error codes, if available.
	 */
	public function getErrorCodes(): array {
		if ( ! $this->hasErrors() ) {
			return [];
		}
		return array_keys( $this->errors );
	}

	/**
	 * Retrieve first error code available.
	 *
	 * @since 1.0.0
	 *
	 * @return string|int Empty string, if no error codes.
	 */
	public function getErrorCode(): int|string
	{
		$codes = $this->getErrorCodes();

		return $codes[0] ?? '';
	}

	/**
	 * Retrieve all error messages or error messages matching code.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Retrieve messages matching code, if exists.
	 * @return array Error strings on success, or empty array on failure (if using code parameter).
	 */
	public function getErrorMessages( $code = '' ): array {
		// Return all messages if no code specified.
		if ( empty( $code ) ) {
			$all_messages = [];
			foreach ( (array) $this->errors as $messages ) {
				$all_messages = array_merge( $all_messages, $messages );
			}
			return $all_messages;
		}

		return $this->errors[ $code ] ?? [];
	}

	/**
	 * Get single error message.
	 *
	 * This will get the first message available for the code. If no code is
	 * given then the first code available will be used.
	 *
	 * @since 1.0.0
	 *
	 * @param string|int $code Optional. Error code to retrieve message.
	 * @return string
	 */
	public function getErrorMessage( string|int $code = '' ): string {
		if ( empty( $code ) ) {
			$code = $this->getErrorCode();
		}
		$messages = $this->getErrorMessages( $code );

		return $messages[0] ?? '';
	}

	/**
	 * Verify if the instance contains errors.
	 *
	 * @since 1.0.0
	 *
	 * @return bool
	 */
	public function hasErrors(): bool
	{
		return ! empty( $this->errors );
	}
}

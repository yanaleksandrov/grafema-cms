<?php
namespace Grafema;

/**
 * The I18n class provides methods for handling translations in the system, including
 * regular translations, translations with formatting, and conditional translations.
 * The class also offers methods for returning translations sanitized for use in HTML attributes.
 *
 * Main functionalities:
 * - `t|_t(_attr)`: translates a string and returns/outputs it (optionally sanitizes for HTML attributes).
 * - `f|_f(_attr)`: translates a string with placeholders and returns/outputs it (optionally sanitizes for HTML attributes).
 * - `c|_c(_attr)`: translates a string based on a condition and returns/outputs it (optionally sanitizes for HTML attributes).
 *
 * TODO: Implement text pluralization.
 *
 * @package Grafema
 * @since 2025.1
 */
final class I18n extends I18n\Locale {

	/**
	 * Output translated string.
	 *
	 * @param string $string
	 *
	 * @since 2025.1
	 */
	public static function t( string $string ): void {
		echo self::_t( $string );
	}

	/**
	 * Get translated string.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _t( string $string ): string {
		return self::translate( $string );
	}

	/**
	 * Translation and use in html attribute.
	 *
	 * @param string $string
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function t_attr( string $string ): void {
		echo self::_t_attr( $string );
	}

	/**
	 * Translation and use in html attribute.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _t_attr( string $string ): string {
		return Sanitizer::attribute( self::_t( $string ) );
	}

	/**
	 * Translate with formatting.
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function f( string $string, mixed ...$args ): void {
		echo self::_f( $string, ...$args );
	}

	/**
	 * Translate with formatting. Use instead php placeholders like %s and %d, human readable strings.
	 * The function support converting to lowercase, uppercase & first letter to uppercase.
	 * To write the placeholder and the suffix together, use the "\" slash.
	 *
	 * For example:
	 *
	 * I18n::_f( 'Hi, :Firstname :Lastname, you have :count\st none closed ":TASKNAME" task', 'yan', 'aleksandrov', 1, 'test' )
	 *
	 * return 'Hi, Yan Aleksandrov, you have 1st none closed "TEST" task'
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _f( string $string, mixed ...$args ): string {
		return preg_replace_callback('/:(\w+)(?:\\\\([^:]+))?|%[sd]/u', function( $matches ) use ( &$args ) {
			if ( $matches[0] === '%s' || $matches[0] === '%d' ) {
				return array_shift( $args );
			}

			$placeholder = $matches[1] ?? '';
			$suffix      = $matches[2] ?? '';

			$value = array_shift( $args );

			$replacement = match (true) {
				mb_strtolower( $placeholder ) === $placeholder => mb_strtolower( $value ),
				mb_strtoupper( $placeholder ) === $placeholder => mb_strtoupper( $value ),
				default => mb_convert_case( $value, MB_CASE_TITLE, 'UTF-8' ),
			};

			return $replacement . $suffix;
		}, $string );
	}

	/**
	 * Output translation with placeholder & sanitize like html attribute.
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function f_attr( string $string, mixed ...$args ): void {
		echo self::_f_attr( $string, $args );
	}

	/**
	 * Return translation with placeholder & sanitize like html attribute.
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _f_attr( string $string, mixed ...$args ): string {
		return Sanitizer::attribute( self::_f( $string, $args ) );
	}

	/**
	 * Output translated text by condition.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function c( bool $condition, string $ifString, string $elseString = '' ): void {
		echo self::_c( $condition, $ifString, $elseString );
	}

	/**
	 * Return translated text by condition.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _c( bool $condition, string $ifString, string $elseString = '' ): string
	{
		return $condition ? self::_t( $ifString ) : self::_t( $elseString );
	}

	/**
	 * Output translated text by condition & sanitize like html attribute.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function c_attr( bool $condition, string $ifString, string $elseString = '' ): void {
		echo self::_c_attr( $condition, $ifString, $elseString );
	}

	/**
	 * Return translated text by condition & sanitize like html attribute.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _c_attr( bool $condition, string $ifString, string $elseString = '' ): string {
		return Sanitizer::attribute( self::_c( $condition, $ifString, $elseString ) );
	}

	/**
	 * Output local from HTTP.
	 *
	 * @param string $default
	 * @return string
	 *
	 * @since 2025.1
	 */
    public static function locale( string $default = 'en' ): string {
        return self::getLocale( $default );
    }
}

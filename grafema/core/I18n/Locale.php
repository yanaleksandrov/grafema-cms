<?php
namespace Grafema\I18n;

/**
 * The Locale class is responsible for retrieving and storing the user's locale
 * based on the HTTP `Accept-Language` header. It provides a method to fetch the
 * locale in a standardized format, replacing underscores with hyphens.
 *
 * @package Grafema
 * @since 2025.1
 */
class Locale {

	/**
	 * Stores the locale determined from the HTTP request.
	 *
	 * @var string
	 * @since 2025.1
	 */
    private static string $locale;

	/**
	 * Get local from HTTP.
	 *
	 * @param string $default
	 * @return string
	 *
	 * @since 2025.1
	 */
	protected static function getLocale( string $default = 'en' ): string {
        if ( ! isset( self::$locale ) && function_exists( 'locale_accept_from_http' ) ) {
            self::$locale = locale_accept_from_http( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? $default );
        }
        return str_replace( '_', '-', self::$locale ?? $default );
    }

	/**
	 * Translates a given string based on the current locale. The method checks for
	 * a corresponding translation in a locale-specific JSON file. If a translation
	 * exists, it returns the translated string; otherwise, it returns the original string.
	 *
	 * The translation files are expected to be named according to the locale and
	 * follow a JSON format, where keys are original strings and values are their
	 * translations.
	 *
	 * @param string $string The string to be translated.
	 * @return string        The translated string, or the original if no translation is found.
	 *
	 * @since 2025.1
	 */
	protected static function translate( string $string ): string {
		$locale   = self::getLocale();
		$filepath = sprintf( '%sdashboard/i18n/%s.json', GRFM_PATH, $locale );
		if ( is_file( $filepath ) ) {
			$translates = file_get_contents( $filepath );
			$translates = json_decode( $translates, 1 );
			if ( isset( $translates[ $string ] ) ) {
				return $translates[ $string ];
			}
		}
		return $string;
	}
}

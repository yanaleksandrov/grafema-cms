<?php
namespace Grafema\I18n;

/**
 *
 *
 * @package Grafema
 * @since 2025.1
 */
trait Translation {

	/**
	 * Incoming translations routes list.
	 *
	 * @var array
	 * @since 2025.1
	 */
	protected static array $routes = [];

	/**
	 *
	 *
	 * @var string
	 * @since 2025.1
	 */
	protected static string $pattern = '';

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
	protected static function get( string $string ): string {
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$source    = $backtrace[1] ?? [];

		if ( $source ) {
			$sourceFile = $source['file'] ?? '';
			$segments   = array_map( fn( $key ) => basename( rtrim( $key, '/' ) ), array_keys( self::$routes ) );
			$result     = implode( '|', $segments );
			$pattern    = sprintf( '/(%s)\/([^\/]+)\/[^\/]+$/', $result );

			// check that incoming path is part of exist routes
			if ( preg_match( $pattern, $sourceFile, $matches ) ) {
				$element   = $matches[1] ?? '';
				$directory = $matches[2] ?? '';
				$filename  = sprintf( self::$pattern, self::getLocale() );

				// add routes storages
				static $override = [];
				static $routes   = [];

				// try to find main & custom translations
				foreach ( self::$routes as $route => $targetRoute ) {
					if ( ! str_starts_with( $sourceFile, $route ) ) {
						continue;
					}

					$targetRoute = rtrim( $targetRoute, DIRECTORY_SEPARATOR );
					$targetDir   = basename( $targetRoute );
					if ( $directory ) {
						$targetRoute = str_replace( ':dirname', $directory, $targetRoute );
					}

					if ( in_array( $element, [ 'plugins', 'themes' ], true ) ) {
						$targetDir = $element . DIRECTORY_SEPARATOR . str_replace( ':dirname', $directory, $targetDir );
					}

					$override[ $sourceFile ] ??= sprintf( '%s%s/%s.json', GRFM_I18N, $targetDir, self::getLocale() );
					$routes[ $sourceFile ]   ??= sprintf( '%s/%s.json', $targetRoute, $filename );
				}

				if ( isset( $override[ $sourceFile ] ) ) {
					$translation = self::gettext( $override[ $sourceFile ], $string );
					if ( $translation !== $string ) {
						return $translation;
					}
				}
				return self::gettext( $routes[ $sourceFile ] ?? '', $string );
			}
		}
		return $string;
	}

	/**
	 * Find text from file.
	 *
	 * @param string $filepath
	 * @param string $string
	 * @return string
	 */
	private static function gettext( string $filepath, string $string ): string {
		if ( file_exists( $filepath ) ) {
			$translations = json_decode( file_get_contents( $filepath ) ?? '', 1 );
			if ( isset( $translations[ $string ] ) ) {
				return $translations[ $string ];
			}
		}
		return $string;
	}
}

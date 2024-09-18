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
	 *
	 *
	 * @var array
	 * @since 2025.1
	 */
	protected static array $rules = [];

	/**
	 *
	 *
	 * @var string
	 * @since 2025.1
	 */
	protected static string $globPattern = '';

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
		$locale    = self::getLocale();
		$backtrace = debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS, 2 );
		$source    = $backtrace[1] ?? [];

		if ( $source ) {
			$sourceFile = $source['file'] ?? '';
			$pattern    = '/\/cms\/(?:plugins|core|dashboard|themes)\/([^\/]+)\/[^\/]+$/';
			if ( preg_match( $pattern, $sourceFile, $matches ) ) {
				$filepath = match ( true ) {
					str_starts_with( $sourceFile, GRFM_THEMES )    => sprintf( '%s%s/i18n/%s.json', GRFM_THEMES, $matches[1] ?? '', $locale ),
					str_starts_with( $sourceFile, GRFM_PLUGINS )   => sprintf( '%s%s/i18n/%s.json', GRFM_PLUGINS, $matches[1] ?? '', $locale ),
					str_starts_with( $sourceFile, GRFM_DASHBOARD ),
					str_starts_with( $sourceFile, GRFM_CORE )      => sprintf( '%si18n/%s.json', GRFM_DASHBOARD, $locale ),
					default                                        => null
				};

				if ( file_exists( $filepath ) ) {
					$translations = json_decode( file_get_contents( $filepath ) ?? '', 1 );
					if ( isset( $translations[ $string ] ) ) {
						return $translations[ $string ];
					}
				}
			}
		}
		return $string;
	}
}

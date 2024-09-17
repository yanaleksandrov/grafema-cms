<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\Dir\Dir;
use Grafema\File\File;
use Grafema\Sanitizer;

class Translates extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'translates';

	/**
	 * Get media files.
	 *
	 * @since 2025.1
	 */
	public static function get(): array {
		$dirpath = Sanitizer::path( GRFM_PATH . ( $_POST['project'] ?? '' ) );
		$paths   = ( new Dir( $dirpath ) )->getFiles( '*.php', 10 );

		$result = [];
		foreach ( $paths as $path ) {
			$content = ( new File( $path ) )->read();

			$pattern = '/I18n::                # Match the literal "I18n::"
                (?:_?t|_?t_attr|_?f|_?f_attr)  # Non-capturing group, matches "_t(f)" or "_t(f)_attr" functions
                \s*                            # Match any whitespace characters (optional)
                \(                             # Match the opening parenthesis
                \s*                            # Match any whitespace characters (optional)
                [\'"]                          # Match either single or double quote
                (.*?)                          # Capture the content inside the quotes (non-greedy match)
                [\'"]                          # Match either single or double quote
                \s*                            # Match any whitespace characters (optional)
                (?:,\s*[^)]+)?                 # Optionally match additional parameters before closing parenthesis
                \)                             # Match the closing parenthesis
            /ux';                              # Enable extended mode and Unicode support

			preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );

			// extracting the found strings into a separate array
			$i18nStrings = array_map( fn( $match ) => $match[1] ?? '', $matches );
			if ( $i18nStrings ) {
				$result = [ ...$result, ...$i18nStrings ];
			}

			// regular expression pattern with comments
			$pattern = '/
				I18n::                   # Match the literal "I18n::"
				_?с(?:_attr)?            # Function names matches: с, _с, с_attr, _с_attr
				\s*\(                    # Opening parenthesis with optional spaces
				[^,]+,                   # First parameter (anything up to the first comma)
				\s*([\'"])(.*?)\1        # Second parameter: string in single or double quotes
				\s*,\s*                  # Comma with optional spaces
				([\'"])(.*?)\3           # Third parameter: string in single or double quotes
			/x';

			preg_match_all( $pattern, $content, $matches );

			// extracting the found strings
			$i18nStrings = array_filter( [ $matches[2] ?? null, $matches[4] ?? null ] );
			if ( $i18nStrings ) {
				$result = [ ...$result, ...$i18nStrings ];
			}

			usort($result, fn( $a, $b ) => strcasecmp( $a, $b ) );
		}

		$result = array_unique( $result );

		return [
			'posts' => $result,
		];
	}
}

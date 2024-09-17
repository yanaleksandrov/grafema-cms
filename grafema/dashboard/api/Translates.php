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

			$pattern = '/
				I18n::           # Matches the text "I18n::"
				(?:_?t|_?t_attr) # Non-capturing group, matches "_t" or "t" or "_t_attr" or "t_attr"
				\(               # Matches the opening parenthesis
				\s*              # Matches zero or more spaces
				[\'"]            # Matches a single quote or double quote
				([^\'"]+)        # Capturing group, matches any characters except quotes
				[\'"]            # Matches the closing quote
				\s*              # Matches zero or more spaces
				\)               # Matches the closing parenthesis
				/x';             # Modifier 'x' for extended mode with whitespaces and comments

			preg_match_all( $pattern, $content, $matches, PREG_SET_ORDER );

			// Извлекаем найденные строки в отдельный массив
			$i18nStrings = array_map( fn( $match ) => $match[1] ?? '', $matches );
			if ( $i18nStrings ) {
				$result = [ ...$result, ...$i18nStrings ];
			}
		}
		echo '<pre>';
		print_r( $result );
		echo '</pre>';
		exit;
		return [
			'posts' => '$media',
		];
	}
}

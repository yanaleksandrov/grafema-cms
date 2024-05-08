<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

/**
 * @since   1.0.0
 */
class Sanitizer
{
	/**
	 * Sanitized data.
	 *
	 * @var array
	 */
	public array $data = [];

	/**
	 * Incoming fields and their values.
	 *
	 * @var array
	 */
	protected array $fields = [];

	/**
	 * Sanitizer rules list.
	 *
	 * @var array
	 */
	protected array $rules = [];

	/**
	 * List for custom rules for extend sanitizer.
	 *
	 * @var array
	 */
	protected array $extensions = [];

	/**
	 * Setup sanitizer rules.
	 *
	 * @param array $data  Data for sanitizing.
	 * @param array $rules Sanitizer rules list. Example: 'kebabcase'
	 */
	public function __construct( array $data = [], array $rules = [] )
	{
		$this->fields = $data;
		$this->rules  = $rules;

		return $this;
	}

	/**
	 * Add custom sanitizing rule or override exist.
	 *
	 * @param string    $rule     New sanitize rule name.
	 * @param callable  $callback Callback for sanitizing.
	 * @return Sanitizer
	 */
	public function extend( string $rule, callable $callback ): Sanitizer {
		if ( is_callable( $callback ) ) {
			$this->extensions[ $rule ] = $callback;
		}
		return $this;
	}

	/**
	 * Apply sanitizer.
	 *
	 * @return array
	 */
	public function apply(): array
	{
		foreach ( $this->rules as $field => $rules_list ) {
			$rules = explode( '|', $rules_list );

			// add support dot notation
			if (str_contains($field, '.')) {
				[$field, $key] = explode('.', $field, 2) + [null, null];
			}

			foreach ( $rules as $rule ) {
				[$method, $default_value] = explode(':', $rule, 2) + [null, null];

				$extension = isset( $this->extensions[$method] ) ? $this->extensions[$method] : null;
				if ( ! empty( $key ) ) {
					$value = $this->data[$field][$key] ?? ( $this->fields[$field][$key] ?? null );
				} else {
					$value = $this->data[$field] ?? ( $this->fields[$field] ?? null );
				}

				// set default value if incoming is empty & default is not empty
				if ( $value === null && ! empty( $default_value ) ) {
					$value = $default_value;

					// substring "$" at the beginning, means that the default value must be taken from another field
					if ( str_starts_with( $default_value, '$' ) ) {
						$value = $this->data[ trim( $default_value, '$' ) ] ?? '';
					}
				}

				$data = match (true) {
					is_callable( $extension )       => call_user_func( $extension, $value, $this ),
					is_callable( [$this, $method] ) => call_user_func( [$this, $method], $value ),
					default                         => null
				};

				if ( ! empty( $key ) ) {
					$this->data[$field][$key] = $data;
				} else {
					$this->data[$field] = $data;
				}
			}
			unset( $key );
		}

		return $this->data;
	}

	/**
	 * Get values list.
	 *
	 * @return array
	 */
	public function values(): array {
		return array_values( $this->apply() );
	}

	/**
	 * Checks for the presence of an element in the array.
	 *
	 * @param mixed $value Value to change
	 * @param string|null $comparison_value Value to compare
	 * @return string
	 */
	public static function exist( mixed $value, ?string $comparison_value ): string
	{
		$comparison = [];
		if ( $comparison_value ) {
			$comparison = array_map(
				'trim',
				explode( ',', $comparison_value )
			);
		}

		return in_array( (string) $value, $comparison, true ) ? (string) $value : '';
	}

	/**
	 * Leads a variable to a string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function string( mixed $value ): string
	{
		return (string) $value;
	}

	/**
	 * Leads a variable to an array.
	 *
	 * @param mixed $value Value to change
	 * @return array
	 */
	public static function array( mixed $value ): array
	{
		return is_array( $value ) ? $value : ( ! empty( $value ) ? (array) $value : [] );
	}

	/**
	 * Leads a variable to number.
	 *
	 * @param mixed $value Value to change
	 * @return int
	 */
	public static function int( mixed $value ): int
	{
		return (int) $value;
	}

	/**
	 * Leads a variable to not negative number.
	 *
	 * @param mixed $value Value to change
	 * @return int
	 */
	public static function absint( mixed $value ): int
	{
		return abs( (int) $value );
	}

	/**
	 * Leads a variable to float number.
	 *
	 * @param mixed $value Value to change
	 * @return float
	 */
	public static function float( mixed $value ): float
	{
		return (float) $value;
	}

	/**
	 * Leads a variable to boolean.
	 *
	 * @param mixed $value Value to change
	 * @return bool
	 */
	public static function bool( mixed $value ): bool
	{
		return ! in_array( $value, ['false', false, '0', 0, '', null], true );
	}

	/**
	 * Leads a variable to json string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function json( mixed $value ): string
	{
		return Json::encode( self::array( $value ) );
	}

	/**
	 * Sanitizer html.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function html( mixed $value ): string
	{
		return ( new Kses() )->apply( $value ?? '' );
	}

	/**
	 * Sanitizer attribute.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function attribute( mixed $value ): string
	{
		return trim( htmlspecialchars( (string) $value, ENT_QUOTES ) );
	}

	/**
	 * Value to price.
	 *
	 * @param mixed $value Value to change
	 * @return float
	 */
	public static function price( mixed $value ): float
	{
		$sanitized = 0;
		if ( is_scalar( $value ) ) {
			$sanitized = self::trim( $value );
			$sanitized = preg_replace( '/[^0-9.,]/', '', $sanitized );
			$sanitized = str_replace( ',', '.', $sanitized );
		}

		return floatval( $sanitized );
	}

	/**
	 * Sanitizes a string from user input or from the database.
	 *
	 * - Checks for invalid UTF-8,
	 * - Converts single `<` characters to entities
	 * - Strips all tags
	 * - Removes line breaks, tabs, and extra whitespace
	 * - Strips percent-encoded characters
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function text( mixed $value ): string
	{
		$sanitized = '';
		if ( is_scalar( $value ) ) {
		}

		return $sanitized;
	}

	/**
	 * Sanitizes a multiline string from user input or from the database.
	 *
	 * The function is like sanitize_text_field(), but preserves
	 * new lines (\n) and other whitespace, which are legitimate
	 * input in textarea elements.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function textarea( mixed $value ): string
	{
		$sanitized = '';
		if ( is_scalar( $value ) ) {
		}

		return $sanitized;
	}

	/**
	 * Clears the string to use it as a key. The keys are used as different internal IDs.
	 * Removes everything from the string except a-z0-9_- and converts the string to lowercase.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function key( mixed $value ): string
	{
		return preg_replace( '/[^a-z0-9_\-]/', '', self::lowercase( $value ) );
	}

	/**
	 * Clears the string to use it as a name attribute. Removes everything
	 * from the string except a-zA-Z0-9_[] and converts the string to lowercase.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function name( mixed $value ): string
	{
		return preg_replace( '/[^a-zA-Z0-9_\[\]]/', '', self::lowercase( $value ) );
	}

	/**
	 * Convert name attribute as "db[username]" to "db.username".
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function dot( mixed $value ): string
	{
		return trim( str_replace( [ ']', '[' ], [ '', '.' ], self::lowercase( $value ) ), '.' );
	}

	/**
	 * Strip whitespaces, tabs, NUL-byte & new line from the beginning & end of a string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function trim( mixed $value ): string
	{
		return is_array( $value ) || is_object( $value ) ? '' : trim( (string) $value );
	}

	/**
	 * To uppercase.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function uppercase( mixed $value ): string
	{
		return mb_strtoupper( self::trim( $value ) );
	}

	/**
	 * Lowercase string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function lowercase( mixed $value ): string
	{
		return mb_strtolower( self::trim( $value ) );
	}

	/**
	 * Capitalize string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function capitalize( mixed $value ): string
	{
		return ucwords( self::trim( $value ) );
	}

	/**
	 * Capitalize first letter.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function ucfirst( mixed $value ): string
	{
		return ucfirst( self::trim( $value ) );
	}

	/**
	 * Pascal case string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function pascalcase( mixed $value ): string
	{
		$value = self::trim( $value );
		$value = str_replace( ['_', '-'], ' ', $value );
		$value = ucwords( $value );
		$value = str_replace( ' ', '', $value );

		return ucfirst( $value );
	}

	/**
	 * Camel case string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function camelcase( mixed $value ): string
	{
		$value = self::trim( $value );
		$value = str_replace( ['_', '-'], ' ', $value );
		$value = ucwords( $value );
		$value = str_replace( ' ', '', $value );

		return lcfirst( $value );
	}

	/**
	 * Snake case string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function snakecase( mixed $value ): string
	{
		$value = self::trim( $value );

		// replacing spaces with underscores
		$str = preg_replace( ['/\s+/', '#-#'], '_', $value );
		// insert underscores before each capital letter
		$str = preg_replace( '/(.)(?=[A-Z])/u', '$1_', $str );

		return strtolower( $str );
	}

	/**
	 * Kebab case string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function kebabcase( mixed $value ): string
	{
		$value = self::trim( $value );

		// replacing spaces with hyphens
		$str = preg_replace( ['/\s+/', '_'], '-', $value );
		// insert underscores before each capital letter
		$str = preg_replace( '/(.)(?=[A-Z])/u', '$1-', $str );

		return strtolower( $str );
	}

	/**
	 * Flat case string.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function flatcase( mixed $value ): string
	{
		$value = self::trim( $value );

		$str = preg_replace( ['-', '_', ' '], '', $value );

		return strtolower( $str );
	}

	/**
	 * Password hash.
	 *
	 * @param  string $value
	 * @return string
	 */
	public static function hash( mixed $value ): string
	{
		return password_hash( self::trim( $value ), PASSWORD_DEFAULT );
	}

	/**
	 * Filters string for valid email characters.
	 *
	 * @param string $value
	 * @return string
	 */
	public static function email( mixed $value ): string
	{
		return strval( filter_var( self::trim( $value ), FILTER_SANITIZE_EMAIL ) );
	}

	/**
	 * Filters string for valid URL characters.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function url( mixed $value ): string
	{
		return strval( filter_var( self::trim( $value ), FILTER_SANITIZE_URL ) );
	}

	/**
	 * Filters string for valid path syntax, with optional trailing slash.
	 *
	 * @param mixed $value Value to change
	 * @return string
	 */
	public static function path( mixed $value ): string
	{
		// Remove whitespace, spaces, leading and trailing slashes
		$path = self::trim( preg_replace( '/\s+/', '', (string) $value ) );

		// Convert all invalid slashes to one single forward slash
		return strtr(
			$path,
			[
				'//'   => '/',
				'\\'   => '/',
				'\\\\' => '/',
			]
		);
	}

	/**
	 * Sanitizes an HTML classname to ensure it only contains valid characters.
	 *
	 * Strips the string down to A-Z,a-z,0-9,_,-. If this results in an empty
	 * string then it will return the alternative value supplied.
	 *
	 * @param mixed $value The classname to be sanitized
	 *
	 * @return string The sanitized value
	 */
	public static function class( mixed $value ): string
	{
		$sanitized = [];
		$classes   = explode( ' ', (string) $value );

		foreach ( $classes as $class ) {
			// Strip out any %-encoded octets.
			$class = preg_replace( '|%[a-fA-F0-9][a-fA-F0-9]|', '', $class );

			// Limit to A-Z, a-z, 0-9, '_', '-'.
			$class = preg_replace( '/[^A-Za-z0-9_-]/', '', $class );

			$sanitized[] = self::trim( $class );
		}

		return implode( ' ', array_filter( $sanitized ) );
	}

	/**
	 * Normalize EOL characters and strip duplicate whitespace.
	 *
	 * @param mixed $value Value to change
	 *
	 * @return string the normalized string
	 *
	 * @since  1.0.0
	 */
	public static function whitespace( mixed $value ): string
	{
		return preg_replace( ['/\n+/', '/[ \t]+/'], ["\n", ' '], str_replace( "\r", "\n", self::trim( $value ) ) );
	}

	/**
	 * Properly strip all HTML tags including script and style.
	 *
	 * This differs from strip_tags() because it removes the contents of
	 * the `<script>` and `<style>` tags. E.g. `strip_tags( '<script>something</script>' )`
	 * will return 'something'. `Sanitizer::tags()` will return ''
	 *
	 * @since  1.0.0
	 *
	 * @param  string $value String containing HTML tags.
	 * @return string        The processed string.
	 */
	public static function tags( string $value ): string
	{
		$value = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $value );
		$value = strip_tags( $value );
		$value = preg_replace( '/[\r\n\t ]+/', ' ', $value );

		return self::trim( $value );
	}

	/**
	 * Sanitizer a mime type.
	 *
	 * @since  1.0.0
	 *
	 * @param mixed $value Mime type.
	 * @return string      Sanitized mime type.
	 */
	public static function mime( mixed $value ): string
	{
		return preg_replace( '/[^-+*.a-zA-Z0-9\/]/', '', self::trim( $value ) );
	}

	/**
	 * Sanitizes a hex color with or without a hash.
	 *
	 * @since  1.0.0
	 *
	 * @param string $value Color in HEX format
	 * @return string|null  3 or 6 digit hex color with or without #
	 */
	public static function hex( string $value ): ?string
	{
		$value = self::trim( $value );
		if ( ! str_contains( $value, '#' ) ) {
			$value = '#' . $value;
		}

		// 3 or 6 hex digits, or the empty string.
		if ( preg_match( '|^#([A-Fa-f0-9]{3}){1,2}$|', $value ) ) {
			return $value;
		}

		return '';
	}

	/**
	 * Clearing the slug that is used as part of the url.
	 *
	 * @param string  $value Value of slug.
	 * @return string
	 */
	public static function slug( string $value ): string
	{
		$value = strip_tags( $value );
		// Preserve escaped octets.
		$value = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $value );
		// Remove percent signs that are not part of an octet.
		$value = str_replace( '%', '', $value );
		// Restore octets.
		$value = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $value );

		$value = strtolower( $value );

		// Remove HTML entities.
		$value = preg_replace( '/&.+?;/', '', $value );
		$value = str_replace( '.', '-', $value );

		$value = preg_replace( '/[^%a-z0-9 _-]/', '', $value );
		$value = preg_replace( '/\s+/', '-', $value );
		$value = preg_replace( '|-+|', '-', $value );

		return self::trim( $value );
	}

	/**
	 * Sanitize a login.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed  $value Incoming login.
	 * @return string
	 */
	public static function login( string $value ): string
	{
		$value = self::trim( $value );

		// Strip all tags.
		$value = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $value );
		$value = strip_tags( $value );
		$value = preg_replace( '/[\r\n\t ]+/', ' ', $value );

		$value = self::accents( $value );
		// Remove percent-encoded characters.
		$value = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '', $value );
		// Remove HTML entities.
		$value = preg_replace( '/&.+?;/', '', $value );

		// Consolidate contiguous whitespace.
		return preg_replace( '|\s+|', '', $value );
	}

	/**
	 * Removes accent from strings.
	 *
	 * Esc::accents("ªºÀÁÂÃÄÅÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝàáâãäåçèéêëìíîïñòóôõöøùúûüýÿØ");
	 *         echo: ooAAAAAACEEEEIIIINOOOOOUUUUYaaaaaaceeeeiiiinoooooouuuuyyO.
	 *
	 * @since  1.0.0
	 *
	 * @param  mixed  $value Incoming login.
	 * @return string
	 */
	public static function accents( string $value ): string
	{
		if ( ! preg_match( '/[\x80-\xff]/', $value ) ) {
			return $value;
		}

		// converting accents in HTML entities
		$value = htmlentities( $value, ENT_NOQUOTES, 'utf-8' );

		// replacing the HTML entities to extract the first letter
		// examples: "&ecute;" => "e", "&Ecute;" => "E", "à" => "a" ...
		$value = preg_replace(
			'#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml|caron|lig|rdf|rdm);#',
			'\1',
			$value
		);

		// replacing ligatures, e.g.: "œ" => "oe", "Æ" => "AE"
		$value = preg_replace( '#&([A-za-z]{2})(?:lig);#', '\1', $value );

		// removing the remaining bits
		return preg_replace( '#&[^;]+;#', '', $value );
	}

	/**
	 * Sanitizes a filename, replacing whitespace with dashes.
	 *
	 * Removes special characters that are illegal in filenames on certain
	 * operating systems and special characters requiring special escaping
	 * to manipulate at the command line. Replaces spaces and consecutive
	 * dashes with a single dash. Trims period, dash and underscore from beginning
	 * and end of filename. It is not guaranteed that this function will return a
	 * filename that is allowed to be uploaded.
	 *
	 * @since 1.0.0
	 *
	 * @param string $value The filename to be sanitized.
	 * @return string       The sanitized filename.
	 */
	public static function filename( string $value ): string {
		$filename  = pathinfo( $value, PATHINFO_FILENAME );
		$extension = pathinfo( $value, PATHINFO_EXTENSION );

		// remove accents
		$filename = self::accents( $filename );

		// remove not allowed symbols
		$specialChars = '"!@#$%&*()_-~+=|{[}]/?;:.,\\\'<>«»”“’`°ºª�' . chr( 0 );
		$filename     = str_replace( mb_str_split( $specialChars ), '', $filename );

		// remove not allowed symbols & letters with diacritics
		$letters      = 'ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖØÙÚÛÜüÝÞßàáâãäåæçèéêëìíîïðñòóôõöøùúûýýþÿRr';
		$replacements = 'aaaaaaaceeeeiiiidnoooooouuuuuybsaaaaaaaceeeeiiiidnoooooouuuyybyrr';
		$filename     = str_replace( mb_str_split( $letters ), mb_str_split( $replacements ), $filename );

		// remove whitespaces
		$filename = str_replace( ' ', '-', $filename );

		// remove duplicate hyphens, tabs & new lines
		$filename = preg_replace( '/[\r\n\t -]+/', '-', $filename );
		$filename = preg_replace( '/[-\s]+/', '-', $filename );
		$filename = trim( $filename, '.-_' );

		return sprintf( '%s.%s', $filename, $extension );
	}
}

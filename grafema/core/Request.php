<?php
namespace Grafema;

/**
 * The 'Request' class is a wrapper for HTTP requests.
 * It provides a collection of simple methods for easily and
 * reliably retrieving information associated with an HTTP request.
 *
 * @see https://github.com/hinchley/Request
 */
class Request {
	/**
	 * The key that indicates an 'overridden' request method.
	 * Typically used to support methods over a REST API. e.g. PUT.
	 *
	 * @var string
	 */
	const OVERRIDE = 'HTTP_X_HTTP_METHOD_OVERRIDE';

	/**
	 * An array of trusted proxy IP addresses.
	 *
	 * @var array
	 */
	protected static array $proxies = [];

	/**
	 * An array of URI resolvers.
	 *
	 * @var array
	 */
	protected static array $resolvers = [];

	/**
	 * All input data for the request. Used as a cache.
	 *
	 * @var array
	 */
	protected static array $input = [];

	/**
	 * An array of media type formats.
	 *
	 * @var array
	 */
	protected static array $formats = [
		'html' => [ 'text/html', 'application/xhtml+xml' ],
		'txt'  => [ 'text/plain' ],
		'js'   => [ 'application/javascript', 'application/x-javascript', 'text/javascript' ],
		'css'  => [ 'text/css' ],
		'json' => [ 'application/json', 'application/x-json' ],
		'xml'  => [ 'text/xml', 'application/xml', 'application/x-xml' ],
		'rdf'  => [ 'application/rdf+xml' ],
		'atom' => [ 'application/atom+xml' ],
		'rss'  => [ 'application/rss+xml' ],
	];

	/**
	 * Get the value of an item in a super global array.
	 *
	 * @param array $array   The super global array.
	 * @param string $key     The array key.
	 * @param string $default The default value.
	 * @return string|array
	 */
	protected static function lookup(array $array, string $key, string $default): array|string
	{
		return $array[$key] ?? $default;
	}

	/**
	 * Return a single array containing all input data (i.e. GET, POST, PUT and DELETE).
	 *
	 * @return array
	 */
	protected static function submitted(): array {
		if (! empty(static::$input)) {
			return static::$input;
		}

		parse_str(static::body(), $input);
		return static::$input = $_GET + $_POST + $input;
	}

	/**
	 * Check if the request method has been overridden.
	 *
	 * @return bool
	 */
	protected static function overridden(): bool
	{
		return isset($_POST[static::OVERRIDE]) || isset($_SERVER[static::OVERRIDE]);
	}

	/**
	 * Get the raw body of a request.
	 *
	 * @param string|null $default The default value.
	 * @return string|null
	 */
	public static function body(string $default = null): ?string
	{
		return file_get_contents('php://input') ?: $default;
	}

	/**
	 * Get the value of an item in the $_GET array.
	 *
	 * <code>
	 *   // Get the username variable from the $_GET array.
	 *   $username = Request::get('username');
	 *
	 *   // Return a default value if the requested item is undefined.
	 *   $username = Request::get('username', 'Fred Nurk');
	 *
	 *   // Return all input data from the $_GET array.
	 *   $input = Request::get();
	 * </code>
	 *
	 * @param string|null $key The array key.
	 * @param string|null $default The default value.
	 * @return string|array
	 */
	public static function get(?string $key = null, ?string $default = null): string|array {
		return static::lookup($_GET, $key, $default);
	}

	/**
	 * Get the value of an item in the $_POST array.
	 *
	 * @param string|null $key     The array key.
	 * @param string|null $default The default value.
	 * @return string          null if key undefined.
	 */
	public static function post(?string $key = null, ?string $default = null) {
		return static::lookup($_POST, $key, $default);
	}

	/**
	 * Get the value of an item that was submitted via the PUT or
	 * DELETE methods.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	protected static function stream($key, $default) {
		if (Request::overridden())
			return static::lookup($_POST, $key, $default);

		parse_str(file_get_contents('php://input'), $input);
		return static::lookup($input, $key, $default);
	}

	/**
	 * Get the value of an item that was submitted via the PUT
	 * method (either spoofed or via REST).
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function put($key = null, $default = null) {
		return static::method() === 'PUT' ? static::stream($key, $default) : $default;
	}

	/**
	 * Get the value of an item that was submitted via the DELETE
	 * method (either spoofed or via REST).
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function delete($key = null, $default = null) {
		return static::method() === 'DELETE' ? static::stream($key, $default) : $default;
	}

	/**
	 * Get the value of an item in the $_FILES array.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function files($key = null, $default = null) {
		return static::lookup($_FILES, $key, $default);
	}

	/**
	 * Get the value of an item in the $_SESSION array.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function session($key = null, $default = null) {
		return static::lookup($_SESSION, $key, $default);
	}

	/**
	 * Get the value of an item in the $_COOKIE array.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function cookie($key = null, $default = null) {
		return static::lookup($_COOKIE, $key, $default);
	}

	/**
	 * Get the value of an item in the $_ENV array.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function env($key = null, $default = null) {
		return static::lookup($_ENV, $key, $default);
	}

	/**
	 * Get the value of an item in the $_SERVER array.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function server($key = null, $default = null) {
		return static::lookup($_SERVER, $key, $default);
	}

	/**
	 * Get the value of an item from the input data submitted via GET, POST, PUT or DELETE.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function input($key = null, $default = null) {
		return static::lookup(static::submitted(), $key, $default);
	}

	/**
	 * Get the value of an item from the input data submitted via GET, POST, PUT, DELETE or FILES.
	 *
	 * @param  string $key     The array key.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function all($key = null, $default = null) {
		return array_merge(static::input(), static::files());
	}

	/**
	 * Get a subset of items from the input data.
	 *
	 * <code>
	 *   // Only get the email variable from the input data.
	 *   $email = Request::only('email');
	 *
	 *   // Only get the username and email from the input data.
	 *   $input = Request::only(array('username', 'email'));
	 * </code>
	 *
	 * @param  array $keys The keys to select from the input.
	 * @return array
	 */
	public static function only(array $keys) {
		return array_intersect_key( static::input(), array_flip($keys) );
	}

	/**
	 * Get all input data except for a specified item or array
	 * of items.
	 *
	 * <code>
	 *   // Get all input data except for username.
	 *   $input = Request::except('username');
	 *
	 *   // Get all input data except for username and email.
	 *   $input = Request::except(array('username', 'email'));
	 * </code>
	 *
	 * @param  array $keys The keys to ignore from the input.
	 * @return array
	 */
	public static function except(array$keys) {
		return array_diff_key( static::input(), array_flip($keys) );
	}

	/**
	 * Check if the input data contains an item, or all of the
	 * specified array of items.
	 *
	 * Will return false if any of the input items is an empty string.
	 *
	 * <code>
	 *   // Has `id` been submitted?
	 *   if (Request::has('id')) { echo 'The `id` exists.'; }
	 *
	 *   // Have `id` and `name` both been submitted?
	 *   if (Request::has(array('id', 'name'))) { // do stuff }
	 * </code>
	 *
	 * @param  mixed $keys The input data key, or an array of keys.
	 * @return bool
	 */
	public static function has(array $keys) {
		foreach ($keys as $key) {
			if (trim(static::input($key)) == '') return false;
		}
		return true;
	}

	/**
	 * Get the protocol of the request. e.g. HTTP/1.1
	 *
	 * Defaults to HTTP/1.1.
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function protocol($default = 'HTTP/1.1') {
		return static::server('SERVER_PROTOCOL', $default);
	}

	/**
	 * Get the request scheme. i.e. http or https.
	 *
	 * If the method is called with true, the scheme will return with a :// suffix.
	 *
	 * @param  bool   $decorated Add :// suffix.
	 * @return string
	 */
	public static function scheme(bool $decorated = false): string {
		$scheme = static::secure() ? 'https' : 'http';
		return $decorated ? "$scheme://" : $scheme;
	}

	/**
	 * Check if the request was made over HTTPS.
	 *
	 * @return bool
	 */
	public static function secure() {
		if (strtoupper(static::server('HTTPS')) == 'ON') {
			return true;
		}

		if (!static::entrusted()) {
			return false;
		}

		return (strtoupper(static::server('SSL_HTTPS')) == 'ON' || strtoupper(static::server('X_FORWARDED_PROTO')) == 'HTTPS');
	}

	/**
	 * Get the request method. e.g. GET, POST.
	 *
	 * This method can be overridden to support non-browser request
	 * methods. e.g. PUT, DELETE.
	 *
	 * @return string
	 */
	public static function method() {
		return strtoupper(static::overridden() ? ($_POST[static::OVERRIDE] ?? $_SERVER[static::OVERRIDE]) : $_SERVER['REQUEST_METHOD']);
	}

	/**
	 * Check if the request method is safe. i.e. GET or HEAD.
	 *
	 * @return bool
	 */
	public static function safe() {
		return in_array(static::method(), [ 'GET', 'HEAD' ]);
	}

	/**
	 * Check if the request is an AJAX request.
	 *
	 * @return bool
	 */
	public static function ajax():bool {
		return strtoupper($_SERVER['HTTP_X_REQUESTED_WITH'] ?? '') === 'XMLHTTPREQUEST';
	}

	/**
	 * Get the address of the request's referrer.
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function referrer($default = null) {
		return static::server('HTTP_REFERRER', $default);
	}

	/**
	 * Override the list of default URI resolvers.
	 *
	 * Elements of the resolvers array are keys in the $_SERVER array
	 * with optional 'modifier' functions to tweak the returned value.
	 *
	 * @param  array $resolvers Priority ordered list of URI resolvers.
	 * @return array
	 */
	public static function resolvers(array $resolvers = []) {
		if ($resolvers || empty(static::$resolvers)) {
			static::$resolvers = $resolvers +
				[
					'PATH_INFO',
					'REQUEST_URI' => fn($uri) => parse_url($uri, PHP_URL_PATH),
					'PHP_SELF',
					'REDIRECT_URL'
				];
		}

		return static::$resolvers;
	}

	/**
	 * Get the requested URL. e.g. http://a.com/bar?q=foo
	 *
	 * @return string
	 */
	public static function url(): string {
		return static::scheme(true).static::host().static::port(true).static::uri().static::query(true);
	}

	/**
	 * Get the request URI. e.g. /blog/item/10
	 *
	 * Excludes query strings.
	 *
	 * @return string
	 */
	public static function uri() {
		foreach (static::resolvers() as $key => $resolver) {
			$key = is_numeric($key) ? $resolver : $key;
			if (isset($_SERVER[$key])) {
				if (is_callable($resolver)) {
					$uri = $resolver($_SERVER[$key]);
					if ($uri !== false) return $uri;
				} else {
					return $_SERVER[$key];
				}
			}
		}
	}

	/**
	 * Get the request query string. e.g. q=search&foo=bar
	 *
	 * By default, the question mark is excluded. To include the
	 * question mark, call the method with true.
	 *
	 * @param  bool   $decorated Add ? prefix.
	 * @return string
	 */
	public static function query($decorated = false) {
		if (count($_GET)) {
			$query = http_build_query($_GET);
			return $decorated ? "?$query" : $query;
		}
	}

	/**
	 * Get the URI segments of the request.
	 *
	 * @param  array $default The default value.
	 * @return array
	 */
	public static function segments($default = []) {
		return explode('/', trim(static::uri() ?: $default, '/'));
	}

	/**
	 * Get a specific URI segment of the request.
	 *
	 * Use a negative index to retrieve segments in reverse order.
	 *
	 * @param  int    $index   A one-based segment index.
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function segment($index, $default = null) {
		$segments = static::segments();

		if ($index < 0) {
			$index *= -1;
			$segments = array_reverse($segments);
		}

		return static::lookup($segments, $index - 1, $default);
	}

	/**
	 * Get an ordered array of values from an HTTP accept header.
	 *
	 * @param  string $terms An HTTP accept header.
	 * @param  string $regex A regex for parsing the header.
	 * @return array
	 */
	protected static function parse($terms, $regex) {
		$result = [];

		foreach (array_reverse(explode(',', $terms)) as $part) {
			if (preg_match("/{$regex}/", $part, $m)) {
				$quality = $m['quality'] ?? 1;
				$result[$m['term']] = $quality;
			}
		}

		arsort($result);
		return array_keys($result);
	}

	/**
	 * Associate a format with a media type.
	 *
	 * @param  string $format      The format.
	 * @param  string|array $types The media type.
	 * @return void
	 */
	public static function format($format, $types) {
		static::$formats[$format] = is_array($types) ? $types : array($types);
	}

	/**
	 * Get the language preferred by the client.
	 *
	 * Defaults to 'en'.
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function language($default = null) {
		return static::lookup(static::languages(), 0, $default);
	}

	/**
	 * Get an ordered array of the languages preferred by the client.
	 *
	 * @return array
	 */
	public static function languages() {
		return static::parse(
			static::server('HTTP_ACCEPT_LANGUAGE', 'en'),
			'(?P<term>[\w\-]+)+(?:;q=(?P<quality>[0-9]+\.[0-9]+))?'
		);
	}

	/**
	 * Format a media type.
	 *
	 * @param  string $default The media type.
	 * @param  bool   $strict  Return the raw media type.
	 * @return string
	 */
	protected static function media($type, $strict = false) {
		if ($strict) return $type;

		$type = preg_split('/\s*;\s*/', $type)[0];
		foreach (static::$formats as $format => $types) {
			if (in_array($type, (array) $types)) return $format;
		}

		return $type;
	}

	/**
	 * Get the media type of the body of a request.
	 *
	 * Defaults to 'application/x-www-form-urlencoded'.
	 *
	 * @param  string $default The default value.
	 * @param  bool   $strict  Return the raw media type.
	 * @return string
	 */
	public static function type($default = null, $strict = false) {
		$type = static::server('HTTP_CONTENT_TYPE',
			$default ?: 'application/x-www-form-urlencoded');
		return static::media($type, $strict);
	}

	/**
	 * Get the media type preferred by the client.
	 *
	 * Defaults to 'html'.
	 *
	 * @param  string $default The default value.
	 * @param  bool   $strict  Return the raw media type.
	 * @return string
	 */
	public static function accept($default = null, $strict = false) {
		return static::media(static::lookup(static::accepts(), 0, $default), $strict);
	}

	/**
	 * Get an ordered array of the media types preferred by the client.
	 *
	 * @return array
	 */
	public static function accepts() {
		return static::parse(
			static::server('HTTP_ACCEPT', 'text/html'),
			'(?P<term>[\w\-\+\/\*]+)+(?:;q=(?P<quality>[0-9]+\.[0-9]+))?'
		);
	}

	/**
	 * Get the media type preferred by the client.
	 *
	 * Defaults to 'utf-8'.
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function charset($default = null) {
		return static::lookup(static::charsets(), 0, $default);
	}

	/**
	 * Get an ordered array of charsets preferred by the client.
	 *
	 * @return array
	 */
	public static function charsets() {
		return static::parse(
			static::server('HTTP_ACCEPT_CHARSET', 'utf-8'),
			'(?P<term>[\w\-\*]+)+(?:;q=(?P<quality>[0-9]+\.[0-9]+))?'
		);
	}

	/**
	 * Get the user agent. e.g. Mozilla/5.0 (Macintosh; ...)
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function agent($default = null) {
		return static::server('HTTP_USER_AGENT', $default);
	}

	/**
	 * Set one or more trusted proxy servers.
	 *
	 * By default, all proxy servers are trusted. Use this method
	 * to only trust a limited set of proxy servers when requesting
	 * the client IP address.
	 *
	 * This method is not cumulative.
	 *
	 * @param  mixed $proxies The IP address of a trusted proxy,
	 *         or an array of trusted proxies.
	 * @return void
	 */
	public static function proxies(mixed $proxies): void {
		static::$proxies = (array) $proxies;
	}

	/**
	 * Check if all proxy servers are trusted, or if this request has
	 * been specifically sent via a trusted proxy server.
	 *
	 * @return bool
	 */
	public static function entrusted() {
		return (empty(static::$proxies) || isset($_SERVER['REMOTE_ADDR'])
			&& in_array($_SERVER['REMOTE_ADDR'], static::$proxies));
	}

	/**
	 * Resolve the name of the web server.
	 *
	 * The resolution order is the 'host' header of the request,
	 * then the 'server name' directive, then the server IP address.
	 *
	 * The port number, if present, is stripped.
	 *
	 * @param  string $default The default value.
	 * @return string
	 */
	public static function host($default = null) {
		$keys = array('HTTP_HOST', 'SERVER_NAME', 'SERVER_ADDR');

		if (static::entrusted() &&
			$host = static::server('X_FORWARDED_HOST')) {
			$host = explode(',', $host);
			$host = trim($host[count($host) - 1]);
		} else {
			foreach($keys as $key) {
				if (isset($_SERVER[$key])) {
					$host = $_SERVER[$key];
					break;
				}
			}
		}

		return isset($host) ? preg_replace('/:\d+$/', '', $host) : $default;
	}

	/**
	 * Get the client IP address.
	 *
	 * By default, HTTP_CLIENT_IP is trusted. Call the method
	 * with false if you do not trust this header.
	 *
	 * If HTTP_CLIENT_IP is invalid or excluded, either a valid
	 * IP address obtained via a trusted proxy server or REMOTE_ADDR
	 * will be returned.
	 *
	 * Ignores invalid, private, and reserved IP addresses.
	 *
	 * Returns 0.0.0.0 if a valid IP address cannot be obtained.
	 *
	 * @param  bool $trusted Trust an IP address set by the client
	 *         via HTTP_CLIENT_IP.
	 * @return string
	 */
	public static function ip($trusted = true) {
		$keys = array(
			'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
			'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'
		);

		$ips = [];

		if ($trusted && isset($_SERVER['HTTP_CLIENT_IP']))
			$ips[] = $_SERVER['HTTP_CLIENT_IP'];

		foreach ($keys as $key) {
			if (isset($_SERVER[$key])) {
				if (static::entrusted()) {
					$parts = explode(',', $_SERVER[$key]);
					$ips[] = trim($parts[count($parts) - 1]);
				}
			}
		}

		foreach ($ips as $ip) {
			if (filter_var($ip, FILTER_VALIDATE_IP,
				FILTER_FLAG_IPV4 || FILTER_FLAG_IPV6 ||
				FILTER_FLAG_NO_PRIV_RANGE || FILTER_FLAG_NO_RES_RANGE)) {
				return $ip;
			}
		}

		return static::server('REMOTE_ADDR', '0.0.0.0');
	}

	/**
	 * Get the port number of the request. e.g. 80
	 *
	 * If the method is called with true, the port number will
	 * be omitted if 80 or 443, and otherwise prefixed with a colon.
	 *
	 * Defaults to port 80 if SERVER_PORT is not defined.
	 *
	 * @param bool $decorated Prefix with :.
	 * @return string
	 */
	public static function port(bool $decorated = false): string {
		$port = static::entrusted() ? static::server('X_FORWARDED_PORT') : null;

		$port = $port ?: static::server('SERVER_PORT');

		return $decorated ? ( in_array($port, [ 80, 443 ], true) ? '' : ":$port" ) : $port;
	}
}
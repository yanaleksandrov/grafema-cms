<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

use Grafema\Csrf\Csrf;
use Grafema\Csrf\Exceptions\InvalidCsrfTokenException;
use Grafema\Csrf\Providers\NativeHttpOnlyCookieProvider;

/**
 * @since 2025.1
 */
final class Api
{
	/**
	 * @var array
	 */
	public static array $resources = [];

	/**
	 * Register new API.
	 *
	 * TODO: в качестве переменной $path добавить возможность указывать путь не только к папке, но и к файлу.
	 * Это позволит при необъодимости подключать эндпоинты выборочно, а не пакетно.
	 *
	 * @param string $path Path to controls with endpoints.
	 * @param string $root Root of the API.
	 */
	public static function create( string $path, string $root ): void
	{
		self::scan( $path );

		$route = new Route();
		$route->mount( $root, function () use ( $route ) {
			$route->get( '/(.*)', fn ( $slug ) => self::run( $slug ) );
			$route->post( '/(.*)', fn ( $slug ) => self::run( $slug ) );
		} );
		$route->run();
	}

	/**
	 */
	private static function run( $slug ): void
	{
		[$endpoint, $method] = explode( '/', $slug, 2 ) + [null, null];

		$resource = self::$resources[$endpoint] ?? '';
		if ( empty( $resource ) ) {
			// TODO: return error
		}

		$method   = Sanitizer::camelcase( $method );
		$filepath = Sanitizer::path( $resource['filepath'] ?? '' );
		$class    = Sanitizer::pascalcase( $resource['class'] ?? '' );

		if ( file_exists( $filepath ) ) {
			require_once $filepath;
		}

		$csrf = new Csrf( new NativeHttpOnlyCookieProvider() );
		try {
			$csrf->check( 'token', $_COOKIE['grafema_token'] ?? '' );
		} catch ( InvalidCsrfTokenException $e ) {
			$data = new Error( 'api-no-route', I18n::_t( 'Ajax queries not allows without CSRF token!' ) );
		}

		if ( empty( $data ) ) {
			$csrf->generate( 'token' );
			try {
				$reflector   = new \ReflectionClass( $class );
				$classMethod = $reflector->getMethod( $method );
				$data        = $classMethod->isStatic() ? $class::$method() : $class->{$method}();
			} catch ( \ReflectionException $e ) {
				$data = new Error( 'api-no-route', I18n::_t( 'No route was found matching the URL and request method.' ) );
			}

			/**
			 * Interceptor for overriding the server response.
			 *
			 * @since 2025.1
			 */
			$data = Hook::apply( 'grafema_api_response', $data, $slug );
		}

		$data = Json::encode(
			[
				'status'    => 200,
				'benchmark' => Debug::timer( 'getall' ),
				'memory'    => Debug::memory_peak(),
				'data'      => $data instanceof Error ? [] : $data,
				'errors'    => $data instanceof Error ? Error::get() : [],
			], true, true
		);

		header( 'Content-Type: application/json; charset=utf-8' );
		exit( $data );
	}

	/**
	 * Extract all implements on Grafema\Api\Handler.
	 *
	 * Adds a plugin to the list of registered plugins.
	 *
	 * @param string $path Path to directory with API controllers
	 */
	private static function scan( string $path ): void
	{
		$files = array_diff( scandir( $path ), ['.', '..'] );

		foreach ( $files as $file ) {
			$filepath = $path . '/' . $file;

			if ( file_exists( $filepath ) && pathinfo( $file, PATHINFO_EXTENSION ) === 'php' ) {
				$content = file_get_contents( $filepath );

				if ( preg_match( '/namespace\s+([^\s;]+)/', $content, $matches ) ) {
					$namespace = $matches[1] ?? '';
				}

				if ( preg_match( '/class\s+(\w+)\s+extends\s+\\\Grafema\\\Api\\\Handler/', $content, $match ) ) {
					$class = $match[1] ?? '';
				}

				if ( preg_match( '/\$endpoint\s*=\s*\'([^\']+)\'/', $content, $match ) ) {
					$endpoint = $match[1] ?? '';
				}

				if ( empty( $class ) || empty( $endpoint ) ) {
					continue;
				}

				self::$resources[$endpoint] = [
					'namespace' => $namespace ?? '',
					'class'     => sprintf( '%s%s', ! empty( $namespace ) ? $namespace . '\\' : '', $class ),
					'filepath'  => $filepath,
				];
			} elseif ( is_dir( $filepath ) ) {
				self::scan( $filepath );
			}
		}
	}
}

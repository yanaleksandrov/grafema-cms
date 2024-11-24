<?php
namespace Grafema;

/**
 * Class Route.
 */
final class RouteNew extends Router\Singleton {

	/**
	 * Store a before middleware route and a handling function to be executed when accessed using one of the specified methods.
	 *
	 * @param string $methods Allowed methods, | delimited
	 * @param string $pattern A route pattern such as /about/system
	 * @param object|callable $fn The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function before( string $methods, string $pattern, object|callable $fn ) {
		self::router()->before( $methods, $pattern, $fn );
	}

	/**
	 * Store a route and a handling function to be executed when accessed using one of the specified methods.
	 *
	 * @param string $methods Allowed methods, | delimited
	 * @param string $pattern A route pattern such as /about/system
	 * @param object|callable $fn The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function match( string $methods, string $pattern, callable|object $fn ) {
		self::router()->match( $methods, $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using any method.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function any( string $pattern, callable|object $fn ) {
		self::router()->match( 'GET|POST|PUT|DELETE|OPTIONS|PATCH|HEAD', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using GET.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function get( string $pattern, callable|object $fn ) {
		self::router()->match( 'GET', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using POST.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function post( string $pattern, callable|object $fn ) {
		self::router()->match( 'POST', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using PATCH.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function patch( string $pattern, callable|object $fn ) {
		self::router()->match( 'PATCH', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using DELETE.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function delete( string $pattern, callable|object $fn ) {
		self::router()->match( 'DELETE', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using PUT.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function put( string $pattern, callable|object $fn ) {
		self::router()->match( 'PUT', $pattern, $fn );
	}

	/**
	 * Shorthand for a route accessed using OPTIONS.
	 *
	 * @param string          $pattern A route pattern such as /about/system
	 * @param object|callable $fn      The handling function to be executed
	 *
	 * @since 2025.1
	 */
	public static function options( string $pattern, callable|object $fn ) {
		self::router()->match( 'OPTIONS', $pattern, $fn );
	}

	/**
	 * Mounts a collection of callbacks onto a base route.
	 *
	 * @param string   $baseRoute The route sub pattern to mount the callbacks on
	 * @param callable $fn        The callback method
	 *
	 * @since 2025.1
	 */
	public static function middleware( string $baseRoute, callable $fn ) {
		self::router()->mount( $baseRoute, $fn );
	}

	/**
	 * Execute the router: Loop all defined before middlewares and routes, and execute the handling function if a match was found.
	 *
	 * @param object|callable $callback Function to be executed after a matching route was handled (= after router middleware)
	 * @return bool
	 *
	 * @since 2025.1
	 */
	public static function run( $callback = null ): bool {
		return self::router()->run( $callback );
	}

	/**
	 * Set the 404 handling function.
	 *
	 * @param object|callable|string $match_fn The function to be executed
	 * @param object|callable|null   $fn       The function to be executed
	 *
	 * @since 2025.1
	 */
	public static function set404( object|callable|string $match_fn, object|callable $fn = null ) {
		self::router()->set404( $match_fn, $fn );
	}
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Plugins;

use Grafema\I18n;
use Grafema\Error;
use Grafema\Sanitizer;

/**
 * The main class for managing extensions.
 *
 * This class provides functionality for registering, activating, deactivating, installing, and uninstalling extensions.
 *
 * @since 2025.1
 */
class Manager {

	/**
	 * Full list of existing extensions.
	 *
	 * @since 2025.1
	 */
	public static array $collection = [];

	/**
	 * Contains registered instances of plugin classes.
	 *
	 * @since 2025.1
	 */
	public static array $instances = [];

	/**
	 * Constructor for the Manager class.
	 *
	 * @param callable $callback a callback function used for registering extensions
	 * @since 2025.1
	 */
	public function __construct( callable $callback )
	{
		call_user_func( $callback, $this );
	}

	/**
	 * Extract all implements on Plugins\Skeleton & add a plugin to the list of registered extensions.
	 *
	 * @param array $paths Array of paths to the start files of extensions
	 * @since 2025.1
	 */
	public function register( array $paths ): void
	{
		foreach ( $paths as $path ) {
			if ( ! file_exists( $path ) ) {
				continue;
			}

			$code = file_get_contents( $path );

			// try to find the implementation of the new plugin
			// preg_match( '/class\s+(\w+)(?:\s+extends\s+(\w+))?.*implements\s+Plugins\\\Skeleton/', $code, $classes );
			// TODO: add support final & extends
			preg_match( '/class\s+(\w+)\s+implements\s+Plugins\\\\Skeleton/', $code, $classes );
			preg_match( '/namespace\s+([^;]+);/', $code, $namespaces );

			// TODO: add error if plugin with same name is exist
			$namespace = Sanitizer::pascalcase( $namespaces[1] ?? '' );
			$class     = Sanitizer::pascalcase( $namespace ? $namespace : ( $classes[1] ?? '' ) );
			if (  ! $namespace && ! $class ) {
				continue;
			}

			require_once $path;

			// TODO:: require & call class just on launch
			// TODO:: add namespaces support for plugins & themes
			try {
				$plugin = new $class();
				if ( $plugin instanceof Skeleton ) {
					$data    = $plugin->manifest();
					$missing = array_diff( ['name', 'description', 'version', 'php', 'mysql'], array_keys( $data ) );
					if ( ! empty( $missing ) ) {
						new Error( 'manager-register', I18n::_f( 'Plugin "%s" parameters "%s" are required', $class, implode( ' ', $missing ) ) );

						continue;
					}

					self::$collection[$class] = $path;
					self::$instances[$class]  = $plugin;
				}
			} catch ( \Throwable $e ) {
//				echo '<pre>';
//				print_r( $e );
//				echo '</pre>';
			}
		}
	}

	/**
	 * Launch all registered extensions.
	 *
	 * @since 2025.1
	 */
	public function launch(): void
	{
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {
				$extension->launch();
			}
		}
	}

	/**
	 * Activate all registered extensions.
	 *
	 * Calls the `activate()` method on each registered plugin, allowing them to perform necessary initialization tasks.
	 *
	 * @since 2025.1
	 */
	public function activate(): void
	{
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {
				$extension->activate();
			}
		}
	}

	/**
	 * Deactivate all registered extensions.
	 *
	 * Calls the `deactivate()` method on each registered plugin, allowing them to clean up resources or undo changes made during activation.
	 *
	 * @since 2025.1
	 */
	public function deactivate(): void
	{
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {
				$extension->deactivate();
			}
		}
	}

	/**
	 * Install all registered extensions.
	 *
	 * Calls the `install()` method on each registered plugin, allowing them to perform installation tasks.
	 *
	 * @since 2025.1
	 */
	public function install(): void
	{
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {
				$extension->install();
			}
		}
	}

	/**
	 * Uninstall all registered extensions.
	 *
	 * Calls the `uninstall()` method on each registered plugin, allowing them to clean up resources or remove associated assets.
	 *
	 * @since 2025.1
	 */
	public function uninstall(): void
	{
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {
				$extension->uninstall();
			}
		}
	}

	/**
	 * Checks the plugins directory and retrieve all plugin files with plugin data.
	 *
	 * Grafema only supports plugin files in the base plugins directory
	 * (plugins) and in one directory above the plugins directory
	 * (plugins/my-plugin). The file it looks for has the plugin data
	 * and must be found in those two locations. It is recommended to keep your
	 * plugin files in their own directories.
	 *
	 * @since 2025.1
	 *
	 * @return array[] Array of arrays of plugin data, keyed by plugin file name.
	 */
	public function get(): array {
		echo '<pre>';
		print_r( self::$instances );
		echo '</pre>';
		foreach ( self::$instances as $extension ) {
			if ( $extension instanceof Skeleton ) {

			}
		}

		return [];
	}
}

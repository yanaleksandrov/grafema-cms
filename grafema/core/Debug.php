<?php
namespace Grafema;

/**
 * A utility class for debugging PHP applications. Provides methods to manage error reporting,
 * measure execution time, memory usage, and generate detailed error outputs. This class is
 * designed to be used in a development environment to facilitate troubleshooting and performance
 * analysis.
 *
 * @package Grafema
 * @final
 */
final class Debug {

	public static function launch(): void
	{
		if ( defined( 'GRFM_DEBUG' ) && GRFM_DEBUG ) {
			ini_set( 'error_reporting', E_ALL );
			ini_set( 'display_errors', 1 );
			ini_set( 'display_startup_errors', 1 );
			error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );
		}
	}

	/**
	 * Считает время выполнения PHP кода (в секундах).
	 *
	 * @param string $phase run             - включает подсчет: первый раз или после паузы (по умолчанию).
	 *                      start           - очищает все кэши и включает подсчет - clear & run.
	 *                      get             - получает разницу, между предыдущим вызовом функции.
	 *                      getall|end|stop - получает разницу, между первым вызовом функции (run).
	 *                      pause           - временная остановка подсчета. exec_time() для продолжения.
	 *                      clear           - полностью очищает результат. exec_time() для начала нового подсчета.
	 * @param int $round    до скольки знаков после запятой округлять результат
	 * @param string $txt
	 * @return float|int|string Example 0.03654
	 *
	 * @ver: 3.4.3
	 */
	public static function timer( string $phase = 'run', int $round = 2, string $txt = 's' ): float|int|string
	{
		static $prev_time, $collect;

		if ( $phase === 'start' ) {
			$collect = $prev_time = 0;
		} elseif ( $phase === 'clear' ) {
			return $collect = $prev_time = 0;
		}

		[$usec, $sec] = explode( ' ', microtime() );
		$mctime       = bcadd( $usec, $sec, 8 );

		if ( $prev_time ) {
			$exectime = $mctime - $prev_time; // bcsub( $mctime, $prev_time, 8 );
			$collect += $exectime;            // bcadd( $collect, $exectime, 8 );
		}
		$prev_time = $mctime;

		return match ( $phase ) {
			'getall',
			'end',
			'stop'    => round( $collect, $round ) . $txt,
			'get'     => round( $exectime, $round ) . $txt,
			default   => 0,
		};
	}
	
	/**
	 * Возвращает количество памяти в байтах, которое было выделено PHP-скрипту.
	 *
	 * @param bool $real_usage
	 * @return string
	 */
	public static function memory( bool $real_usage = false ): string
	{
		return self::convert( memory_get_usage( $real_usage ) );
	}

	/**
	 * Возвращает пиковое значение объема памяти, выделенное PHP.
	 *
	 * @param bool $real_usage
	 * @return string
	 */
	public static function memory_peak( bool $real_usage = false ): string
	{
		return self::convert( memory_get_peak_usage( $real_usage ) );
	}

	/**
	 * Возвращает процент используемой памяти от общего количества выделенной.
	 *
	 * @param bool $relative
	 * @return false|string
	 */
	public static function memory_limit( bool $relative = true ): bool|string
	{
		if ( $relative ) {
			return round( memory_get_usage() / ( (int) ini_get( 'memory_limit' ) * 1024 * 1024 ) * 100, 2 ) . '%';
		}

		return ini_get( 'memory_limit' );
	}

	/**
	 * Converts bytes.
	 *
	 * @param int $size
	 * @return string
	 */
	public static function convert( int $size ): string
	{
		$i = floor( log( $size, 1024 ) );

		return round( $size / pow( 1024, $i ), 1 ) . ['b', 'kb', 'mb', 'gb'][$i];
	}

	/**
	 * Получает конфигурационную информацию кеша.
	 *
	 * @return mixed|void
	 */
	public static function opcache( string $field )
	{
		$directives = opcache_get_configuration();
		if ( isset( $directives['directives']['opcache.' . $field] ) ) {
			return $directives['directives']['opcache.' . $field];
		}
	}

	/**
	 * Generates an error ID to use when logging errors.
	 * It is formed as a string that separates the name of the class, method, and line in the file with a slash.
	 * This makes it convenient to search for the source of errors for subsequent debugging.
	 *
	 * @since 2025.1
	 */
	public static function get_backtrace(): string
	{
		$backtrace = self::backtrace();
		if ( isset( $backtrace[1]['file'], $backtrace[1]['line'] ) ) {
			return str_replace( GRFM_PATH, '', $backtrace[1]['file'] ) . '/' . $backtrace[1]['line'];
		}

		return '';
	}

	/**
	 * TODO: output some debug code only for specified user.
	 *
	 * @param int $user_id User ID
	 * @param ?callable $function
	 */
	public static function only( int $user_id, ?callable $function ): void
	{
		echo '<pre>';
		$function();
		echo '</pre>';
	}

	/**
	 * Generates a backtrace.
	 *
	 * @since 2025.1
	 */
	private static function backtrace(): array
	{
		return debug_backtrace( DEBUG_BACKTRACE_IGNORE_ARGS );
	}

	/**
	 * A readable display of a php fatal error.
	 *
	 * @param mixed $error
	 * @return string
	 * @since 2025.1
	 */
	public static function print( mixed $error ): string {
		$msg = $error->getMessage();
		$msg = preg_replace( '/[a-z0-9_\-]*\.php/i','$1<u>$0</u>', $msg );
		$msg = preg_replace( '/[0-9]/i','$1<em>$0</em>', $msg );
		$msg = preg_replace( '/[\(\)#\[\]\':]/i','$1<ss>$0</ss>', $msg );

		ob_start();
		?>
		<pre>
			<style>
				h3 {
					color: #c40000;
				}
				u {
					color: #a5751f;
					text-decoration:none;
				}
                em {
	                color: #c333ac;
	                font-style:normal;
                }
			</style>
			<h3><?php I18n::t( 'Fatal Error' ); ?></h3>
			<?php printf( 'Find on line %d in file %s', $error->getLine(), $error->getFile() ); ?><br>
			<strong><?php I18n::t( 'Message text:' ); ?></strong><br>
			<?php echo nl2br( $msg ); ?>
			<?php
			switch ( true ) {
				case $error instanceof \TypeError:
					$last = current( $error->getTrace() );
					$args = $last['args'] ?? [];
					if ( $args ) {
						foreach ( $args as $key => $arg ) {
							I18n::f( '<br><br><strong>Argument #%s with "%s" type and value:</strong><br>', $key, gettype( $arg ) );
							if ( is_scalar( $arg ) ) {
								printf( '"%s"', $arg );
							} else {
								print_r( $arg );
							}
						}
					}
					?>
					<?php
					break;
			}
			?>
		</pre>
		<?php
		return ob_get_clean();
	}
}

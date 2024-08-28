<?php
namespace Grafema;

/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE.md
 *
 * Functions:
 * _t|t     translate & return|echo
 * TODO: в форматировании добавить поддержку плейсхолдеров к обозначениям типа %s, %d, %f
 * TODO: к заполнителям добавить поддержку регистра, например плейсхолдер :NAME преобразует строку в верхний регистр
 * _f|f     format & return|echo
 * _c|c     conditional & return|echo
 *
 * _t_attr|t_attr   sanitize attribute & return|echo
 * TODO: next functions
 * _f_attr|f_attr   format & sanitize attribute & return|echo
 * _c_attr|c_attr   conditional & sanitize attribute & return|echo
 *
 * TODO: добавить плюрализацию текста
 */
class I18n
{
    /**
     * Local from HTTP.
     *
     * @since 2025.1
     */
    public static string $locale;

	/**
	 * Translation.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @since 2025.1
	 */
    public static function _t( string $string ): string {
        $locale   = self::getLocale();
        $filepath = sprintf( '%sdashboard/i18n/%s.json', GRFM_PATH, $locale );
        if ( is_file( $filepath ) ) {
            $json = file_get_contents( $filepath );
            $json = json_decode( $json, 1 );
            if ( isset( $json[ $string ] ) ) {
                return $json[ $string ];
            }
        }
        return $string;
    }

	/**
	 * Translate with formatting.
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _f( string $string, mixed ...$args ): string {
		return sprintf( self::_t( $string ), ...$args );
	}

	/**
	 * Translate with condition.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _c( bool $condition, string $ifString, string $elseString = '' ): string
	{
		return $condition ? self::_t( $ifString ) : self::_t( $elseString );
	}

	/**
	 * Translation and use in html attribute.
	 *
	 * @param string $string
	 * @return string
	 *
	 * @since 2025.1
	 */
	public static function _t_attr( string $string ): string {
		return Sanitizer::attribute( $string );
	}

	/**
	 * Translation.
	 *
	 * @param string $string
	 *
	 * @since 2025.1
	 */
	public static function t( string $string ): void {
		echo self::_t( $string );
	}

	/**
	 * Translate with formatting.
	 *
	 * @param string $string
	 * @param mixed ...$args
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function tf( string $string, mixed ...$args ): void {
		echo sprintf( self::_t( $string ), ...$args );
	}

	/**
	 * Translate with condition.
	 *
	 * @param bool $condition
	 * @param string $ifString
	 * @param string $elseString
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function tc( bool $condition, string $ifString, string $elseString = '' ): void {
		echo self::_c( $condition, $ifString, $elseString );
	}

	/**
	 * Translation and use in html attribute.
	 *
	 * @param string $string
	 * @return void
	 *
	 * @since 2025.1
	 */
	public static function t_attr( string $string ): void {
		echo self::_t_attr( $string );
	}

	/**
	 * Get local from HTTP.
	 *
	 * @param string $default
	 * @return string
	 *
	 * @since 2025.1
	 */
    public static function getLocale( string $default = 'en' ): string {
        if ( ! isset( self::$locale ) && function_exists( 'locale_accept_from_http' ) ) {
            self::$locale = locale_accept_from_http( $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? $default );
        }
        return self::$locale ?? $default;
    }

	/**
	 * Output local from HTTP.
	 *
	 * @param string $default
	 * @return void
	 *
	 * @since 2025.1
	 */
    public static function locale( string $default = 'en' ): void {
        echo self::getLocale( $default );
    }
}

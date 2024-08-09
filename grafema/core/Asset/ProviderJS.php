<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset;

class ProviderJS implements ProviderInterface
{
	/**
	 * List of type 'js' script tag.
	 *
	 * @since 2025.1
	 */
	private array $whitelist = ['id', 'src', 'async', 'defer', 'media'];

	/**
	 * @param string $id
	 * @param string $src
	 * @param array $args
	 * @return array
	 * @since 2025.1
	 */
	public function add( string $id, string $src, array $args ): array
	{
		return array_merge(
			[
				'id'           => sprintf( '%s-js', $id ),
				'src'          => $src,
				'async'        => false,
				'defer'        => false,
				'media'        => '',
				// and custom data
				'uuid'         => $id,
				'type'         => 'js',
				'dependencies' => [],
				'data'         => [],
				'path'         => \Grafema\Url::toPath( $src ),
			],
			$args
		);
	}

	/**
	 * @param array $asset
	 * @return string
	 */
	public function plug( array $asset ): string
	{
		$key     = Sanitizer::key( $asset['uuid'] ?? '' );
		$data    = Sanitizer::array( $asset['data'] ?? [] );
		$return  = '';

		if ( $data ) {
			$return = sprintf( "\n<script>const %s = %s</script>", $key, json_encode( $data, JSON_HEX_TAG ) );
		}
		return $return . sprintf( "\n<script%s></script>", ( new Helpers() )->format( $asset, $this->whitelist ) );
	}

	/**
	 * @param string $code
	 * @return string
	 * @since 2025.1
	 */
	public function minify( string $code ): string
	{
		// remove comments
		$code = preg_replace( '/\/\*[\s\S]*?\*\//', '', $code, -1, $count );
		$code = preg_replace( '/\/\/.*$/m', '', $code );

		// remove unnecessary spaces and line breaks
		$code = preg_replace( '/\s+/', ' ', $code );
		$code = str_replace( ["\r\n", "\r", "\n", "\t"], '', $code );

		// remove unnecessary spaces before symbols
		return preg_replace( '/\s*([=:;,+\-*\/<>\(\)\{\}\[\]])\s*/', '$1', $code );
	}
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset;

interface ProviderInterface
{
	/**
	 * @param string $id
	 * @param string $src
	 * @param array $args
	 * @return array
	 * @since 2025.1
	 */
	public function add( string $id, string $src, array $args ): array;

	/**
	 * @param array $asset
	 * @return string
	 * @since 2025.1
	 */
	public function plug( array $asset ): string;

	/**
	 * @param string $code
	 * @return string
	 * @since 2025.1
	 */
	public function minify( string $code ): string;
}

<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema\Asset;

class Dependence
{

	/**
	 * Include jQuery
	 *
	 * @param string $version
	 * @return Dependence
	 */
	public function jQuery( string $version ): Dependence {
		return $this;
	}

	/**
	 * Include React
	 *
	 * @param string $version
	 * @return Dependence
	 */
	public function react( string $version ): Dependence {
		return $this;
	}

	/**
	 * Include Vue.js
	 *
	 * @param string $version
	 * @return Dependence
	 */
	public function vue( string $version = '3.4.31' ): Dependence {
		//return '//unpkg.com/alpinejs';
		return $this;
	}

	/**
	 * Include Alpine.js
	 *
	 * @param string $version
	 * @return Dependence
	 */
	public function alpine( string $version = '3.14.1' ): Dependence {
		//return '//unpkg.com/alpinejs';
		return $this;
	}
}

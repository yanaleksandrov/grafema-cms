<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Builders;

use Grafema\I18n;

/**
 * Class Builder.
 *
 * Base class for displaying a list of items in HTML table.
 *
 * @package Dashboard\Builders
 */
class Builder
{

	/**
	 * The path to the folder with the template files.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	public string $directory = GRFM_PATH . 'dashboard/templates/table';

	/**
	 * The current list of items.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public array $items = [];

	/**
	 * The columns list.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	public array $columns = [];

	/**
	 * Builder constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

	}

	/**
	 * @param array $columns
	 * @return string
	 */
	public function stylize( array $columns ): string
	{
		$result = [];

		if ( $columns ) {
			$previous = null;
			$repeat   = 1;

			foreach ( $columns as $column ) {
				$width    = trim( (string) ( ! empty( $column['width'] ) ? $column['width'] : '1fr' ) );
				$flexible = (bool) ( $column['flexible'] ?? false );

				$value = $flexible ? sprintf( 'minmax(%s, 1fr)', $width ) : $width;

				if ( $value === $previous ) {
					++$repeat;
				} else {
					if ( $previous !== null ) {
						$result[] = $repeat > 1 ? sprintf( 'repeat(%s, %s)', $repeat, $previous ) : $previous;
					}

					$previous = $value;
					$repeat   = 1;
				}
			}

			$result[] = $repeat > 1 ? sprintf( 'repeat(%s, %s)', $repeat, $previous ) : $previous;
		}

		return sprintf( '--grafema-grid-template-columns: %s', implode( ' ', $result ) );
	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function wrapper() {

	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function columns() {

	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function row() {

	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function cell() {

	}

	/**
	 *
	 *
	 * @since 1.0.0
	 */
	public function sort() {

	}

	/**
	 * Return text if table not have items.
	 *
	 * @return string
	 */
	public function noItems(): string
	{
		return I18n::__( 'No items found.' );
	}
}

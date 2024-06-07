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
use Grafema\View;
use Grafema\Sanitizer;

/**
 * Class Table.
 *
 * Base class for displaying a list of items in HTML table.
 *
 * @package Dashboard\Tables
 */
class Table
{
	use Traits\Table;

	/**
	 * Data for render table.
	 *
	 * @since 1.0.0
	 * @var array
	 */
	protected array $data = [];

	/**
	 * The path to the folder with the template files.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	protected string $views = GRFM_DASHBOARD . 'templates/table';

	/**
	 * Add new table.
	 *
	 * @since 1.0.0
	 */
	public static function add(): Table {
		return new self();
	}

	/**
	 * Add table title.
	 *
	 * @param string $title
	 * @return Table
	 * @since 1.0.0
	 */
	public function title( string $title ): Table {
		$title = Sanitizer::html( $title );
		if ( $title ) {
			$this->title = $title;
		}
		return $this;
	}

	/**
	 * Set table attribute.
	 *
	 * @param string $attribute
	 * @param string|int $value
	 * @return Table
	 */
	public function attribute( string $attribute, string|int $value = '' ): Table {
		$attribute = Sanitizer::key( $attribute );
		$value     = Sanitizer::attribute( $value );
		if ( $attribute && $value ) {
			$this->attributes[ $attribute ] = $value;
		}
		return $this;
	}

	/**
	 * Bulk adding attributes.
	 *
	 * @param array $attributes
	 * @return Table
	 */
	public function attributes( array $attributes ): Table {
		foreach ( $attributes as $attribute => $value ) {
			$this->attribute( $attribute, $value );
		}
		return $this;
	}

	/**
	 * Get table markup.
	 *
	 * @return string
	 */
	public function get(): string {
		$output  = '';
		$columns = $this->columns();
		echo '<pre>';
		print_r( $columns );
		echo '</pre>';
		if ( $columns ) {
			$output .= '<div class="table" x-data="table" x-init="$ajax(\'extensions/get\').then(response => items = response.items)" style="' . $this->stylize( $columns ) . '">';

			$output .= View::get(
				sprintf( '%s/header', $this->views ),
				[
					'title'   => I18n::__( 'Plugins' ),
					'content' => View::get( sprintf( '%s/cell-head', $this->views ), $columns ),
				]
			);

			$output .= Row::add()
				->tag( 'div' )
				->attribute( 'class', 'table__row' )
				->render( $columns );

			$output .= '<!-- table rows list start -->';
			$output .= '<template x-for="item in items">';
			$output .= '<div class="table__row">';
			foreach ( $columns as $key => $column ) {
				$cell    = Sanitizer::trim( $column['cell'] ?? '' );
				$output .= View::get( sprintf( '%s/cell-%s', $this->views, $cell ), [ ...$column, ...[ 'key' => $key ] ] );
			}
			$output .= '</div>';
			$output .= '</template>';
			ob_start();
			?>
			<template x-if="!items.length">
				<?php
				View::print(
					'templates/states/undefined',
					[
						'title'       => I18n::__( 'Plugins are not installed yet' ),
						'description' => I18n::__( 'You can download them manually or install from the repository' ),
					]
				);
				?>
			</template>
			<?php
			$output .= ob_get_clean();
			$output .= '</div>';
		}
		return $output;
	}

	/**
	 * Output table markup.
	 *
	 * @return void
	 */
	public function print(): void {
		echo $this->get();
	}

	/**
	 * Calculate grid css styles.
	 *
	 * @param array $columns
	 * @return string
	 */
	public function stylize( array $columns ): string
	{
		if ( ! $columns ) {
			return '';
		}

		$repeat   = 1;
		$previous = null;
		$output   = array_reduce( $columns, function ( $carry, Column $column ) {
			static $previous = null;
			static $repeat   = 1;

			$width    = trim( (string) ( $column->width ?? '1fr' ) );
			$flexible = (bool) ( $column->flexible ?? false );
			$value    = $flexible ? sprintf('minmax(%s, 1fr)', $width) : $width;

			if ($value === $previous) {
				++$repeat;
			} else {
				if ($previous !== null) {
					$carry[] = $repeat > 1 ? sprintf( 'repeat(%s, %s)', $repeat, $previous ) : $previous;
				}

				$previous = $value;
				$repeat = 1;
			}

			return $carry;
		}, [] );

		$output[] = $repeat > 1 ? sprintf('repeat(%s, %s)', $repeat, $previous) : $previous;

		return sprintf( '--grafema-grid-template-columns: %s', implode( ' ', $output ) );
	}
}

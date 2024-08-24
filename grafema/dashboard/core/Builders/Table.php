<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Builders;

use Grafema\View;
use Grafema\Sanitizer;
use Grafema\Helpers\Arr;

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
	 * @since 2025.1
	 * @var array
	 */
	public array $data = [];

	/**
	 * Rows settings.
	 *
	 * @since 2025.1
	 * @var Row
	 */
	public Row $rows;

	/**
	 * Columns list.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $columns = [];

	/**
	 * Template to get for not found.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $notFoundTemplate = 'templates/states/undefined';

	/**
	 * Data for not found template partial.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $notFoundContent;

	/**
	 * The path to the folder with the template files.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $views = GRFM_DASHBOARD . 'templates/table';

	public function __construct( $table ) {
		$methods = [
			'rows',
			'data',
			'title',
			'columns',
			'attributes',
			'notFoundContent',
			'notFoundTemplate',
		];

		foreach ( $methods as $method ) {
			if ( method_exists( $table, $method ) ) {
				$this->$method = $table->$method();
			}
		}
	}

	/**
	 * Add new table.
	 *
	 * @return Table
	 * @since 2025.1
	 */
	public static function add(): Table {
		return new self();
	}

	/**
	 * Add table title.
	 *
	 * @param string $title
	 * @return Table
	 * @since 2025.1
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
		$this->attributes['style'] = $this->stylize( $this->columns );

		$attributes = Arr::toHtmlAtts( $this->attributes );
		ob_start();
		?>
		<div<?php echo $attributes; ?>>
			<?php
			View::print(
				sprintf( '%s/header', $this->views ),
				[
					'title'   => $this->title,
					'content' => View::get( sprintf( '%s/cell-head', $this->views ), $this->columns ),
				]
			);
			?>
			<!-- table rows list start -->
			<template x-for="item in items">
				<?php echo $this->rows->render( $this->columns, $this->data ); ?>
			</template>
			<template x-if="!items.length">
				<?php View::print( $this->notFoundTemplate, $this->notFoundContent ); ?>
			</template>
		</div>
		<?php
		return ob_get_clean();
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

		$repeat = 1;
		$styles = [];
		foreach ( $columns as $i => $column ) {
			$width    = Sanitizer::trim( $column->width ?? '1fr' );
			$flexible = Sanitizer::bool( $column->flexible ?? false );
			if ( $flexible ) {
				$width = sprintf( 'minmax(%s, 1fr)', $width );
			}

			if ( $width === ( $styles[$i - 1] ?? null ) ) {
				$repeat++;
				$styles[ $i - 1 ] = sprintf( 'repeat(%s, %s)', $repeat, $width );
			} else {
				$repeat = 1;
				$styles[ $i ] = $width;
			}
		}

		return sprintf( '--grafema-grid-template-columns: %s', implode( ' ', $styles ) );
	}
}

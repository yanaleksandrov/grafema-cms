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

	public function __construct( $table ) {
		$methods = [
			'rows',
			'data',
			'title',
			'columns',
			'attributes',
			'headerContent',
			'headerTemplate',
			'notFoundContent',
			'notFoundTemplate',
			'cellHeadTemplate',
		];

		foreach ( $methods as $method ) {
			if ( method_exists( $table, $method ) ) {
				$this->$method = $table->$method();
			}
		}
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
		$styles = $this->stylize( $this->columns );
		if ( $styles ) {
			$this->attributes['style'] = $styles;
		}

		$attributes = Arr::toHtmlAtts( $this->attributes );
		ob_start();
		?>
		<div<?php echo $attributes; ?>>
			<?php
			View::print(
				$this->headerTemplate,
				$this->headerContent ?? [
					'title'   => $this->title,
					'content' => View::get( sprintf( '%s/%s', $this->views, $this->cellHeadTemplate ), $this->columns ),
				]
			);

//			echo '<pre>';
//			print_r( $this );
//			echo '</pre>';
			if ( is_array( $this->data ) && $this->data ) {
				foreach ( $this->data as $i => $data ) {
					$row = $this->rows[ $i ] ?? end( $this->rows );

					View::print(
						$row->view ?? '',
						[
							'data'    => $data,
							'rows'    => $row,
							'columns' => $this->columns,
						]
					);
				}
			} else {
				View::print( $this->notFoundTemplate, $this->notFoundContent );
			}
			// TODO: добавить возможность добавления обёртки для vue.js или alpine.js
			?>
<!--			<template x-if="!items.length">-->
<!--			</template>-->
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
	public function stylize( array $columns ): string {
		$repeat = 1;
		$styles = [];
		if ( $columns ) {
			foreach ( $columns as $i => $column ) {
				$width    = Sanitizer::trim( $column->width ?? '1fr' );
				$flexible = Sanitizer::bool( $column->flexible ?? false );
				if ( $flexible ) {
					$width = sprintf( 'minmax(%s, 1fr)', $width );
				}

				if ( $width ) {
					if ( $width === ( $styles[ $i - 1 ] ?? null ) ) {
						$repeat++;
						$styles[ $i - 1 ] = sprintf( 'repeat(%s, %s)', $repeat, $width );
					} else {
						$repeat = 1;
						$styles[ $i ] = $width;
					}
				}
			}
		}

		if ( $styles ) {
			return sprintf( '--grafema-grid-template-columns: %s', implode( ' ', $styles ) );
		}

		return '';
	}
}

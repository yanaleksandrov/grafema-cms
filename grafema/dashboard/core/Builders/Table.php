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
final class Table {
	use Traits\Table;

	public function __construct( $table ) {
		$methods = [
			'tag',
			'rows',
			'columns',
			'attributes',
			'data',
			'dataAfter',
			'dataBefore',
			'headerContent',
			'headerTemplate',
			'notFoundAfter',
			'notFoundBefore',
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
		$this->tag && printf( '<%s>', trim( sprintf( '%s %s', $this->tag, $attributes ) ) );

		View::print(
			$this->headerTemplate,
			$this->headerContent ?? [
				'title'   => $this->title,
				'content' => View::get( sprintf( '%s/%s', $this->views, $this->cellHeadTemplate ), $this->columns ),
			]
		);

		if ( is_array( $this->data ) && $this->data ) {
			if ( $this->dataBefore ) {
				echo $this->dataBefore;
			}

			foreach ( $this->data as $i => $data ) {
				$row = $this->rows[ $i ] ?? end( $this->rows );

				View::print(
					$row->view ?? '',
					[
						'data'    => $data,
						'row'     => $row,
						'columns' => $this->columns,
					]
				);
			}

			if ( $this->dataAfter ) {
				echo $this->dataAfter;
			}
		} else {
			if ( $this->notFoundBefore ) {
				echo $this->notFoundBefore;
			}

			View::print( $this->notFoundTemplate, $this->notFoundContent );

			if ( $this->notFoundAfter ) {
				echo $this->notFoundAfter;
			}
		}

		$this->tag && printf( '</%s>', $this->tag );
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

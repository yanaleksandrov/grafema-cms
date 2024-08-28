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
			'dataVariable',
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
		if ( $this->tag ) {
			?>
			<<?php echo trim( sprintf( '%s %s', $this->tag, $attributes ) ); ?>>
			<?php
		}

		View::print(
			$this->headerTemplate,
			[
				'content' => $this->data ? View::get( sprintf( '%s/%s', $this->views, $this->cellHeadTemplate ), $this->columns ) : '',
				...$this->headerContent
			]
		);

		ob_start();
		echo $this->dataBefore ?? '';

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

		echo $this->dataAfter ?? '';
		$data = ob_get_clean();

		ob_start();
		echo $this->notFoundBefore ?? '';

		View::print( $this->notFoundTemplate, $this->notFoundContent );

		echo $this->notFoundAfter ? $this->notFoundAfter . PHP_EOL : '';
		$notFound = ob_get_clean();

		if ( $this->dataVariable ) {
			?>
			<template x-if="<?php echo Sanitizer::attribute( $this->dataVariable ); ?>.length">
				<template x-for="item in <?php echo Sanitizer::attribute( $this->dataVariable ); ?>">
					<?php echo $data; ?>
				</template>
			</template>
			<template x-if="!<?php echo Sanitizer::attribute( $this->dataVariable ); ?>.length">
				<?php echo $notFound; ?>
			</template>
			<?php
		} else {
			if ( $this->data ) {
				echo $data;
			} else {
				echo $notFound;
			}
		}

		if ( $this->tag ) {
			?>
			</<?php echo $this->tag; ?>>
			<?php
		}
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
				$width    = Sanitizer::trim( $column->width ?: '1fr' );
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

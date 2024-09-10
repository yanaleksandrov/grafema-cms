<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
namespace Dashboard;

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

	use Table\Traits\Table;

	public function __construct( $table ) {
		// include filter
		require_once GRFM_DASHBOARD . 'forms/grafema-items-filter.php';

		$methods = [
			'tag',
			'rows',
			'columns',
			'filter',
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
		$tag    = Sanitizer::tag( $this->tag );
		$styles = $this->stylize( $this->columns );
		if ( $styles ) {
			$this->attributes['style'] = $styles;
		}

		ob_start();
		$tag && printf( '<%s>', trim( $tag . ' ' . Arr::toHtmlAtts( $this->attributes ) ) );

		View::print(
			$this->headerTemplate,
			[
				'content' => $this->data ? View::get( sprintf( '%s/%s', $this->views, $this->cellHeadTemplate ), $this->columns ) : '',
				...$this->headerContent
			]
		);

		if ( $this->dataVariable ) {
			$prop = Sanitizer::prop( $this->dataVariable );
			$row  = current( $this->rows ?? [] );
			?>
			<template x-if="<?php echo $prop; ?>.length">
				<?php echo $this->dataBefore ?? ''; ?>
					<template x-for="item in <?php echo $prop; ?>">
						<?php View::print( $row->view, [ 'data' => $this->data, 'row' => $row, 'columns' => $this->columns ] ); ?>
					</template>
				<?php echo $this->dataAfter ?? ''; ?>
			</template>
			<template x-if="!<?php echo $prop; ?>.length">
				<?php View::print( $this->notFoundTemplate, $this->notFoundContent ); ?>
			</template>
			<?php
		} else {
			if ( $this->data ) {
				echo $this->dataBefore ?? '';
				foreach ( $this->data as $i => $data ) {
					$row = $this->rows[ $i ] ?? end( $this->rows );
					View::print( $row->view ?? '', [ 'data' => $data, 'row' => $row, 'columns' => $this->columns ] );
				}
				echo ( $this->dataAfter ?? '' ) . PHP_EOL;
			} else {
				View::print( $this->notFoundTemplate, $this->notFoundContent );
			}
		}

		$tag && printf( '</%s>', $tag );

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

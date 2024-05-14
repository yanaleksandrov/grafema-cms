<?php

namespace Dashboard\Tables;

use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

class Plugins extends Builder
{
	public function render()
	{
		$output  = '';
		$content = '';
		$columns = $this->columns();
		if ( $columns ) {
			$output .= '<div class="table" x-data="table" x-init="$ajax(\'extensions/get\').then(response => items = response.items)" style="' . $this->stylize( $columns ) . '">';

			$content .= '<div class="table__head">';
			foreach ( $columns as $key => $column ) {
				$content .= View::get( 'templates/table/cell-head', $column + ['key' => $key] );
			}
			$content .= '</div>';

			$output .= View::get(
				'templates/table/header',
				[
					'title'   => I18n::__( 'Plugins' ),
					'content' => $content,
				]
			);

			$output .= '<!-- table rows list start -->';
			$output .= '<template x-for="item in items">';
			$output .= '<div class="table__row hover">';

			foreach ( $columns as $key => $column ) {
				$cell    = Sanitizer::trim( $column['cell'] ?? '' );
				$output .= View::get(
					'templates/table/cell-' . $cell,
					[
						'column' => [
							'key' => $key,
							...$column,
						],
					]
				);
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
		echo $output;
	}

	public function columns(): array
	{
		return [
			'cb' => [
				'cell'       => 'cb',
				'title'      => '<input type="checkbox" x-bind="trigger">',
				'width'      => '1rem',
				'flexible'   => false,
				'sortable'   => false,
			],
			'reviews' => [
				'cell'       => 'raw',
				'title'      => '<i class="ph ph-hash-straight"></i>',
				'width'      => '1rem',
				'flexible'   => false,
				'sortable'   => false,
			],
			'image' => [
				'cell'       => 'image',
				'title'      => '',
				'width'      => '2.5rem',
				'flexible'   => false,
				'sortable'   => false,
			],
			'title' => [
				'cell'       => 'title',
				'title'      => I18n::__( 'Title' ),
				'width'      => '22rem',
				'flexible'   => true,
				'sortable'   => true,
			],
			'author' => [
				'cell'       => 'links',
				'title'      => I18n::__( 'Author' ),
				'width'      => '6rem',
				'flexible'   => true,
				'sortable'   => false,
			],
			'categories' => [
				'cell'       => 'links',
				'title'      => I18n::__( 'Categories' ),
				'width'      => '6rem',
				'flexible'   => true,
				'sortable'   => false,
			],
			'date' => [
				'cell'       => 'date',
				'title'      => I18n::__( 'Date' ),
				'width'      => '9rem',
				'flexible'   => false,
				'sortable'   => true,
			],
		];
	}

	/**
	 * @since 1.0.0
	 */
	public function wrapper()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function row()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function cell()
	{
		return sprintf( '<div %s>%s</div>' );
	}

	public function sort()
	{
		// TODO: Implement sort() method.
	}

	public function modify() {}
}

<?php

namespace Dashboard;

use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\View;

class PagesTable extends Builders\Table
{
	public function render()
	{
		$output  = '';
		$columns = $this->columns();
		if ( $columns ) {
			$output .= '<div class="table" x-data="table" x-init="$ajax(\'posts/get\').then(response => items = response)" style="' . $this->stylize( $columns ) . '">';
			$output .= '<template x-if="items.length">';
			$output .= '<!-- table header start -->';
			$output .= '<div class="table__row">';

			foreach ( $columns as $key => $column ) {
				$output .= View::get( 'templates/table/cell-head', $column + ['key' => $key] );
			}
			$output .= '</div>';
			$output .= '</template>';

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
						'title'       => I18n::__( 'Pages not found' ),
						'description' => I18n::__( 'You don\'t have any pages yet. <a @click="$modal.open(\'grafema-modals-post\')">Add them manually</a> or <a href="/dashboard/import">import via CSV</a>' ),
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
				'title'      => '<input type="checkbox" x-bind="trigger"/>',
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

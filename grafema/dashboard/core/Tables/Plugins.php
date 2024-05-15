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
				$content .= View::get( 'templates/table/cell-head', [ ...$column, ...[ 'key' => $key ] ] );
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
			$output .= '<div class="table__row">';
			foreach ( $columns as $key => $column ) {
				$cell    = Sanitizer::trim( $column['cell'] ?? '' );
				$output .= View::get( 'templates/table/cell-' . $cell, [ ...$column, ...[ 'key' => $key ] ] );
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
			'plugin' => [
				'cell'       => 'plugin',
				'title'      => I18n::__( 'Plugin' ),
				'width'      => '10rem',
				'flexible'   => true,
				'sortable'   => false,
			],
//			'description' => [
//				'cell'       => 'raw',
//				'title'      => I18n::__( 'Description' ),
//				'width'      => '16rem',
//				'flexible'   => true,
//				'sortable'   => false,
//			],
			'license' => [
				'cell'       => 'badge',
				'title'      => I18n::__( 'License' ),
				'width'      => '4rem',
				'flexible'   => false,
				'sortable'   => false,
			],
			'version' => [
				'cell'       => 'raw',
				'title'      => I18n::__( 'Version' ),
				'width'      => '4rem',
				'flexible'   => false,
				'sortable'   => false,
			],
			'active' => [
				'cell'       => 'toggle',
				'title'      => I18n::__( 'Activity' ),
				'width'      => '4rem',
				'flexible'   => false,
				'sortable'   => false,
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

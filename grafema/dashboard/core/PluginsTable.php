<?php

namespace Dashboard;

use Grafema\I18n;
use Grafema\View;
use Grafema\Hook;
use Grafema\Sanitizer;

use Dashboard\Builders\Row;
use Dashboard\Builders\Table;
use Dashboard\Builders\Column;

class PluginsTable extends Table
{

	/**
	 * The main function for generating a table markup.
     *
	 * @since 2025.1
	 */
	public function render()
	{
	    echo '123456789';
		$output  = '';
		$columns = $this->columns();
		echo '<pre>';
		print_r( $columns );
		echo '</pre>';
		if ( $columns ) {
			$output .= '<div class="table" x-data="table" x-init="$ajax(\'extensions/get\').then(response => items = response.items)" style="' . $this->stylize( $columns ) . '">';

			$output .= View::get(
				'templates/table/header',
				[
					'title'   => I18n::__( 'Plugins' ),
					'content' => View::get( 'templates/table/cell-head', $columns ),
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

	/**
	 * Columns list.
	 *
	 * @since 2025.1
	 */
	protected function columns(): array
	{
	    return Hook::apply(
	        'grafema_plugins_table_columns',
			[
				Column::add( 'cb' )
                    ->title( '<input type="checkbox" x-bind="trigger" />' )
					->fixedWidth( '1rem' )
					->view( 'cb' ),
				Column::add( 'image' )
					->fixedWidth( '2.5rem' )
					->view( 'image' ),
				Column::add( 'plugin' )
					->title( I18n::__( 'Plugin' ) )
					->flexibleWidth( '10rem' )
					->view( 'plugin' ),
				Column::add( 'description' )
					->title( I18n::__( 'Description' ) )
					->flexibleWidth( '16rem' )
					->view( 'raw' ),
				Column::add( 'version' )
					->title( I18n::__( 'Version' ) )
					->fixedWidth( '6rem' )
					->view( 'badge' ),
				Column::add( 'active' )
					->title( I18n::__( 'Activity' ) )
					->fixedWidth( '6rem' )
					->view( 'toggle' ),
			]
        );
	}

	/**
	 * Return text if table not have items.
	 *
	 * @return string
	 */
	public function noItems(): string
	{
		return I18n::__( 'Plugins not found.' );
	}

	/**
	 * Include CSS styles & JS scripts.
	 *
	 * @since 2025.1
	 */
	public function query() {

	}
}

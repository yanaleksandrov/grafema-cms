<?php
namespace Dashboard;

use Grafema\I18n;
use Grafema\View;

use Dashboard\Builders\Row;
use Dashboard\Builders\Cell;

final class MediaTable {

	public function tag(): string {
		return '';
	}

	public function rows(): array {
		return [
			Row::add()->tag( '' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'media' )->view( 'media' ),
		];
	}

	public function attributes(): array {
		return [
			'class' => 'table',
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::_t( 'Files in library is not found' ),
			'description' => I18n::_t( 'They have not been uploaded or do not match the filter parameters' ),
		];
	}

	public function headerContent(): array {
		return [
			'title'   => I18n::_t( 'Media Library' ),
			'actions' => false,
			'filter'  => false,
			'show'    => 'false',
			'content' => '',
		];
	}

	public function notFoundBefore(): string {
		ob_start();
		$row = current( $this->rows() );
		?>
		<template x-if="items.length">
			<div class="storage">
				<template x-for="item in items">
					<?php View::print( $row->view, [ 'columns' => $this->columns(), 'row' => $row, 'data' => [] ] ); ?>
				</template>
			</div>
		</template>
		<template x-if="!items.length">
		<?php
		return ob_get_clean();
	}

	public function notFoundAfter(): string {
		return '</template>';
	}
}

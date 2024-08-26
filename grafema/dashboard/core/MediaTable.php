<?php
namespace Dashboard;

use Grafema\I18n;
use Grafema\View;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class MediaTable {

	public function tag(): string {
		return '';
	}

	public function data(): array {
		return [];
	}

	public function rows(): array {
		return [
			Row::add()->tag( '' ),
		];
	}

	public function columns(): array {
		return [
			Column::add( 'media' )->view( 'media' ),
		];
	}

	public function attributes(): array {
		return [
			'class' => 'table',
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Files in library is not found' ),
			'description' => I18n::__( 'They have not been uploaded or do not match the filter parameters' ),
		];
	}

	public function headerContent(): array {
		return [];
	}

	public function headerTemplate(): string {
		return '';
	}

	public function notFoundBefore(): string {
		ob_start();
		$row = current( $this->rows() );
		?>
		<template x-if="posts.length">
			<div class="storage">
				<template x-for="post in posts">
					<?php View::print( $row->view, [ 'columns' => $this->columns(), 'row' => $row ] ); ?>
				</template>
			</div>
		</template>
		<template x-if="!posts.length">
		<?php
		return ob_get_clean();
	}

	public function notFoundAfter(): string {
		return '</template>';
	}
}

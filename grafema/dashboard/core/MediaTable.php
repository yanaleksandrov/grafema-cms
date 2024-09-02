<?php
namespace Dashboard;

use Grafema\I18n;
use Grafema\View;

use Dashboard\Table\Row;
use Dashboard\Table\Cell;

final class MediaTable {

	public function tag(): string {
		return '';
	}

	public function rows(): array {
		return [
			Row::add()->tag( '' ),
		];
	}

	public function dataBefore(): string {
		return '<div class="storage">';
	}

	public function dataAfter(): string {
		return '</div>';
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
}

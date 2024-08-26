<?php
namespace Dashboard;

use Grafema\I18n;
use Grafema\Media;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

class MediaTable {

	public function data(): array {
		return Media::get(
			[
				'per_page' => 40,
			]
		);
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
			'class' => 'storage',
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
}

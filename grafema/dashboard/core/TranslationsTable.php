<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class TranslationsTable {

	public function data(): array {
		return [
			[
				'source' => 'Hello World',
				'value'  => 'Привет Мир',
			],
			[
				'source' => 'Text',
				'value'  => 'Текст',
			],
			[
				'source' => 'Hello',
				'value'  => '',
			],
		];
	}

	public function rows(): array {
		return [
			Row::add()->tag( 'div' )->attribute( 'class', 'table__grid' ),
		];
	}

	public function columns(): array {
		return [
			Column::add( 'source' )->title( I18n::_f( '%s Source text', '<i class="ph ph-text-aa"></i>' ) )->view( 'raw' ),
			Column::add( 'value' )->title( I18n::_f( '%s Translations', '<i class="ph ph-globe-hemisphere-east"></i>' ) )->view( 'text' ),
		];
	}

	public function attributes(): array {
		return [
			'class' => 'table',
		];
	}

	public function headerContent(): array {
		return [
			'title' => I18n::__( 'Translations' ),
			'badge' => I18n::_f( 'translated %d from %d <i class="t-green">(%d%%)</i>', 56, 408, 25 ),
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Translates not found' ),
			'description' => I18n::__( 'You don\'t have any pages yet. <a @click="$modal.open(\'grafema-modals-post\')">Add them manually</a> or <a href="/dashboard/import">import via CSV</a>' ),
		];
	}
}
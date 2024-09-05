<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Table\Row;
use Dashboard\Table\Cell;

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
			Row::add()->attribute( 'class', 'table__grid' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'source' )->title( I18n::_f( '%s Source text', '<i class="ph ph-text-aa"></i>' ) )->view( 'raw' ),
			Cell::add( 'value' )->title( I18n::_f( '%s Translations', '<i class="ph ph-globe-hemisphere-east"></i>' ) )->view( 'text' ),
		];
	}

	public function attributes(): array {
		return [
			'class' => 'table',
		];
	}

	public function headerContent(): array {
		return [
			'title'       => I18n::_t( 'Translations' ),
			'badge'       => I18n::_f( 'completed %d from %d <i class="t-green">(%d%%)</i>', 56, 408, 25 ),
			'translation' => true,
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::_t( 'Translates not found' ),
			'description' => I18n::_t( 'You don\'t have any pages yet. <a @click="$dialog.open(\'grafema-modals-post\')">Add them manually</a> or <a href="/dashboard/import">import via CSV</a>' ),
		];
	}
}

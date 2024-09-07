<?php
namespace Dashboard;

use Grafema\I18n;
use Grafema\Hook;

use Dashboard\Table\Row;
use Dashboard\Table\Cell;

final class TranslationsTable {

	public function data(): array {
		Hook::add( 'grafema_dashboard_data', function( $data ) {
			$data['items'] = [
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
			return $data;
		} );

		return [ 435 ];
	}

	public function rows(): array {
		return [
			Row::add()->attribute( 'class', 'table__grid' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'source' )->title( I18n::_f( '%s Source text - English', '<i class="ph ph-text-aa"></i>' ) )->view( 'raw' ),
			Cell::add( 'value' )->title( I18n::_f( '%s Translations - Russian', '<i class="ph ph-globe-hemisphere-east"></i>' ) )->view( 'text' ),
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
			'description' => I18n::_t( "Click the 'Scan' button to get started and load the strings to be translated from the source code." ),
		];
	}
}

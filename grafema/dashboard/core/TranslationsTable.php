<?php
namespace Dashboard;

use Grafema\File\File;
use Grafema\I18n;
use Grafema\Hook;
use Grafema\Json;

use Dashboard\Table\Row;
use Dashboard\Table\Cell;

final class TranslationsTable {

	public function data(): array {
		Hook::add( 'grafema_dashboard_data', function( $data ) {
			$filepath = GRFM_DASHBOARD . sprintf( 'i18n/%s.json', I18n::locale() );
			$filetext = ( new File( $filepath ) )->read();

			$json  = Json::decode( $filetext, true );

			foreach ( $json as $source => $value ) {
				$data['items'][] = [ 'source' => $source, 'value'  => $value ];
			}

			return $data;
		} );

		return [ 435 ];
	}

	public function dataBefore(): string {
		return '<form class="translation" method="POST" @input.debounce.500ms="$ajax(\'translations/update\',{project})">';
	}

	public function dataAfter(): string {
		return '</form>';
	}

	public function rows(): array {
		return [
			Row::add()->attribute( 'class', 'translation__grid' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'source' )
				->title( I18n::_f( ':icon Source text - English', '<i class="ph ph-text-aa"></i>' ) )
				->attributes( [ 'class' => 'translation__source' ] )
				->view( 'raw' ),
			Cell::add( 'value' )
				->title( I18n::_f( ':icon Translations - Russian', '<i class="ph ph-globe-hemisphere-east"></i>' ) )
				->attributes( [ 'class' => 'translation__value' ] )
				->view( 'translation' ),
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
			'badge'       => I18n::_f( 'completed :stringsCount from :allStringsCount <i class="t-green">(:percent%)</i>', 56, 408, 25 ),
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

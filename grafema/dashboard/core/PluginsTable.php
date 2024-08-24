<?php

namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

class PluginsTable {

	public function data(): array {
		return [
			[
				'cb'         => '<input type="checkbox" value="1">',
				'image'      => 'image',
				'title'      => 'Post title',
				'author'     => 'Yan Aleksandrov',
				'categories' => [],
				'date'       => '24 august 2024',
			]
		];
	}

	public function title(): string {
		return I18n::__( 'Plugins' );
	}

	public function rows(): Row {
		return Row::add()
			->tag( 'div' )
			->attribute( 'class', 'table__row' );
	}

	public function columns(): array {
		return [
			Column::add( 'cb' )
				->title( '<input type="checkbox" x-bind="trigger" />' )
				->fixedWidth( '1rem' )
				->view( 'cb' ),
			Column::add( 'image' )
				->fixedWidth( '2.5rem' )
				->view( 'image' ),
			Column::add( 'plugin' )
				->title( I18n::__( 'Plugin' ) )
				->flexibleWidth( '16rem' )
				->view( 'plugin' ),
			Column::add( 'description' )
				->title( I18n::__( 'Description' ) )
				->flexibleWidth( '24rem' )
				->view( 'raw' ),
			Column::add( 'version' )
				->title( I18n::__( 'Version' ) )
				->fixedWidth( '6rem' )
				->view( 'badge' ),
			Column::add( 'active' )
				->title( I18n::__( 'Activity' ) )
				->fixedWidth( '6rem' )
				->view( 'toggle' ),
		];
	}

	public function attributes(): array {
		return [
			'class'  => 'table',
			'x-data' => 'table',
			'x-init' => '$ajax("extensions/get").then(response => items = response.items)',
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Plugins are not installed yet' ),
			'description' => I18n::__( 'You can download them manually or install from the repository' ),
		];
	}

	public function notFoundTemplate(): string {
		return 'templates/states/undefined';
	}
}

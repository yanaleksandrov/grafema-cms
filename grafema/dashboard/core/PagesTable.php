<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class PagesTable {

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

	public function rows(): array {
		return [
			Row::add()->tag( 'div' )->attribute( 'class', 'table__row' )
		];
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
			Column::add( 'title' )
				->title( I18n::_t( 'Title' ) )
				->flexibleWidth( '16rem' )
				->sortable()
				->view( 'title' ),
			Column::add( 'author' )
				->title( I18n::_t( 'Author' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
			Column::add( 'categories' )
				->title( I18n::_t( 'Categories' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
			Column::add( 'date' )
				->title( I18n::_t( 'Date' ) )
				->fixedWidth( '6rem' )
				->sortable()
				->view( 'date' ),
		];
	}

	public function attributes(): array {
		return [
			'class'  => 'table',
			'x-data' => 'table',
			'x-init' => '$ajax("posts/get").then(response => items = response.items)',
		];
	}

	public function headerContent(): array {
		return [
			'title'   => I18n::_t( 'Pages' ),
			'actions' => true,
			'filter'  => true,
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::_t( 'Pages not found' ),
			'description' => I18n::_t( 'You don\'t have any pages yet. <a @click="$modal.open(\'grafema-modals-post\')">Add them manually</a> or <a href="/dashboard/import">import via CSV</a>' ),
		];
	}
}

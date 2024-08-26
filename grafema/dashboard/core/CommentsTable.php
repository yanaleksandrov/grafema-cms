<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class CommentsTable {

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
			Column::add( 'author' )
				->title( I18n::__( 'Author' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
			Column::add( 'comment' )
				->title( I18n::__( 'Comment' ) )
				->flexibleWidth( '6rem' )
				->view( 'raw' ),
			Column::add( 'date' )
				->title( I18n::__( 'In response to' ) )
				->fixedWidth( '9rem' )
				->sortable()
				->view( 'date' ),
			Column::add( 'date' )
				->title( I18n::__( 'Date' ) )
				->fixedWidth( '9rem' )
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
			'title' => I18n::__( 'Comments' ),
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Comments not found' ),
			'description' => I18n::__( 'You don\'t have any pages yet. <a @click="$modal.open(\'grafema-modals-post\')">Add them manually</a> or <a href="/dashboard/import">import via CSV</a>' ),
		];
	}
}

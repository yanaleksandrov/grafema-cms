<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class UsersTable {

	public function data(): array {
		return [
			[
				'cb'      => '<input type="checkbox" name="post[]" x-bind="switcher">',
				'avatar' => 'https://i.pravatar.cc/150?img=1',
				'name'   => 'Izabella Tabakova',
				'email'  => 'codyshop@team.com',
				'role'   => 'Admin',
				'visit'  => '3 days ago',
			],
		];
	}

	public function rows(): array {
		return [
			Row::add()->tag( 'div' )->attribute( 'class', 'table__row' ),
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
			Column::add( 'name' )
				->title( I18n::__( 'Name' ) )
				->flexibleWidth( '22rem' )
				->sortable()
				->view( 'title' ),
			Column::add( 'author' )
				->title( I18n::__( 'Role' ) )
				->flexibleWidth( '22rem' )
				->view( 'links' ),
			Column::add( 'categories' )
				->title( I18n::__( 'Last visit' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
		];
	}

	public function attributes(): array {
		return [
			'class'  => 'table',
			'x-data' => 'table',
			'x-init' => '$ajax("users/get").then(response => items = response.items)',
		];
	}

	public function headerContent(): array {
		return [
			'title' => I18n::__( 'Users' ),
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Users not found' ),
			'description' => I18n::_f(
				'You don\'t have any pages yet. %1$sAdd them manually%2$s or %3$simport via CSV%4$s',
				'<a @click="$modal.open(\'grafema-modals-post\')">',
				'</a>',
				'<a href="/dashboard/import">',
				'</a>'
			)
		];
	}
}

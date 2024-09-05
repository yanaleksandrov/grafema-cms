<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Table\Row;
use Dashboard\Table\Cell;

final class UsersTable {

	public function data(): array {
		return [];
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
			Row::add()->attribute( 'class', 'table__row' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'cb' )
				->title( '<input type="checkbox" x-bind="trigger" />' )
				->fixedWidth( '1rem' )
				->view( 'cb' ),
			Cell::add( 'image' )
				->fixedWidth( '2.5rem' )
				->view( 'image' ),
			Cell::add( 'name' )
				->title( I18n::_t( 'Name' ) )
				->flexibleWidth( '16rem' )
				->sortable()
				->view( 'title' ),
			Cell::add( 'author' )
				->title( I18n::_t( 'Role' ) )
				->flexibleWidth( '8rem' )
				->view( 'links' ),
			Cell::add( 'categories' )
				->title( I18n::_t( 'Last visit' ) )
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
			'title'   => I18n::_t( 'Users' ),
			'actions' => true,
			'filter'  => true,
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::_t( 'Users not found' ),
			'description' => I18n::_f(
				'You don\'t have any pages yet. %1$sAdd them manually%2$s or %3$simport via CSV%4$s',
				'<a @click="$dialog.open(\'grafema-modals-post\')">',
				'</a>',
				'<a href="/dashboard/import">',
				'</a>'
			)
		];
	}
}

<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Cell;

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
			Row::add()->attribute( 'class', 'table__row' )
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
			Cell::add( 'title' )
				->title( I18n::_t( 'Title' ) )
				->flexibleWidth( '16rem' )
				->sortable()
				->view( 'title' ),
			Cell::add( 'author' )
				->title( I18n::_t( 'Author' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
			Cell::add( 'categories' )
				->title( I18n::_t( 'Categories' ) )
				->flexibleWidth( '6rem' )
				->view( 'links' ),
			Cell::add( 'date' )
				->title( I18n::_t( 'Date' ) )
				->fixedWidth( '6rem' )
				->sortable()
				->view( 'date' ),
		];
	}

	public function filter() {
		Form::override( 'grafema-items-filter', function( Form $form ) {
			$form->before( 'submit' )->attach(
				[
					[
						'type'        => 'select',
						'uid'         => 'authors',
						'label'       => '',
						'class'       => 'field field--sm field--outline',
						'label_class' => '',
						'reset'       => 0,
						'before'      => '',
						'after'       => '',
						'instruction' => '',
						'tooltip'     => '',
						'copy'        => 0,
						'sanitizer'   => '',
						'validator'   => '',
						'conditions'  => [],
						'attributes'  => [],
						'options'     => [
							''                => I18n::_t( 'Select an author' ),
							'user-registered' => I18n::_t( 'New user registered' ),
						],
					],
					[
						'type'        => 'date',
						'uid'         => 'date',
						'label'       => '',
						'class'       => 'field field--sm field--outline',
						'label_class' => '',
						'reset'       => 0,
						'before'      => '',
						'after'       => '',
						'instruction' => '',
						'tooltip'     => '',
						'copy'        => 0,
						'sanitizer'   => '',
						'validator'   => '',
						'conditions'  => [],
						'attributes'  => [],
					],
				]
			);
		} );
	}

	public function attributes(): array {
		return [
			'class'  => 'table',
			'x-data' => 'table',
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

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
		Form::register(
			'grafema-items-filter',
			[
				'class'           => 'table__filter',
				'x-show'          => 'showFilter === true',
				'x-cloak'         => true,
				'@submit.prevent' => '$ajax("items/filter")',
			],
			function ( $form ) {
				$form->addFields(
					[
						[
							'type'        => 'search',
							'label'       => '',
							'name'        => 's',
							'value'       => '',
							'placeholder' => '',
							'class'       => 'field field--outline',
							'label_class' => '',
							'reset'       => 1,
							'required'    => 0,
							'copy'        => 0,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => '',
							'attributes'  => [
								'placeholder' => I18n::_t( 'e.g. search text' ),
							],
							'conditions'  => [],
						],
						[
							'type'        => 'select',
							'label'       => '',
							'name'        => 'authors',
							'value'       => '',
							'placeholder' => '',
							'class'       => '',
							'label_class' => '',
							'reset'       => 1,
							'required'    => 0,
							'copy'        => 0,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => '',
							'attributes'  => [
								'class' => 'select select--outline',
							],
							'conditions'  => [],
							'options'     => [
								''                => I18n::_t( 'Select an author' ),
								'user-registered' => I18n::_t( 'New user registered' ),
							],
						],
						[
							'type'       => 'submit',
							'label'      => I18n::_t( 'Apply filters' ),
							'attributes' => [
								'class' => 'btn btn--primary',
							],
						],
					]
				);
			}
		);
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

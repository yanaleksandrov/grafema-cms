<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Column;

final class EmailsTable {

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
			Column::add( 'title' )
				->title( I18n::__( 'Name' ) )
				->flexibleWidth( '15rem' )
				->sortable()
				->view( 'title' ),
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
			'x-init' => '$ajax("emails/get").then(response => items = response.items)',
		];
	}

	public function headerContent(): array {
		return [
			'title' => I18n::__( 'Emails' ),
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::__( 'Emails templates is not found' ),
			'description' => I18n::_f(
				'Add %1$snew email template%2$s manually',
				'<a href="/dashboard/emails" @click.prevent="$modal.open(\'grafema-emails-creator\')">',
				'</a>'
			)
		];
	}
}

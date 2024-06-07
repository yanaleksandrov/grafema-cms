<?php

namespace Dashboard\Builders\Traits;

trait Column
{

	/**
	 * Unique column key.
	 *
	 * @var string
	 */
	protected string $key;

	/**
	 * Title of column.
	 *
	 * @var string
	 */
	protected string $title = '';

	/**
	 * Path to get view for render column cell.
	 *
	 * @var string
	 */
	protected string $view = '';

	/**
	 * Column is sortable.
	 *
	 * @var bool
	 */
	protected bool $sortable = false;

	/**
	 * Column default sort ordering.
	 *
	 * @var string
	 */
	protected string $sortOrder = 'DESC';

	/**
	 * Min column width.
	 *
	 * @var string
	 */
	protected string $width = '';

	/**
	 * Column width is flexible.
	 *
	 * @var bool
	 */
	protected bool $flexible = false;

	/**
	 * Column is searchable.
	 *
	 * @var bool
	 */
	protected bool $searchable = false;

	/**
	 * Column constructor.
	 *
	 * @param string $key
	 */
	public function __construct( string $key ) {
		$this->key = $key;
	}
}

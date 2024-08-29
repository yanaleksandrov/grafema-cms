<?php
namespace Dashboard\Builders\Traits;

trait Cell {

	/**
	 * Unique column key.
	 *
	 * @var string
	 */
	public string $key;

	/**
	 * Title of column.
	 *
	 * @var string
	 */
	public string $title = '';

	/**
	 * Path to get view for render column cell.
	 *
	 * @var string
	 */
	public string $view = 'templates/table/cell';

	/**
	 * Column is sortable.
	 *
	 * @var bool
	 */
	public bool $sortable = false;

	/**
	 * Column default sort ordering.
	 *
	 * @var string
	 */
	public string $sortOrder = 'DESC';

	/**
	 * Min column width.
	 *
	 * @var string
	 */
	public string $width = '';

	/**
	 * Column width is flexible.
	 *
	 * @var bool
	 */
	public bool $flexible = false;

	/**
	 * Column is searchable.
	 *
	 * @var bool
	 */
	public bool $searchable = false;

	/**
	 * Column constructor.
	 *
	 * @param string $key
	 */
	public function __construct( string $key ) {
		$this->key = $key;

		return $this;
	}
}

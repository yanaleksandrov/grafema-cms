<?php
namespace Dashboard\Builders\Traits;

trait Row {

	/**
	 * Tag for row wrapper.
	 *
	 * @var string
	 */
	public string $tag = 'div';

	/**
	 * Path to get view for render table row.
	 *
	 * @var string
	 */
	public string $view = 'templates/table/row';

	/**
	 * Attributes list.
	 *
	 * @var array
	 */
	public array $attributes = [];
}

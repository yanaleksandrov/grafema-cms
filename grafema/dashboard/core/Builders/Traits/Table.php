<?php
namespace Dashboard\Builders\Traits;

trait Table {

	/**
	 * Table title.
	 *
	 * @var string
	 */
	public string $title = '';

	/**
	 * Table attributes list.
	 *
	 * @var array
	 */
	public array $attributes = [];

	/**
	 * Data for render table.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $data = [];

	/**
	 * Rows settings.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $rows = [];

	/**
	 * Columns list.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $columns = [];

	/**
	 * Data for render table header.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $headerContent = [];

	/**
	 * Template to get table header.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $headerTemplate = 'templates/table/header';

	/**
	 * Template to get for not found.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $notFoundTemplate = 'templates/states/undefined';

	/**
	 * Data for not found template partial.
	 *
	 * @since 2025.1
	 * @var array
	 */
	public array $notFoundContent;

	/**
	 * Use table header or not.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $cellHeadTemplate = 'cell-head';

	/**
	 * The path to the folder with the template files.
	 *
	 * @since 2025.1
	 * @var string
	 */
	public string $views = 'templates/table';
}

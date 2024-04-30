<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\File\File;

class Files extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'files';

	/**
	 * Get all items.
	 *
	 * @url    GET api/user
	 */
	public function index(): array
	{
		return [
			'method' => 'GET user list',
		];
	}

	/**
	 * Get item by ID.
	 *
	 * @url    GET api/user/$id
	 */
	public function view(): array
	{
		return [
			'method' => 'GET user by ID',
		];
	}

	/**
	 * Create item.
	 *
	 * @url    POST api/user
	 */
	public function create(): array
	{
		return [
			'method' => 'POST create user',
		];
	}

	/**
	 * Update item by ID.
	 *
	 * @url    PUT api/user/$id
	 */
	public function update(): array
	{
		return [
			'method' => 'PUT update user by ID',
		];
	}

	/**
	 * Remove item by ID.
	 *
	 * @url    DELETE api/user/$id
	 */
	public function delete(): array
	{
		return [
			'method' => 'DELETE remove user by ID',
		];
	}

	/**
	 * Upload files from external url.
	 *
	 * @since 1.0.0
	 */
	public static function upload(): File|array
	{
		$files = $_FILES ?? [];
		if ( $files ) {
			foreach ( $files as $file ) {
				return ( new File() )->to( GRFM_UPLOADS . 'i/' )->upload( $file );
			}
		}
		return [];
	}
}

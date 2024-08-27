<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\Api\Crud;
use Grafema\Helpers\Arr;
use Grafema\I18n;

class Options extends \Grafema\Api\Handler
{
	use Crud;

	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'posts';

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
	 * Update item by ID.
	 *
	 * @url    PUT api/user/$id
	 */
	public function update(): array
	{
		$options = Arr::exclude( $_POST, [ 'nonce' ] );
		if ( $options ) {
			print_r( $options );
			foreach ( $options as $option => $value ) {
				print_r( Arr::dot( [ $option => $value ] ) );
				//Option::modify( $option, $value );
			}
		}

		return [
			[
				'fragment' => I18n::_t( 'Options is updated successfully' ),
				'target'   => 'body',
				'method'   => 'notify',
				'custom'   => [
					'type'     => 'success',
					'duration' => 5000,
				],
			],
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
}

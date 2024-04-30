<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

use Grafema\Helpers\Arr;
use Grafema\Json;
use Grafema\Debug;
use Grafema\I18n;

class Options extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'posts';

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
	 * Export new posts.
	 *
	 * @since 1.0.0
	 */
	public static function save(): array
	{
		$options = Arr::exclude( $_POST, [ 'nonce' ] );
		if ( $options ) {
			print_r( $options );
			foreach ( $options as $option => $value ) {
				print_r( Arr::dot( [ $option => $value ] ) );
				//Option::modify( $option, $value );
			}
		}

		echo Json::encode(
			[
				'status'    => 200,
				'benchmark' => Debug::timer( 'getall' ),
				'data'      => [
					[
						'fragment' => I18n::__( 'Options is updated successfully' ),
						'target'   => 'body',
						'method'   => 'notify',
						'custom'   => [
							'type'     => 'success',
							'duration' => 5000,
						],
					],
				],
			]
		);
	}
}

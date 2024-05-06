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
use Grafema\Query\Query;
use Grafema\Json;
use Grafema\File\Csv;
use Grafema\Post\Post;
use Grafema\View;
use Grafema\I18n;

class Posts extends \Grafema\Api\Handler
{
	use Crud;

	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'posts';

	/**
	 * Create item.
	 *
	 * @url    POST api/posts
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
	 * @url    GET api/posts
	 */
	public function index(): array
	{
		return Query::apply(
			[
				'type'     => 'pages',
				'page'     => 1,
				'per_page' => 30,
			],
			function( $posts ) {
				if ( ! is_array( $posts ) ) {
					return $posts;
				}
				return str_replace( '"', "'", json_encode( $posts ) );
			}
		);
	}

	/**
	 * Update item by ID.
	 *
	 * @url    PUT api/posts/$id
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
	 * @url    DELETE api/posts/$id
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
	public static function export(): array
	{
		$format = trim( strval( $_REQUEST['format'] ?? '' ) );
		$types  = $_REQUEST['types'] ?? [];
		$date   = date( 'YmdHis' );

		header( 'Content-type: application/force-download' );
		header( 'Content-Disposition: inline; filename="core-posts-' . $date . '.' . $format . '"' );

		if ( ! is_array( $types ) ) {
			exit;
		}

		echo Query::apply(
			[
				'type'     => $types,
				'per_page' => 99999999,
			],
			function( $posts ) use ( $format ) {
				if ( ! is_array( $posts ) || empty( $posts ) ) {
					return $posts;
				}

				switch ( $format ) {
					case 'json':
						return Json::encode( $posts );
					case 'csv':
						array_unshift( $posts, array_keys( $posts[0] ) );

						return Csv::export( $posts );
					default:
						return $posts;
				}
			}
		);
	}

	/**
	 * Import posts.
	 *
	 * @since 1.0.0
	 */
	public static function import(): array
	{
		$imported = [];
		$filename = $_POST['filename'] ?? '';
		$map      = $_POST['map'] ?? [];
		$status   = $_POST['status'] ?? '';
		$author   = $_POST['author'] ?? '';
		$type     = $_POST['type'] ?? '';

		if ( file_exists( $filename ) ) {
			$rows = Csv::import( $filename );
			foreach ( $rows as $row ) {
				$args = array_filter(
					array_combine( $map, $row ),
					function( $key ) {
						return ! empty( $key );
					},
					ARRAY_FILTER_USE_KEY
				);

				$status = trim( strval( $row['status'] ?? $status ) );
				$author = trim( strval( $row['author'] ?? $author ) );
				if ( ! empty( $status ) ) {
					$args['status'] = $status;
				}

				if ( ! empty( $author ) ) {
					$args['author'] = $author;
				}

				$post_id = Post::add( $type, $args );
				if ( $post_id ) {
					$imported[] = $post_id;
				}
			}
		}

		return [
			'completed' => true,
			'output'    => View::include(
				GRFM_DASHBOARD . 'templates/states/completed.php',
				[
					'title'       => I18n::__( 'Import is complete!' ),
					'description' => I18n::_s( '%d posts was successfully imported. Do you want %sto launch a new import?%s', count( $imported ), '<a href="/dashboard/import">', '</a>' ),
				]
			)
		];
	}
}

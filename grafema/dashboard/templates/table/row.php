<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table row content.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/row.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

if ( ! is_array( $data ) || empty( $row ) || empty( $columns ) ) {
	return;
}

[ $tag, $view, $attributes ] = (
	new Sanitizer(
		(array) $row,
		[
			'tag'        => 'trim',
			'view'       => 'trim',
			'attributes' => 'array',
		]
	)
)->values();

$tag && printf( '<%s>', trim( sprintf( '%s %s', $tag, Arr::toHtmlAtts( $attributes ) ) ) );
foreach ( $columns as $column ) {
	View::print( $column->view, [ 'key' => $column->key, ...$data ] );
}
$tag && printf( '</%s>' . PHP_EOL, $tag );
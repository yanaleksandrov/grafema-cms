<?php
/**
 * Custom field markup.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/custom.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$callback = $args['callback'] ?? null;
if ( is_callable( $callback ) ) {
	ob_start();
	call_user_func( $callback );
	echo ob_get_clean();
}

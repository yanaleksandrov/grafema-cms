<?php
use Grafema\Sanitizer;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/raw.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $class, $cell, $title, $sortable ] = (
    new Sanitizer(
		$args ?? [],
        [
			'key'      => 'class',
			'cell'     => 'key',
			'title'    => 'trim',
			'sortable' => 'bool',
        ]
    )
)->values();

$prop = Sanitizer::prop($args['key'] ?? [] );
?>
<div class="<?php echo $class; ?>" x-text="item.<?php echo $prop; ?>"></div>

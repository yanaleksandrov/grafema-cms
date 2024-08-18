<?php
use Grafema\Sanitizer;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/text.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $key, $cell, $title, $sortable ] = (
    new Sanitizer(
		$args ?? [],
        [
			'key'      => 'key',
			'cell'     => 'key',
			'title'    => 'trim',
			'sortable' => 'bool',
        ]
    )
)->values();
?>
<div class="<?php echo $key; ?>">
	<div x-text="item.<?php echo $key; ?>"></div>
</div>

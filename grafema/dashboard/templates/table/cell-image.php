<?php
use Grafema\Sanitizer;

/**
 * Table image cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/image.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $key ] = (
    new Sanitizer(
		$args ?? [],
        [
            'key' => 'key',
        ]
    )
)->values();
?>
<div class="<?php echo $key; ?>">
    <span class="avatar avatar--rounded" :style="`background-image: url(${item.<?php echo $key; ?>})`"></span>
</div>

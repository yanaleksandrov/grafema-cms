<?php
use Grafema\Sanitizer;

/**
 * Comma-separated list of links
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-badge.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
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
    <span class="badge badge--green-lt" x-text="item.<?php echo $key; ?>"></span>
</div>

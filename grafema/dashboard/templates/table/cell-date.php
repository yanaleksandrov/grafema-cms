<?php
use Grafema\Sanitizer;

/**
 * Publish date
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/date.php
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
	Published
	<div x-text="item.<?php echo $key; ?>"></div>
</div>

<?php
use Grafema\Sanitizer;

/**
 * Table title with actions cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/title.php
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
	<div class="fs-15 lh-sm fw-600" x-text="item.title"></div>
    <div class="mt-1 t-muted" x-text="item.description"></div>
	<div class="df g-1 mt-1 t-muted">
        <span class="dib">by <a href="#" class="dib">WP Engine</a></span> ·
        <span class="dib"><a href="#" class="dib">Settings</a></span> ·
        <a href="#" class="dib t-red">Delete</a>
	</div>
</div>

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
	<div class="fs-14 lh-sm">
		<a href="#" class="fw-600 t-dark" x-text="item.title" @click="$modal.open('jb-add-item')"></a> <span class="t-muted">— Draft</span>
	</div>
	<div class="df aic g-2 mt-1 hover--show">
		<a href="#">View</a>
		<a href="#">Duplicate</a>
		<a class="t-red" href="#">Trash</a>
	</div>
</div>

<?php
use Grafema\Sanitizer;

/**
 * Group
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/group.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $label, $class, $content, $columns ] = (
    new Sanitizer(
        $args ?? [],
        [
			'label'   => 'trim',
			'class'   => 'class:ga-1 fw-600',
			'content' => 'trim',
			'columns' => 'absint:2',
        ]
    )
)->values();
?>
<div class="dg g-7 gtc-5 sm:gtc-1">
	<?php if ( $label ) : ?>
		<div class="<?php echo $class; ?>"><?php echo $label; ?></div>
	<?php endif; ?>
	<div class="dg ga-4 g-7 gtc-<?php echo $columns; ?> sm:gtc-1">
		<?php echo $content; ?>

	</div>
</div>

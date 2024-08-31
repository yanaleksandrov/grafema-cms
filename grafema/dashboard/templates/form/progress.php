<?php
use Grafema\Esc;
use Grafema\Sanitizer;

/**
 * Progress bar
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/progress.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $name, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes, $max, $min, $value, $speed ] = ( new Grafema\Sanitizer(
	$args ?? [],
	[
		'name'        => 'name',
		'label'       => 'trim',
		'class'       => 'class:dg g-1',
		'label_class' => 'class:df aic jcsb fw-600 mb-1',
		'reset'       => 'bool:false',
		'before'      => 'trim',
		'after'       => 'trim',
		'instruction' => 'trim',
		'tooltip'     => 'attribute',
		'copy'        => 'bool:false',
		'conditions'  => 'array',
		'attributes'  => 'array',
		// progress
		'max'         => 'absint:0',
		'min'         => 'absint:0',
		'value'       => 'absint:100',
		'speed'       => 'absint:1000',
	]
) )->values();
?>
<div class="<?php echo $class; ?>">
	<div class="<?php echo $label_class; ?>">
		<?php echo $label; ?>
	</div>
	<div class="progress" x-progress.<?php printf( '%d.%d.%d.%d', $max, $min, $value, $speed ); ?>ms></div>
	<?php if ( $instruction ) : ?>
		<div class="mt-1 fw-600 lh-xs"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

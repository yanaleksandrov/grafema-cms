<?php
use Grafema\Sanitizer;
use Grafema\Helpers\Arr;

/**
 * Input field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/number.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $uid, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes ] = ( new Sanitizer(
	$args ?? [],
	[
		'uid'         => 'key',
		'label'       => 'trim',
		'class'       => 'class:field',
		'label_class' => 'class:field-label',
		'reset'       => 'bool:false',
		'before'      => 'trim',
		'after'       => 'trim',
		'instruction' => 'trim',
		'tooltip'     => 'attribute',
		'copy'        => 'bool:false',
		'conditions'  => 'array',
		'attributes'  => 'array',
	]
) )->values();
?>
<div class="field <?php echo $class; ?>">
	<?php if ( $label ) : ?>
		<div class="<?php echo $label_class; ?>"><?php echo $label; ?></div>
	<?php endif; ?>
	<div class="field-item">
		<i class="ph ph-minus" @click="<?php echo $uid; ?>--"></i>
		<input type="number" name="<?php echo $uid; ?>"<?php echo Arr::toHtmlAtts( $attributes ); ?> x-model.fill="<?php echo $uid; ?>" @keydown.e.prevent>
		<i class="ph ph-plus" @click="<?php echo $uid; ?>++"></i>
	</div>
	<?php if ( $instruction ) : ?>
		<div class="field-instruction"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

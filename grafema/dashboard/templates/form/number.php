<?php
use Grafema\Esc;
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

[ $name, $label, $label_class, $class, $reset, $instruction, $attributes ] = (
    new Grafema\Sanitizer(
        $args ?? [],
        [
			'name'        => 'attribute|key',
			'label'       => 'trim',
			'label_class' => 'class:field-label',
			'class'       => 'class:field',
			'reset'       => 'bool:false',
			'instruction' => 'trim',
			'attributes'  => 'array',
        ]
    )
)->values();
?>
<div class="field <?php echo $class; ?>">
	<?php if ( $label ) : ?>
		<div class="<?php echo $label_class; ?>"><?php Esc::html( $label ); ?></div>
	<?php endif; ?>
	<div class="field-item">
		<i class="ph ph-minus" @click="<?php Esc::attr( $name ); ?>--"></i>
		<input type="number" name="<?php Esc::attr( $name ); ?>"<?php echo Arr::toHtmlAtts( $attributes ); ?> x-model.fill="<?php Esc::attr( $name ); ?>" @keydown.e.prevent>
		<i class="ph ph-plus" @click="<?php Esc::attr( $name ); ?>++"></i>
	</div>
	<?php if ( $instruction ) : ?>
		<div class="field-instruction"><?php Esc::html( $instruction ); ?></div>
	<?php endif; ?>
</div>

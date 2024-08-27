<?php
use Grafema\Esc;
use Grafema\I18n;
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/**
 * Input field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/input.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $placeholder, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $attributes, $conditions] = (
    new Sanitizer(
        $args ?? [],
        [
            'label'       => 'trim',
            'name'        => 'key',
            'value'       => 'trim|attribute',
            'placeholder' => 'trim',
            'class'       => 'class:field',
	        'label_class' => 'class:field-label',
            'reset'       => 'bool:false',
            'before'      => 'trim',
            'after'       => 'trim',
            'instruction' => 'trim',
            'tooltip'     => 'trim|attribute',
            'copy'        => 'bool:false',
            'attributes'  => 'array',
            'conditions'  => 'trim',
            'options'     => 'array',
        ]
    )
)->values();

$attributes['value'] = $value;
?>
<div class="<?php echo $class; ?>">
	<?php if ( $label ) : ?>
		<div class="<?php echo $label_class; ?>"><?php Esc::html( $label ); ?></div>
	<?php endif; ?>
	<label class="field-item">
		<?php echo $before; ?>
		<input<?php echo Arr::toHtmlAtts( $attributes ); ?>>
		<?php
		echo $after;
		if ( $copy ) :
			?>
			<i class="ph ph-copy" title="<?php I18n::t_attr( 'Copy' ); ?>" @click="$copy(<?php echo $name; ?>)"></i>
		<?php
		endif;
		if ( $tooltip ) :
			?>
			<i class="ph ph-info" x-tooltip.click.prevent="'<?php echo $tooltip; ?>'"></i>
		<?php endif; ?>
	</label>
	<?php if ( $instruction ) : ?>
		<div class="field-instruction"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

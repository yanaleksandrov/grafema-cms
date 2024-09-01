<?php
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/*
 * Checkbox
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/checkbox.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $name, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes, $options ] = ( new Sanitizer(
	$args ?? [],
	[
		'name'        => 'name',
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
		'options'     => 'array',
	]
) )->values();

$prop = Sanitizer::prop( $attributes['name'] ?? $name );
?>
<div class="<?php echo $class; ?>" x-data="{<?php echo $prop; ?>: []}">
	<?php if ( $label ) { ?>
		<div class="<?php echo $label_class; ?>">
			<?php
			echo $label;
            if ( $reset ) :
                ?>
				<span class="ml-auto t-red" @click.prevent="<?php echo $prop; ?> = []; setTimeout(() => $dispatch('change'), 0)" x-show="<?php echo $prop; ?>.length > 0" x-cloak><?php I18n::t( 'Reset' ); ?></span>
			<?php endif; ?>
		</div>
		<?php
	}

	foreach ( $options as $option => $text ) {
	    $optionName = sprintf( '%s.%s', $name, $option );
		$attributes = [
			'type'         => 'checkbox',
		    'value'        => $option,
            'name'         => $name,
			'x-model.fill' => $prop,
        ];
		?>
		<label class="df aic">
			<input <?php echo Arr::toHtmlAtts( $attributes ); ?>>
			<span class="df aic mw"><?php echo $text; ?></span>
		</label>
		<?php
	}

	if ( $instruction ) :
	    ?>
		<div class="field-instruction ml-7"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

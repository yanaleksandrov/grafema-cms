<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/*
 * Checkbox
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/checkbox.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $placeholder, $class, $reset, $before, $after, $instruction, $tooltip, $copy, $attributes, $conditions, $options] = (
    new Sanitizer(
        $args ?? [],
        [
			'label'       => 'trim',
			'name'        => 'key',
			'value'       => 'attribute',
			'placeholder' => 'trim',
			'class'       => 'class:df aic jcsb fw-600 mb-2',
			'reset'       => 'bool:false',
			'before'      => 'trim',
			'after'       => 'trim',
			'instruction' => 'trim',
			'tooltip'     => 'attribute',
			'copy'        => 'bool:false',
			'attributes'  => 'array',
			'conditions'  => 'trim',
			'options'     => 'array',
        ]
    )
)->values();
?>
<div class="dg g-1" x-data="{<?php echo $name; ?>: []}">
	<?php if ( $label ) { ?>
		<div class="<?php echo $class; ?>">
			<?php
			Esc::html( $label );
            if ( $reset ) {
                ?>
				<span class="ml-auto t-red" @click.prevent="<?php echo $name; ?> = []; setTimeout(() => $dispatch('change'), 0)" x-show="<?php echo $name; ?>.length > 0" x-cloak><?php I18n::e( 'Reset' ); ?></span>
			<?php } ?>
		</div>
		<?php
	}

	foreach ( $options as $option => $text ) {
	    $optionName = sprintf( '%s.%s', $name, $option );
		$attributes = [
			'type'         => 'checkbox',
		    'value'        => $option,
            'name'         => $name,
			'x-model.fill' => $name,
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
		<div class="fs-13 t-muted lh-xs ml-7"><?php Esc::html( $instruction ); ?></div>
	<?php endif; ?>
</div>

<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/*
 * Toggle
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/toggle.php
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
			'conditions'  => 'array',
			'options'     => 'array',
        ]
    )
)->values();
?>
<div class="dg g-1">
    <label class="toggle">
        <input class="toggle__checkbox" <?php echo Arr::toHtmlAtts( $attributes ); ?>>
        <span class="toggle__switch"></span>
        <span class="toggle__label">
            <?php
            Esc::html( $label );
            if ( $instruction ) : ?>
                <span class="toggle__description"><?php Esc::html( $instruction ); ?></span>
			<?php endif; ?>
        </span>
    </label>
</div>

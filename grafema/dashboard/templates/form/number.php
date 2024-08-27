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

[$type, $name, $label, $label_class, $class, $reset, $description, $attributes] = (
    new Grafema\Sanitizer(
        $args ?? [],
        [
			'type'        => 'key:number',
			'name'        => 'attribute|key',
			'label'       => 'trim',
			'label_class' => 'class:df aic jcsb fw-600',
			'class'       => 'class:dg g-1',
			'reset'       => 'bool:false',
			'description' => 'trim',
			'attributes'  => 'array',
        ]
    )
)->values();

$attributes = [
	...$attributes,
	'type'         => $type,
	'name'         => $name,
	'x-model.fill' => $name,
];

$fieldAttributes = [ 'data-before' => '', 'data-after' => 'of 25' ];
?>
<div class="<?php echo $class; ?>">
	<?php if ( $label ) : ?>
		<span class="<?php echo $label_class; ?>"><?php Esc::html( $label ); ?></span>
	<?php endif; ?>
	<label class="field"<?php echo Arr::toHtmlAtts( $fieldAttributes ); ?>>
		<i class="ph ph-minus fs-12" @click="<?php Esc::attr( $name ); ?>--"></i>
		<?php printf( '<input%s>', Arr::toHtmlAtts( $attributes ) ); ?>
		<i class="ph ph-plus fs-12" @click="<?php Esc::attr( $name ); ?>++"></i>
	</label>
	<?php if ( $description ) : ?>
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $description ); ?></div>
	<?php endif; ?>
</div>

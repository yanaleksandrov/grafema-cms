<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/**
 * Media field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/media.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $type, $name, $label, $label_class, $class, $description, $attributes, $tooltip ] = (
    new Sanitizer(
        $args ?? [],
        [
			'type'        => 'key:text',
			'name'        => 'attribute|key',
			'label'       => 'trim:field',
			'label_class' => 'class:df aic jcsb fw-500',
			'class'       => 'class:dg g-1',
			'description' => 'trim',
			'attributes'  => 'array',
			'tooltip'     => 'trim|attribute',
        ]
    )
)->values();
?>
<div class="<?php echo $class; ?>" x-data="{<?php echo $name; ?>: []}">
	<div class="dg g-1" x-media>
		<?php if ( $label ) : ?>
			<span class="<?php echo $label_class; ?>"><?php Sanitizer::html( $label ); ?></span>
		<?php endif; ?>
		<template x-for="(item, id) in <?php echo $name; ?>">
			<img class="" :src="item.url" x-init="console.log(item.url)" alt="" width="200" height="200">
		</template>
		<input<?php echo Arr::toHtmlAtts( $attributes ); ?>>
		<?php if ( $tooltip ) : ?>
			<i class="ph ph-info" x-tooltip.click.prevent="'<?php echo $tooltip; ?>'"></i>
		<?php endif; ?>
	</div>
	<?php if ( $description ) : ?>
		<div class="fs-13 t-muted lh-xs"><?php echo $description; ?></div>
	<?php endif; ?>
</div>

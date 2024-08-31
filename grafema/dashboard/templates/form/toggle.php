<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/**
 * Toggle
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/toggle.php
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
		'class'       => 'class:toggle',
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
<label class="<?php echo $class; ?>">
	<input class="toggle__checkbox" <?php echo Arr::toHtmlAtts( [ ...$attributes, 'type' => 'checkbox', 'name' => $name, 'x-model.fill' => $prop ] ); ?>>
	<span class="toggle__switch"></span>
	<?php if ( $label || $instruction ) : ?>
		<span class="toggle__label">
			<?php
			echo $label;
			if ( $instruction ) :
				?>
				<span class="toggle__description"><?php echo $instruction; ?></span>
			<?php endif; ?>
		</span>
	<?php endif; ?>
</label>

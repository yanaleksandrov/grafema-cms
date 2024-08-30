<?php
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/*
 * Textarea field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/textarea.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $uid, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes, $options ] = ( new Sanitizer(
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
		'options'     => 'array',
	]
) )->values();

$value = Sanitizer::attribute( $attributes['value'] ?? '' );
?>
<div class="<?php echo $class; ?>"<?php echo $conditions ? " x-show=\"{$conditions}\" x-cloak" : ''; ?>>
	<?php if ( $label ) : ?>
		<span class="<?php echo $label_class; ?>"><?php echo $label; ?></span>
	<?php endif; ?>
	<label class="field-item">
		<?php echo $before; ?>
		<textarea<?php echo Arr::toHtmlAtts( $attributes ); ?>><?php echo $value; ?></textarea>
		<?php
		echo $after;
		if ( $copy ) {
			?>
			<i class="ph ph-copy" title="<?php I18n::t_attr( 'Copy' ); ?>" @click="$copy(<?php echo $uid; ?>)"></i>
			<?php
		}
		if ( $tooltip ) {
			?>
			<i class="ph ph-info" x-tooltip.click.prevent="'<?php echo $tooltip; ?>'"></i>
			<?php
		}
		?>
	</label>
	<?php if ( $instruction ) : ?>
		<div class="field-instruction"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

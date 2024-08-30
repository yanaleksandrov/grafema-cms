<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/**
 * Submit button
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/submit.php
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
<div class="<?php echo $class; ?>">
	<button type="submit"<?php echo Arr::toHtmlAtts( $attributes ); ?>><?php echo $label; ?></button>
</div>

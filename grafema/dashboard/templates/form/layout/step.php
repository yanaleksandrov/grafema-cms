<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/**
 * Form step
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/step.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $attributes, $content, $step ] = (
    new Sanitizer(
        $args ?? [],
        [
            'attributes' => 'array',
            'content'    => 'trim',
            'step'       => 'absint:1',
        ]
    )
)->values();
?>
<!-- step <?php echo $step; ?> -->
<div <?php echo Arr::toHtmlAtts( $attributes ); ?>>
	<?php echo $content; ?>
</div>


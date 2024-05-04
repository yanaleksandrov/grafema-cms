<?php
use Grafema\Esc;
use Grafema\Sanitizer;

/**
 * Progress bar
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/progress.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$name, $label, $label_class, $class, $instruction, $max, $min, $value, $speed] = (
    new Grafema\Sanitizer(
        $args ?? [],
        [
            'name'        => 'attribute|key',
            'label'       => 'trim',
            'label_class' => 'class:df aic jcsb fw-600 mb-1',
            'class'       => 'class:dg g-1',
            'instruction' => 'trim',
            'max'         => 'absint:0',
            'min'         => 'absint:0',
            'value'       => 'absint:100',
            'speed'       => 'absint:1000',
        ]
    )
)->values();
?>
<div class="<?php echo $class; ?>">
	<div class="<?php echo $label_class; ?>">
		<?php Esc::html( $label ); ?>
	</div>
	<div class="progress" x-progress.<?php printf( '%d.%d.%d.%d', $max, $min, $value, $speed ); ?>ms></div>
	<?php if ( $instruction ) : ?>
		<div class="mt-1 fw-600 lh-xs"><?php Esc::html( $instruction ); ?></div>
	<?php endif; ?>
</div>

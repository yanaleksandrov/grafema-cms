<?php
use Grafema\Sanitizer;

/**
 * Form title.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/title.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $class, $instruction] = (
    new Sanitizer(
        $args ?? [],
        [
            'label'       => 'trim',
            'name'        => 'key',
            'class'       => 'class:t-center',
            'instruction' => 'trim',
        ]
    )
)->values();
?>
<header class="<?php echo $class; ?>">
	<h4><?php echo $label; ?></h4>
	<?php if ( $instruction ) { ?>
		<p class="t-muted"><?php echo $instruction; ?></p>
	<?php } ?>
</header>

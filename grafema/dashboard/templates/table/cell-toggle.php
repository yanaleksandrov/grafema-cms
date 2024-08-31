<?php
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-toggle.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$key = Sanitizer::key($args['key'] ?? [] );
?>
<div class="<?php echo $key; ?>">
	<?php
	View::print(
		'templates/form/toggle',
		[
			'type'        => 'toggle',
			'uid'         => 'uid',
			'label'       => '',
			'class'       => '',
			'label_class' => '',
			'reset'       => 0,
			'before'      => '',
			'after'       => '',
			'instruction' => '',
			'tooltip'     => '',
			'copy'        => 0,
			'sanitizer'   => '',
			'validator'   => '',
			'conditions'  => [],
			'attributes'  => [
				':checked' => "item.$key === true",
				'@change'  => '$ajax("plugin/deactivate")',
			],
		]
	);
	?>
</div>

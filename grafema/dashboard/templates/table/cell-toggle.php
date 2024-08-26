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
			'label'       => '',
			'name'        => '',
			'value'       => '',
			'placeholder' => '',
			'class'       => '',
			'reset'       => 0,
			'required'    => 0,
			'copy'        => 0,
			'before'      => '',
			'after'       => '',
			'tooltip'     => '',
			'instruction' => '',
			'attributes'  => [
			    ':checked' => "item.$key === true",
				'@change'  => '$ajax("plugin/deactivate")',
            ],
			'conditions'  => [],
		]
	);
	?>
</div>

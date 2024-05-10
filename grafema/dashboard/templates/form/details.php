<?php
use Grafema\Sanitizer;

/*
 * Details html tag: button with dropdown menu
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/details.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $instruction, $class, $content] = ( new Sanitizer(
	$args ?? [],
	[
		'label'       => 'trim',
		'instruction' => 'html',
		'class'       => 'class:df',
		'content'     => 'trim',
	]
) )->values();

if ( empty( $label ) ) {
	return;
}
?>
<details class="details" @click.outside="$el.removeAttribute('open')">
	<summary class="details__summary <?php echo $class; ?>"><?php echo $label; ?></summary>
	<div class="details__content">
		<?php if ( $instruction ) { ?>
			<div class="details__head">
				<?php echo $instruction; ?>
			</div>
		<?php } ?>
		<div class="details__body">
			<?php echo $content; ?>
		</div>
	</div>
</details>

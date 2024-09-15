<?php
use Grafema\Sanitizer;
use Grafema\Url;

/**
 * Different site states.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/global/state.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $class, $title, $description, $icon ] = (
	new Sanitizer(
		$args ?? [],
		[
			'class'       => 'class:dg jic m-auto t-center p-5 mw-320',
			'title'       => 'trim',
			'description' => 'trim',
			'icon'        => 'id:state-empty-page',
		]
	)
)->values();
?>
<div class="<?php echo $class; ?>">
	<?php if ( $icon ) : ?>
		<svg><use xlink:href="<?php echo Url::dashboard( '/assets/sprite.svg#' . $icon ); ?>"></use></svg>
		<?php
	endif;
	if ( $title ) :
		?>
		<h4 class="mt-4"><?php echo $title; ?></h4>
		<?php
	endif;
	if ( $description ) :
		?>
		<p class="t-muted"><?php echo $description; ?></p>
	<?php endif; ?>
</div>

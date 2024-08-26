<?php
use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table media element in storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-media.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $title, $sizeHumanize ] = (
	new Sanitizer(
		$args ?? [],
		[
			'title'        => 'attribute',
			'sizeHumanize' => 'trim',
		]
	)
)->values();

$src = Sanitizer::attribute( $args['sizes']['thumbnail']['url'] ?? ( $args['url'] ?? $args['icon'] ) );
?>
<div class="storage__item" @click="$modal.open('grafema-modals-post')">
	<img class="storage__image" src="<?php echo $src; ?>" alt="<?php echo $title; ?>" width="200" height="200">
	<div class="storage__meta">
		<div class="storage__data"><?php echo $sizeHumanize; ?></div>
	</div>
</div>
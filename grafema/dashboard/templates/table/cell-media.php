<?php
use Grafema\Sanitizer;

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

$src = Sanitizer::attribute( $args['sizes']['thumbnail']['url'] ?? $args['url'] ?? $args['icon'] ?? '' );
?>
<div class="storage__item" @click="$dialog.show('grafema-modals-media-editor',{ref: '$refs.grafemaMediaEditor'})">
	<img class="storage__image" :src="item.sizes?.thumbnail?.url || item.url || item.icon" alt="" width="200" height="200">
	<div class="storage__meta">
		<div class="storage__data" x-text="item.sizeHumanize"></div>
	</div>
</div>
<?php if ( 0 ) : ?>
	<div class="storage__item" @click="$dialog.show('grafema-modals-post')">
		<img class="storage__image" src="<?php echo $src; ?>" alt="<?php echo $title; ?>" width="200" height="200">
		<div class="storage__meta">
			<div class="storage__data"><?php echo $sizeHumanize; ?></div>
		</div>
	</div>
<?php endif; ?>
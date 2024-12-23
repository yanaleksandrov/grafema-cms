<?php
use Grafema\Sanitizer;
use Grafema\Url;
use Grafema\I18n;

/**
 * Table media element in storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/views/table/cell-media.php
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
<div class="storage__item" @click="$dialog.open('tmpl-media-editor', item)">
	<template x-if="item.url || item.icon">
		<img class="storage__image" :src="item.sizes?.thumbnail?.url || item.url || item.icon" alt="" width="200" height="200">
		<div class="storage__meta">
			<div class="storage__data" x-text="item.sizeHumanize"></div>
		</div>
	</template>
	<template x-if="!item.url && !item.icon">
		<img class="storage__image" src="<?php echo Url::dashboard( '/assets/images/files/broken.svg' ) ?>" title="<?php I18n::t_attr( 'Image is broken' ); ?>" width="40" height="40">
	</template>
</div>

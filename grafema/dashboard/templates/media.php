<?php
use Grafema\I18n;
use Grafema\View;
use Grafema\Media;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/storage.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main" x-data="{showUploader: false, percent: 0, uploader: null}">
    <?php
    View::part(
        'templates/table/header',
        [
            'title' => I18n::__( 'Media Library' ),
			'show'  => false,
        ]
    );
    ?>
	<template x-if="grafema.posts.length">
		<div class="storage">
			<template x-for="post in grafema.posts">
				<div class="storage__item" @click="$modal.open('grafema-posts-creator')">
					<img class="storage__image" :src="post.sizes?.thumbnail?.url || post.url || post.icon" alt="" width="200" height="200">
					<div class="storage__meta">
						<div class="storage__data" x-text="post.sizeHumanize"></div>
					</div>
				</div>
			</template>
		</div>
	</template>
	<template x-if="!grafema.posts.length">
		<?php
		View::part(
			'templates/states/undefined',
			[
				'title'       => I18n::__( 'Files in library is not found' ),
				'description' => I18n::__( 'They have not been uploaded or do not match the filter parameters' ),
			]
		);
        ?>
	</template>
    <div x-intersect="$ajax('media/get').then(response => grafema.posts = response.posts)"></div>
</div>

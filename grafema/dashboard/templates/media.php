<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/media.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
    <?php
    View::print(
        'templates/table/header',
        [
            'title' => I18n::__( 'Media Library' ),
			'show'  => false,
        ]
    );
    ?>
	<template x-if="index.posts.length">
		<div class="storage">
			<template x-for="post in index.posts">
				<div class="storage__item" @click="$modal.open('grafema-modals-post')">
					<img class="storage__image" :src="post.sizes?.thumbnail?.url || post.url || post.icon" alt="" width="200" height="200">
					<div class="storage__meta">
						<div class="storage__data" x-text="post.sizeHumanize"></div>
					</div>
				</div>
			</template>
		</div>
	</template>
	<template x-if="!index.posts.length">
		<?php
		View::print(
			'templates/states/undefined',
			[
				'title'       => I18n::__( 'Files in library is not found' ),
				'description' => I18n::__( 'They have not been uploaded or do not match the filter parameters' ),
			]
		);
        ?>
	</template>
    <div x-intersect="$ajax('media/get').then(response => index.posts = response.posts)"></div>
</div>

<?php
use Grafema\I18n;
use Grafema\Json;
use Grafema\Patterns;
use Grafema\Url;
use Grafema\View;
use Grafema\Query\Query;

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

$media = Query::apply(
	[
		'type'     => 'media',
		'page'     => 1,
		'per_page' => 30,
	],
	function ( $posts ) {
		if ( ! is_array( $posts ) ) {
			return $posts;
		}

		foreach ( $posts as $i => $post ) {
			$thumbnail = Patterns\Registry::get( 'images.thumbnail' );

			if ( ! is_array( $post ) || ! is_array( $thumbnail ) ) {
				continue;
			}

			[$name, $mime, $width, $height] = ( new Grafema\Sanitizer(
				$thumbnail,
				[
					'name'   => 'text',
					'mime'   => 'mime',
					'width'  => 'absint',
					'height' => 'absint',
				]
            ) )->values();

			if ( ! $mime || ! $width || ! $height ) {
				continue;
			}

			$filepath          = sprintf( '%si/%sx%s/%s', GRFM_UPLOADS, $width, $height, $post['slug'] ?? '' );
			$allowed_extension = [
				'image/jpeg' => IMAGETYPE_JPEG,
				'image/png'  => IMAGETYPE_PNG,
				'image/webp' => IMAGETYPE_WEBP,
			];
			if ( ! in_array( $mime, array_keys( $allowed_extension ), true ) ) {
				continue;
			}
			$posts[$i]['thumbnail'] = Url::fromPath( $filepath );
		}

		return str_replace( '"', "'", Json::encode( $posts ) );
	}
);
?>
<!--<div class="grafema-filter">-->
<!--	--><?php //echo Dashboard\Form::view( 'grafema-posts-filter' ); ?>
<!--</div>-->
<div class="grafema-main" x-data="{showUploader: false, files: <?php echo $media; ?>}">
    <?php
    View::part(
        'templates/table/header',
        [
            'title' => I18n::__( 'Media Library' ),
			'show'  => $media !== '[]',
        ]
    );
    ?>
	<template x-if="files.length">
		<div class="storage">
			<template x-for="file in files">
				<div class="storage__item" @click="$modal.open('grafema-posts-creator')">
					<img class="storage__image" :src="file.thumbnail" alt="" width="200" height="200">
					<div class="storage__meta">
						<div class="storage__data" x-text="file.size"></div>
					</div>
				</div>
			</template>
		</div>
	</template>
	<template x-if="!files.length">
		<?php
		View::part(
			'templates/states/undefined',
			[
				'title'       => I18n::__( 'Media files is not found' ),
				'description' => I18n::__( 'The files were not found, probably because you did not download them or they do not match the specified filter parameters' ),
			]
		);
        ?>
	</template>
</div>

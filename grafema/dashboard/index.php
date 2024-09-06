<?php
use Grafema\Hook;
use Grafema\I18n;
use Grafema\Is;
use Grafema\Option;
use Grafema\View;
use Grafema\Sanitizer;
use Grafema\User;
use Grafema\Tree;

/**
 * Remove the duplicate access to the console at two addresses:
 * "dashboard" and "dashboard/index", leave only the first one.
 *
 * @since 2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	$dashboard_url = trim( $_SERVER['SCRIPT_URI'] ?? '' );
	if ( $dashboard_url ) {
		header( 'Location: ' . $dashboard_url . 'profile' );
	}
	exit;
}

$slug       = Sanitizer::trim( $args['slug'] ?? '' );
$start_time = microtime( true );
// print_r(
//	Query::apply(
//		[
//			'type'           => [ 'pages', 'media' ],
//			'status'         => null,
//			'page'           => 1,
//			'per_page'       => 99,
//			'offset'         => 2,
//			'order'          => 'DESC',
//			'orderby'        => 'rand',
//			's'              => 'hello exception',
//			'sentence'       => false,
//			'boolean_mode'   => true,
//			'slug'           => 'hello-world',
//			'slug_strict'    => true,
//			'title'          => 'Title 6',
//			'status'         => 'draft',
//			'nicename'       => 'alexandrov',
//			'author__in'     => [ 2, 3, 4, 5 ],
//			'author__not_in' => [ 4 ],
//			'post__in'       => [ 2, 3, 4, 5 ],
//			'post__not_in'   => [ 4 ],
//			'parent__in'     => [ 2, 3, 4, 5 ],
//			'parent__not_in' => [ 4 ],
//			'discussion'     => 'closed',
//			'comments'       => [
//				'value'   => 8,
//				'compare' => '<',
//			],
//			'views'          => [
//				'value'   => 0,
//				'compare' => '>=',
//			],
//			'dates'          => [
//				'relation' => 'AND',
//				[
//					'human_date_time' => '-23 days',
//					'compare'         => '<=',
//				],
//				[
//					'column'  => 'modified',
//					'compare' => 'BETWEEN',
//					'year'    => [ 2015, 2016 ],
//				],
//			],
//			'fields'   => [
//				'relation' => 'AND',
//				[
//					'key'     => 'price',
//					'value'   => '32434',
//					'compare' => '<=',
//					'type'    => 'NUMERIC',
//				],
//				[
//					'key'     => 'price',
//					'value'   => '30006',
//					'compare' => '>=',
//				],
//			],
//		]
//	)
// );
// echo 'Time:  ' . number_format( ( microtime( true ) - $start_time ), 4 ) . " Seconds\n";
// exit;
?>
<!DOCTYPE html>
<html lang="<?php I18n::locale(); ?>">
<head>
	<meta charset="<?php Option::attr( 'charset', 'UTF-8' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Menu</title>
    <link rel="apple-touch-icon" sizes="180x180" href="/dashboard/assets/images/favicons/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/dashboard/assets/images/favicons/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/dashboard/assets/images/favicons/favicon-16x16.png">
    <link rel="manifest" href="/dashboard/assets/images/favicons/site.webmanifest">
    <link rel="mask-icon" href="/dashboard/assets/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
    <meta name="msapplication-TileColor" content="#da532c">
    <meta name="theme-color" content="#ffffff">
	<?php
	/**
	 * Prints scripts or data before the closing body tag on the dashboard.
	 *
	 * @since 2025.1
	 */
	Hook::apply( 'grafema_dashboard_header' );
	?>
</head>
<body x-data="grafema">
	<?php if ( Is::installed() && User::logged() ) { ?>
		<div class="grafema" :class="showMenu && 'active'">
            <div class="grafema-bar">
                <div class="grafema-bar-menu" :class="showMenu && 'active'" @click="showMenu = !showMenu">
                    <i class="ph ph-list"></i>
                </div>
                <?php
                View::print( 'templates/menu-bar' );

                View::print( 'templates/global/user-account' );
                ?>
            </div>
			<!-- interface panel start -->
			<div class="grafema-panel">
                <a href="<?php echo Grafema\Url::site(); ?>" target="_blank">
	                <img src="<?php echo Grafema\Url::site( '/dashboard/assets/images/logo.svg' ); ?>" width="34" height="34" alt="Grafema Logo">
                </a>
                <?php View::print( 'templates/menu-panel' ); ?>
			</div>
			<!-- interface side bar start -->
            <?php
            View::print( 'templates/menu' );

            View::print( 'templates/' . $slug );
            ?>
			<!-- interface board start -->
			<div class="grafema-board">
				<a href="#" class="dif aic t-dark" title="Get Support"><i class="ph ph-headset fs-12"></i> support</a>
				<a href="#" class="dif aic t-dark" title="Grafema CMS version"><i class="ph ph-git-branch fs-12"></i> 2025.1</a>
			</div>
		</div>
    	<?php
	} else {
	    ?>
		<div class="df aic jcc p-6">
			<?php View::print( 'templates/' . $slug ); ?>
		</div>
		<?php
	}
	?>

	<!-- dialog windows start -->
	<dialog id="grafema-dialog" class="dialog" :class="$store.dialog?.class" aria-labelledby="dialog-header" aria-describedby="dialog-content">
		<div class="dialog-wrapper" @click.outside="$dialog.close()">
			<div class="dialog-header">
				<template x-if="$store.dialog?.title">
					<h6 class="dialog-title" x-text="$store.dialog.title"></h6>
				</template>
				<button class="dialog-close" type="button" @click="$dialog.close()"></button>
			</div>
			<template x-if="$store.dialog?.content">
				<div class="dialog-content" x-html="$store.dialog.content"></div>
			</template>
		</div>
	</dialog>

	<!-- notices start -->
	<template x-if="$store.notice.length">
		<div class="notice">
			<template x-for="(item, id) in $store.notice.items">
				<div class="notice__item" :class="item.classes()">
					<div class="notice__msg" x-html="item.message"></div>
					<div class="notice__close" x-show="item.closable" :style="`--anim:${item.anim}`" @click="$store.notice.close(id)"></div>
				</div>
			</template>
		</div>
	</template>

	<!-- media editor template start -->
	<template id="tmpl-media-editor" x-init="$dialog.init(() => $ajax('media/get'))">
		<?php Dashboard\Form::print( 'grafema-media-editor', GRFM_DASHBOARD . 'forms/grafema-media-editor.php' ); ?>
	</template>

	<!-- media uploader template start -->
	<template id="tmpl-media-uploader">
		<?php Dashboard\Form::print( 'grafema-files-uploader', GRFM_DASHBOARD . 'forms/grafema-files-uploader.php' ); ?>
	</template>

	<!-- post editor template start -->
	<template id="tmpl-post-editor" x-init="$dialog.init(() => postEditorDialog)">
		<?php Dashboard\Form::print( 'grafema-posts-creator', GRFM_DASHBOARD . 'forms/grafema-posts-creator.php' ); ?>
	</template>

	<!-- email editor template start -->
	<template id="tmpl-email-editor" x-init="$dialog.init(() => emailDialog)">
		<div class="dg gtc-3 g-8">
			<div class="ga-1">
				<?php Dashboard\Form::print( 'grafema-emails-creator', GRFM_DASHBOARD . 'forms/grafema-emails-creator.php' ); ?>
			</div>
			<div class="ga-2">
				<?php
				View::print(
					GRFM_DASHBOARD . 'templates/mails/wrappers.php',
					[
						'body_template' => GRFM_DASHBOARD . 'templates/mails/reset-password.php',
					]
				);
				?>
			</div>
		</div>
	</template>

	<!-- selfie maker start -->
	<template id="take-selfie">
		<div x-data="{second: '', showImg: ''}">
			<div x-init="$stream.start($refs)" style="position: relative; overflow: hidden;">
				<video x-ref="video" class="db mw" autoplay style="object-fit: cover; aspect-ratio: 4/3;"></video>
				<canvas x-ref="canvas" x-show="!showImg" style="border-radius: 20rem; width: 240px; height: 240px; position: absolute; margin: auto; inset: 0; box-shadow: 0 0 0 999px rgb(255 255 255 / 60%);"></canvas>
				<img x-ref="image" x-show="showImg" x-cloak alt="" src="/dashboard/assets/images/1x1.png" style="border-radius: 20rem; width: 240px; height: 240px; position: absolute; margin: auto; inset: 0; box-shadow: 0 0 0 999px rgb(255 255 255 / 98%);">
			</div>
			<div class="modal__body bg-white t-center" style="position: relative;">
				<div
					class="fs-48"
					x-show="second > 0"
					x-text="second"
					:style="second && 'position: fixed; top: 1rem; left: 0; right: 0; margin: 0 auto; transition: all 1s; animation: ticker 1s ease infinite;'"
				></div>

				<div x-show="!showImg">
					<h6>Center your face</h6>
					<div class="fs-14 t-muted mt-2 pl-4 pr-4">
						Align your face to the center of the selfie area and then take a photo
					</div>
					<div class="df jcsb mt-6 mw">
						<button type="button" class="btn btn--outline" @click="$dialog.close(), $stream.stop()">Cancel</button>
						<button class="btn btn--primary" @click="$countdown.start(3, () => second = $countdown.second, () => showImg = $stream.snapshot($refs))">Take Selfie</button>
					</div>
				</div>

				<div x-show="showImg">
					<h6>Check quality</h6>
					<div class="fs-14 t-muted mt-2 pl-4 pr-4">
						Make sure your face is not blurred or out of the frame before continuing
					</div>
					<div class="df jcsb mt-6 mw">
						<button class="btn btn--outline" type="button" @click="showImg = ''"><i class="ph ph-arrows-clockwise"></i> Take a new</button>
						<button class="btn btn--primary" type="button" @click="showImg = ''"><i class="ph ph-user-focus"></i> Use this photo</button>
					</div>
				</div>
			</div>
		</div>
		<div style="position: absolute; right: 0; top: 0;">
			<button type="button" class="modal__close" @click="$dialog.close(), $stream.stop()"></button>
		</div>
	</template>
    <?php
	/**
	 * Prints scripts or data before the closing body tag on the dashboard.
	 *
	 * @since 2025.1
	 */
	Hook::apply( 'grafema_dashboard_footer' );
    ?>
</body>
</html>
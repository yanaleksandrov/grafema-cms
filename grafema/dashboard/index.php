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

$slug	   = Sanitizer::trim( $args['slug'] ?? '' );
$start_time = microtime( true );
// print_r(
//	Query::apply(
//		[
//			'type'		   => [ 'pages', 'media' ],
//			'status'		 => null,
//			'page'		   => 1,
//			'per_page'	   => 99,
//			'offset'		 => 2,
//			'order'		  => 'DESC',
//			'orderby'		=> 'rand',
//			's'			  => 'hello exception',
//			'sentence'	   => false,
//			'boolean_mode'   => true,
//			'slug'		   => 'hello-world',
//			'slug_strict'	=> true,
//			'title'		  => 'Title 6',
//			'status'		 => 'draft',
//			'nicename'	   => 'alexandrov',
//			'author__in'	 => [ 2, 3, 4, 5 ],
//			'author__not_in' => [ 4 ],
//			'post__in'	   => [ 2, 3, 4, 5 ],
//			'post__not_in'   => [ 4 ],
//			'parent__in'	 => [ 2, 3, 4, 5 ],
//			'parent__not_in' => [ 4 ],
//			'discussion'	 => 'closed',
//			'comments'	   => [
//				'value'   => 8,
//				'compare' => '<',
//			],
//			'views'		  => [
//				'value'   => 0,
//				'compare' => '>=',
//			],
//			'dates'		  => [
//				'relation' => 'AND',
//				[
//					'human_date_time' => '-23 days',
//					'compare'		 => '<=',
//				],
//				[
//					'column'  => 'modified',
//					'compare' => 'BETWEEN',
//					'year'	=> [ 2015, 2016 ],
//				],
//			],
//			'fields'   => [
//				'relation' => 'AND',
//				[
//					'key'	 => 'price',
//					'value'   => '32434',
//					'compare' => '<=',
//					'type'	=> 'NUMERIC',
//				],
//				[
//					'key'	 => 'price',
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
<html lang="<?php echo I18n::locale(); ?>">
<head>
	<meta charset="<?php Option::attr( 'charset', 'UTF-8' ); ?>">
	<meta name="theme-color" content="#ffffff">
	<meta name="msapplication-TileColor" content="#da532c">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Menu</title>
	<link rel="apple-touch-icon" sizes="180x180" href="/dashboard/assets/images/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/dashboard/assets/images/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/dashboard/assets/images/favicons/favicon-16x16.png">
	<link rel="manifest" href="/dashboard/assets/images/favicons/site.webmanifest">
	<link rel="mask-icon" href="/dashboard/assets/images/favicons/safari-pinned-tab.svg" color="#5bbad5">
	<?php
	/**
	 * Prints scripts or data before the closing body tag on the dashboard.
	 *
	 * @since 2025.1
	 */
	Hook::apply( 'grafema_dashboard_header' );
	?>
</head>
<body x-data="grafema" @keydown.window.prevent.ctrl.s="$notification.add(notifications.ctrlS)">
	<?php if ( Is::installed() && User::logged() ) { ?>
		<div class="grafema" :class="showMenu && 'active'">
			<div class="grafema-bar">
				<div class="grafema-bar-menu" :class="showMenu && 'active'" @click="showMenu = !showMenu">
					<i class="ph ph-list"></i>
				</div>
				<?php View::print( 'templates/menu-bar' ); ?>

				<details class="grafema-search" x-data="search" x-bind="wrapper">
					<summary class="grafema-search-btn" x-bind="button">
						<i class="ph ph-magnifying-glass"></i> <?php I18n::t_attr( 'Search...' ); ?> <code>Ctrl+K</code>
					</summary>
					<div class="grafema-search-box">
						<div class="field field--lg field--outline">
							<label class="field-item">
								<input class="grafema-search-input" type="search" name="search" placeholder="<?php I18n::t_attr( 'Search...' ); ?>" x-bind="input" @input.debounce.250ms="$ajax('search').then(() => links = [{url: '', text: 'Страницы'}, {url: '/dashboard/themes', text: 'Привет'}, {url: '/dashboard/plugins', text: 'Привет'}])">
							</label>
						</div>
						<template x-if="links.length">
							<ul class="grafema-search-results">
								<template x-for="(link, i) in links" :key="i">
									<li class="grafema-search-item" :class="link.url && {'active': i === currentIdx}">
										<template x-if="link.url">
											<a class="grafema-search-link" :href="link.url">
												<span class="grafema-search-text" x-html="link.text"></span>
												<span class="t-muted"><?php I18n::t( 'Jump to' ); ?></span>
											</a>
										</template>
										<template x-if="!link.url">
											<span class="grafema-search-header" x-html="link.text"></span>
										</template>
									</li>
								</template>
							</ul>
						</template>
						<template x-if="!links.length">
							<div class="grafema-search-results">
								<?php
								View::print(
									GRFM_DASHBOARD . 'templates/global/state',
									[
										'icon'        => 'ufo',
										'title'       => I18n::_t( 'Nothing found' ),
										'description' => I18n::_t( 'Try to write something, there will be search results here' ),
									]
								);
								?>
							</div>
						</template>
						<div class="grafema-search-help">
							<div class="df aic g-1"><i class="ph ph-arrow-up"></i><i class="ph ph-arrow-down"></i> <?php I18n::t( 'Move' ); ?></div>
							<div class="df aic g-1"><i>Esc</i> <?php I18n::t( 'Close' ); ?></div>
							<div class="df aic g-1"><i class="ph ph-arrow-elbow-down-left"></i> <?php I18n::t( 'Select' ); ?></div>
						</div>
					</div>
				</details>

				<?php View::print( 'templates/global/user-account' ); ?>
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
				<a href="#" class="dif g-1 aic t-dark" title="Get Support"><i class="ph ph-headset fs-12"></i> support</a>
				<a href="#" class="dif g-1 aic t-dark" title="Grafema CMS version"><i class="ph ph-git-branch fs-12"></i> 2025.1</a>
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
	<dialog class="dialog" :class="$store.dialog?.class" id="grafema-dialog">
		<div class="dialog-wrapper" @click.outside="$dialog.close()">
			<div class="dialog-header">
				<template x-if="$store.dialog?.title">
					<h6 class="dialog-title" x-text="$store.dialog.title"></h6>
				</template>
				<button class="dialog-close" type="button" @click="$dialog.close()"></button>
			</div>
			<div class="dialog-content" data-content></div>
		</div>
	</dialog>

	<!-- notifications start -->
	<template x-if="$store.notifications.length">
		<div class="notifications">
			<template x-for="(notification, i) in $store.notifications">
				<div class="notifications-item" :class="notification.class" :style="`--notice-scale: ${1 - ($store.notifications.length - i - 1) * 0.005}`">
					<div class="notifications-wrapper">
						<template x-if="notification.type">
							<div class="notifications-icon">
								<i class="ph ph-bell-ringing t-gray" x-show="notification.type === 'info'"></i>
								<i class="ph ph-siren t-red" x-show="notification.type === 'error'"></i>
								<i class="ph ph-check t-green" x-show="notification.type === 'success'"></i>
								<i class="ph ph-shield-warning t-orange" x-show="notification.type === 'warning'"></i>
							</div>
						</template>
						<div class="notifications-text" x-text="notification.message"></div>
						<div class="notifications-close" :style="notification.duration && `--notice-animation: ${notification.animation}`" @click="$notification.close(notification.id)"></div>
					</div>
				</div>
			</template>
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
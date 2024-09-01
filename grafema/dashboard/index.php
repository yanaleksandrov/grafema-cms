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
	<!-- interface notices start -->
    <div class="notice" x-data>
        <template x-for="(item, id) in $store.notice.items">
            <div class="notice__item" :class="item.classes()">
                <div class="notice__msg" x-html="item.message"></div>
                <div class="notice__close" x-show="item.closable" :style="`--anim:${item.anim}`" @click="$store.notice.close(id)"></div>
            </div>
        </template>
    </div>
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
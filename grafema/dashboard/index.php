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
 * @since 1.0.0
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
	<meta charset="<?php Option::attr( 'charset' ); ?>">
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
	 * @since 1.0.0
	 */
	Hook::apply( 'grafema_dashboard_header' );
	?>
</head>
<body x-data="index">
	<?php if ( Is::installed() && User::logged() ) { ?>
		<div class="grafema" :class="showMenu && 'active'">
            <div class="grafema-bar">
                <div class="grafema-bar-menu" @click="showMenu = !showMenu">
                    <i class="ph ph-list"></i>
                </div>
                <?php View::print( 'templates/menu-bar' ); ?>

				<?php ob_start(); ?>
                <div class="df fdc">
                    <div class="fs-13 lh-xs fw-600">Howdy, Yan Aleksandrov</div>
                </div>
                <div class="avatar avatar--xs" style="background-image: url(https://i.pravatar.cc/150?img=3)">
                    <i class="badge bg-green" title="Online"></i>
                </div>
				<?php
				$label = ob_get_clean();

				View::print(
					'templates/form/details',
					[
						'label'       => $label,
						'instruction' => '',
						'class'       => 'ml-auto df aic g-3',
						'content'     => Tree::include(
							'dashboard-user-menu',
							$test = function ( $items, $tree ) use ( &$test ) {
								if ( empty( $items ) || ! is_array( $items ) ) {
									return false;
								}
								?>
                                <ul class="user-menu">
									<?php
									foreach ( $items as $item ) {
										ob_start();

										if ( empty( $item['url'] ) ) {
											?>
                                            <li class="user-menu-divider">%title$s</li>
											<?php
										} else {
											?>
                                            <li class="user-menu-item">
                                                <a class="user-menu-link" href="%url$s">
                                                    <i class="%icon$s"></i> <span>%title$s</span>
                                                </a>
                                            </li>
											<?php
										}

										echo $tree->vsprintf( ob_get_clean(), $item );
									}
									?>
                                </ul>
								<?php
							}
						),
					]
				);
				?>
            </div>

			<div class="grafema-panel">
                <img src="/dashboard/assets/images/logo.svg" width="34" height="34" alt="Grafema Logo">
                <?php View::print( 'templates/menu-panel' ); ?>
			</div>

            <?php
            View::print( 'templates/menu' );

            View::print( 'templates/' . $slug );
            ?>

            <div class="grafema-tools">
                <a class="menu__link" href="/"><i class="ph ph-git-branch"></i> 1.0.0</a>
            </div>
		</div>
    	<?php
		View::print( 'templates/modals/uploader' );

	} else {
	    ?>
		<div class="df aic jcc p-6">
			<?php View::print( 'templates/' . $slug ); ?>
		</div>
		<?php
	}
	?>

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
	 * @since 1.0.0
	 */
	Hook::apply( 'grafema_dashboard_footer' );
    ?>
</body>
</html>
<?php
use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table extension item.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/views/table/cell-extension.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $title, $description, $screenshot, $author, $categories, $installed, $active, $installations, $date, $reviews, $rating, $grafema, $version ] = (
	new Sanitizer(
		$args ?? [],
		[
			'title'         => 'trim',
			'description'   => 'trim',
			'screenshot'    => 'url',
			'author'        => 'array',
			'categories'    => 'array',
			'installed'     => 'bool',
			'active'        => 'bool',
			'installations' => 'trim',
			'date'          => 'trim',
			'reviews'       => 'absint',
			'rating'        => 'float',
			'grafema'       => 'trim',
			'version'       => 'trim',
		]
	)
)->values();
?>
<div class="plugins__item" x-data="<?php printf( '{installed: %s, active: %s}', $installed ? 'true' : 'false', $active ? 'true' : 'false' ); ?>">
	<div class="plugins__card">
		<div class="plugins__image" style="background-image: url(<?php echo $screenshot; ?>)"></div>
		<div class="plugins__data">
			<h4 class="plugins__title"><?php echo $title; ?></h4>
			<div class="plugins__description"><?php echo $description; ?></div>
			<div class="plugins__author">
				by <a href="#" target="_blank">Our Team</a>
			</div>
		</div>
		<div class="plugins__action">
			<button class="btn btn--outline"<?php ( $installed && $active ) && print( ' x-cloak' ); ?>><?php I18n::t( 'Install' ); ?></button>
			<button class="btn btn--primary"<?php ( $installed && ! $active ) && print( ' x-cloak' ); ?>><?php I18n::t( 'Activate' ); ?></button>
		</div>
	</div>
	<div class="plugins__info">
		<span class="plugins__text"><i class="ph ph-desktop-tower"></i> <?php echo $installations; ?></span>
		<span class="plugins__text"><i class="ph ph-calendar-dots"></i> <?php I18n::f( 'Last updated: :date', $date ); ?></span>
		<span class="plugins__text">
			<?php
			if ( $reviews > 0 ) :
				View::print(
					'views/global/rating',
					[
						'class'   => 'df aic g-1',
						'rating'  => $rating,
						'reviews' => $reviews,
					]
				);
			else :
				I18n::t( 'This plugin has not been rated yet' );
			endif;
			?>
		</span>
		<span class="plugins__text"><i class="ph ph-check"></i> <?php I18n::f( '%sCompatible%s with your Grafema version', '<strong>', '</strong>' ); ?></span>
	</div>
</div>
<?php
use Grafema\I18n;
use Grafema\Sanitizer;
use Grafema\View;

/**
 * Table raw text cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-toggle.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $key, $title, $description, $screenshot, $reviews, $version, $rating, $installed ] = (
	new Sanitizer(
		$args ?? [],
		[
			'key'         => 'class',
			'title'       => 'trim',
			'description' => 'trim',
			'screenshot'  => 'url',
			'reviews'     => 'absint',
			'version'     => 'trim',
			'rating'      => 'float',
			'installed'   => 'bool',
		]
	)
)->values();
?>
<div class="themes__item">
	<div class="themes__image" data-title="<?php I18n::t( 'Details' ); ?>" style="background-image: url(<?php echo $screenshot; ?>)"></div>
	<h6 class="themes__title"><?php echo $title, I18n::_c( $installed, ' <i class="badge badge--green-lt">Active</i>' ); ?></h6>
	<div class="themes__text"><?php echo $description; ?></div>
	<div class="themes__data">
		<?php
		if ( $reviews > 0 ) {
			View::print(
				'templates/global/rating',
				[
					'rating'  => $rating,
					'reviews' => $reviews,
				]
			);
		} else {
			I18n::t( 'This theme has not been rated yet' );
		}

		if ( $version ) :
			?>
			<div class="themes__text" title="<?php I18n::tf( 'Version %s', $version ); ?>"><?php echo $version; ?></div>
		<?php endif; ?>
	</div>
</div>

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
			'key'         => 'key',
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
	<div class="dg g-2 py-4 px-2">
		<div class="fw-600 fs-16 df jcsb aic">
			<?php
			echo $title;
			if ( $installed ) :
				?>
				<span class="badge badge--green-lt"><?php I18n::t( 'Active' ); ?></span>
			<?php endif; ?>
		</div>
		<div class="t-muted"><?php echo $description; ?></div>
		<div class="df jcsb fs-12">
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
				<div class="t-muted" title="<?php I18n::tf( 'Version %s', $version ); ?>"><?php echo $version; ?></div>
			<?php endif; ?>
		</div>
	</div>
</div>
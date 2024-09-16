<?php
use Grafema\I18n;
use Grafema\Sanitizer;

/**
 * Output rating.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/global/rating.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $class, $rating, $reviews ] = (
	new Sanitizer(
		$args ?? [],
		[
			'class'   => 'class:df aic g-1',
			'rating'  => 'float',
			'reviews' => 'absint',
		]
	)
)->values();

$fullStars  = round( $rating );
$emptyStars = 5 - $fullStars;
?>
<span class="<?php echo $class; ?>" title="<?php I18n::f( ':rate rating based on :count reviews', $rating, $reviews ); ?>">
	<?php echo str_repeat( '<i class="ph ph-star t-orange" aria-hidden="true"></i>' . PHP_EOL, $fullStars ); ?>
	<?php echo str_repeat( '<i class="ph ph-star t-muted" aria-hidden="true"></i>' . PHP_EOL, $emptyStars ); ?>
	<span class="t-dark ml-1">[<?php echo $reviews; ?>]</span>
</span>

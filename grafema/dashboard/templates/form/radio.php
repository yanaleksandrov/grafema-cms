<?php
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/*
 * Radio buttons
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/radio.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $name, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes, $options, $variation, $width ] = ( new Grafema\Sanitizer(
	$args ?? [],
	[
		'name'        => 'name',
		'label'       => 'trim',
		'class'       => 'class:dg g-1',
		'label_class' => 'class:df aic jcsb fw-600',
		'reset'       => 'bool:false',
		'before'      => 'trim',
		'after'       => 'trim',
		'instruction' => 'trim',
		'tooltip'     => 'attribute',
		'copy'        => 'bool:false',
		'conditions'  => 'array',
		'attributes'  => 'array',
		// radio
		'options'     => 'array',
		'variation'   => 'class:simple', // simple, image, described
		'width'       => 'absint:200',
	]
) )->values();

$value = Sanitizer::attribute( $attributes['value'] ?? '' );
$prop  = Sanitizer::prop( $attributes['name'] ?? $name );
?>
<div class="<?php echo $class; ?>">
	<div class="<?php echo $label_class; ?>"><?php echo $label; ?></div>
	<div class="dg g-4 mb-2" style="grid-template-columns: repeat(auto-fill, minmax(<?php echo $width; ?>px, 1fr))">
		<?php
		foreach ( $options as $v => $option ) :
			[ $image, $title, $content, $hidden ] = (
                new Sanitizer(
					$option,
                    [
                        'image'   => 'attribute',
                        'title'   => 'trim',
                        'content' => 'trim',
                        'hidden'  => 'trim',
                    ]
                )
			)->values();

			$attributes['value'] = $v;

			if ( $v === $value ) {
				$attributes['checked'] = $v === $value;
			} else {
				unset( $attributes['checked'] );
			}
			?>
			<label class="radio radio--<?php echo $variation; ?>">
				<?php
                printf( '<input%s>', Arr::toHtmlAtts( $attributes ) );
				switch ( $variation ) {
					case 'image':
						?>
                        <div class="radio-title"><?php echo $title; ?></div>
						<img src="<?php echo $image; ?>" alt="<?php Sanitizer::attribute( $title ); ?>">
                        <div class="radio-content"><?php echo $content; ?></div>
						<?php
						break;
					case 'described':
						?>
						<div>
							<div class="fw-600"><?php echo $title; ?></div>
							<div class="fs-13 t-muted"><?php echo $content; ?></div>
							<?php if ( $hidden ) : ?>
								<div><?php echo $hidden; ?></div>
							<?php endif; ?>
						</div>
						<?php
						break;
					default:
						?>
						<div><?php echo $title; ?></div>
						<?php
						break;
				}
				?>
			</label>
		<?php endforeach; ?>
	</div>
	<?php if ( $instruction ) : ?>
		<div class="fs-13 t-muted lh-xs"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

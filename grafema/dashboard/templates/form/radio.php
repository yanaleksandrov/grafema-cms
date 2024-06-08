<?php
use Grafema\Esc;
use Grafema\Helpers\Arr;
use Grafema\Sanitizer;

/*
 * Radio buttons
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/radio.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[$label, $name, $value, $class, $instruction, $tooltip, $width, $options, $variation] = (
    new Sanitizer(
        $args ?? [],
        [
            'label'       => 'trim',
            'name'        => 'key',
            'value'       => 'attribute',
            'class'       => 'class:dg g-1',
            'instruction' => 'trim',
            'tooltip'     => 'attribute',
            'width'       => 'absint:200',
            'options'     => 'array',
            'variation'   => 'trim:simple',
        ]
    )
)->values();
?>
<div class="<?php echo $class; ?>">
	<div class="df aic jcsb fw-600"><?php Esc::html( $label ); ?></div>
	<div class="dg g-4 mb-2" style="grid-template-columns: repeat(auto-fill, minmax(<?php Esc::attr( $width ); ?>px, 1fr))">
		<?php
		foreach ( $options as $v => $option ) :
			[$image, $title, $content, $hidden] = (
                new Sanitizer(
					$option,
                    [
                        'image'   => 'trim',
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
			<label class="radio radio--<?php Esc::attr( $variation ); ?>">
				<?php
                printf( '<input%s>', Arr::toHtmlAtts( $attributes ) );
				switch ( $variation ) {
					case 'image':
						?>
                        <div class="radio-title"><?php echo $title; ?></div>
						<img src="<?php Esc::attr( $image ); ?>" alt="<?php Esc::attr( $title ); ?>">
                        <div class="radio-content"><?php echo $content; ?></div>
						<?php
						break;
					case 'described':
						?>
						<div>
							<div class="fw-600"><?php echo $title; ?></div>
							<div class="fs-13 t-muted"><?php echo $content; ?></div>
							<div><?php echo $hidden; ?></div>
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
		<div class="fs-13 t-muted lh-xs"><?php Esc::html( $instruction ); ?></div>
	<?php endif; ?>
</div>

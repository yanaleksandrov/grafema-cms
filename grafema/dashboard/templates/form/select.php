<?php
use Grafema\Helpers\Arr;
use Grafema\I18n;
use Grafema\Sanitizer;

/**
 * Select field
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/select.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $name, $label, $class, $label_class, $reset, $before, $after, $instruction, $tooltip, $copy, $conditions, $attributes, $options ] = ( new Sanitizer(
	$args ?? [],
	[
		'name'        => 'name',
		'label'       => 'trim',
		'class'       => 'class:field',
		'label_class' => 'class:field-label',
		'reset'       => 'bool:false',
		'before'      => 'trim',
		'after'       => 'trim',
		'instruction' => 'trim',
		'tooltip'     => 'attribute',
		'copy'        => 'bool:false',
		'conditions'  => 'array',
		'attributes'  => 'array',
		'options'     => 'array',
	]
) )->values();

$value = Sanitizer::attribute( $attributes['value'] ?? '' );
$prop  = Sanitizer::prop( $attributes['name'] ?? $name );
?>
<div class="<?php echo $class; ?>">
	<?php if ( $label ) : ?>
		<div class="<?php echo $label_class; ?>"><?php
			echo $label;
			if ( $reset ) :
				$click = sprintf( "%s = '%s'", $prop, $value );
				$show  = sprintf( "%s !== '%s'", $prop, $value );
				?>
				<span class="ml-auto t-red" @click="<?php echo $click; ?>" x-show="<?php echo $show; ?>" x-cloak><?php I18n::t( 'Reset' ); ?></span>
				<?php
			endif;

			if ( $tooltip ) :
				?>
				<i class="ph ph-info" x-tooltip.click.prevent="'<?php echo $tooltip; ?>'"></i>
			<?php endif; ?>
		</div>
	<?php endif; ?>
	<label class="field-item">
		<select<?php echo Arr::toHtmlAtts( $attributes ); ?>>
			<?php
			$get_attributes = function ( $key, $value, $option ) {
				$attributes = [
					'value'    => trim( (string) $key ),
					'selected' => $key === $value,
				];

				if ( is_array( $option ) ) {
					unset( $option['content'] );

					foreach ( $option as $attribute => $value ) {
						$attributes['data-' . $attribute] = trim( (string) $value );
					}
				}

				return $attributes;
			};

			foreach ( $options as $option_key => $option ) {
				$optgroup_options = $option['options'] ?? [];
				if ( $optgroup_options ) {
					$label = Sanitizer::attribute( $option['label'] ?? '' );
					?>
					<optgroup label="<?php echo $label; ?>">
						<?php
						foreach ( $optgroup_options as $i => $optgroup_option ) {
							$content = trim( is_scalar( $optgroup_option ) ? $optgroup_option : strval( $optgroup_option['content'] ?? '' ) );
							$atts    = $get_attributes( $i, $value, $optgroup_option );
							?>
							<option<?php echo Arr::toHtmlAtts( $atts ); ?>><?php echo $content; ?></option>
							<?php
						}
						?>
					</optgroup>
					<?php
				} else {
					$content = trim( is_scalar( $option ) ? $option : strval( $option['content'] ?? '' ) );
					$atts    = $get_attributes( $option_key, $value, $option );
					?>
					<option<?php echo Arr::toHtmlAtts( $atts ); ?>><?php echo $content; ?></option>
					<?php
				}
			}
			?>
		</select>
	</label>
	<?php if ( $instruction ) : ?>
		<div class="field-instruction"><?php echo $instruction; ?></div>
	<?php endif; ?>
</div>

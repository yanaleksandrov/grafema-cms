<?php
use Grafema\Sanitizer;
use Grafema\Helpers\Arr;

/**
 * Translation cell.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-translation.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $prop, $attributes ] = (
	new Sanitizer(
		$args ?? [],
		[
			'key'        => 'prop',
			'attributes' => 'array',
		]
	)
)->values();
?>
<label<?php echo Arr::toHtmlAtts( $attributes ); ?>>
	<textarea :name="`translations[${item.source}]`" x-text="item.<?php echo $prop; ?>" rows="1" x-textarea="7"></textarea>
</label>

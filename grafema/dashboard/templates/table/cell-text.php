<?php
use Grafema\Sanitizer;

/**
 * Table title with actions cell
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-text.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$class = Sanitizer::class($args['key'] ?? [] );
$prop  = Sanitizer::prop($args['key'] ?? [] );
$value = Sanitizer::trim($args['value'] ?? '' );
?>
<label class="<?php echo $class; ?>">
	<textarea :name="`items[${i}]`" x-text="item.<?php echo $prop; ?>" rows="1" x-textarea="7"></textarea>
</label>

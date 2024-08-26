<?php
use Grafema\Helpers\Arr;
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

$key   = Sanitizer::attribute($args['key'] ?? '' );
$value = Sanitizer::trim($args['value'] ?? '' );
?>
<div class="<?php echo $key; ?>">
	<?php printf( '<textarea class="empty" name="%s" x-textarea="8">%s</textarea>', 'name', $value ); ?>
</div>

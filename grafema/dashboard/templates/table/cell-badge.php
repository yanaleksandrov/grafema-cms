<?php
use Grafema\Sanitizer;

/**
 * Badge
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cell-badge.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$class = Sanitizer::class($args['key'] ?? [] );
$prop  = Sanitizer::prop($args['key'] ?? [] );
?>
<div class="<?php echo $class; ?>">
    <span class="badge badge--green-lt" x-text="item.<?php echo $prop; ?>"></span>
</div>

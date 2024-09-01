<?php
use Grafema\Sanitizer;

/**
 * Publish date
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/date.php
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
<div class="<?php echo $prop; ?>">
	Published
	<div x-text="item.<?php echo $prop; ?>"></div>
</div>

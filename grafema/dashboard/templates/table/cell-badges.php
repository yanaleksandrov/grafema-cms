<?php
use Grafema\Sanitizer;

/**
 * Comma-separated list of links
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/cells/links.php
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
	<template x-for="(badge, index) in item.<?php echo $prop; ?>">
		<span class="badge badge--lg" :class="badge.class" x-text="badge.title"></span>
	</template>
</div>

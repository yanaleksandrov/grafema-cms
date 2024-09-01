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
<div class="<?php echo $class; ?>" x-data="{show: false}">
	<template x-for="(link, index) in item.<?php echo $prop; ?>.slice(0, show ? item.<?php echo $prop; ?>.length : 3)">
		<span class="table__link"><a :href="link.href" x-text="link.title"></a></span>
	</template>
	<span class="badge" x-text="`+${item.<?php echo $prop; ?>.length - 3} more`" @click="show = !show" x-show="item.<?php echo $prop; ?>.length > 3 && !show"></span>
</div>

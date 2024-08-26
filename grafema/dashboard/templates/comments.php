<?php
/**
 * Comments list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/comments.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php ( new Dashboard\Builders\Table( new Dashboard\CommentsTable() ) )->print(); ?>
</div>

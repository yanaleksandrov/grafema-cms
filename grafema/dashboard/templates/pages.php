<?php
use Grafema\View;

/**
 * Files storage.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/pages.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php
	( new Dashboard\Builders\Table( new Dashboard\PagesTable() ) )->print();

	View::print( 'templates/modals/post' );
    ?>
</div>

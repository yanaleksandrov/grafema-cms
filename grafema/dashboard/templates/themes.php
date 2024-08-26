<?php
use Grafema\I18n;
use Grafema\View;

/**
 * Themes list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/themes.php
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
	View::print(
		'templates/table/header',
		[
			'title' => I18n::__( 'Themes' ),
		]
	);

	( new Dashboard\Builders\Table( new Dashboard\ThemesTable() ) )->print();
    ?>
</div>

<?php
use Grafema\I18n;
use Grafema\View;

/**
 * Addons list for install.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/plugins-install.php
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
			'title'  => I18n::_t( 'Add Plugins' ),
			'search' => true,
		]
	);

	( new Dashboard\Table( new Dashboard\PluginsInstallTable() ) )->print();
	?>
</div>

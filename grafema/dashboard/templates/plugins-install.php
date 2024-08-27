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
<div class="grafema-main" x-data="{bulk: false, reset: true}">
	<?php
	View::print(
		'templates/table/header',
		[
			'title' => I18n::_t( 'Add Plugins' ),
		]
	);

	( new Dashboard\Builders\Table( new Dashboard\PluginsInstallTable() ) )->print();
	?>
</div>

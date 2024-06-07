<?php
use Grafema\I18n;

/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php
	Dashboard\PluginsTable::add()
        ->title( I18n::__( 'Plugins' ) )
        ->attributes(
			[
				'class'  => 'table',
				'x-data' => 'table',
				'x-init' => '$ajax("extensions/get").then(response => items = response.items)',
			]
        )
        ->print();

	//( new Dashboard\PluginsTable() )->render();
	?>
</div>

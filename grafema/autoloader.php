<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */
spl_autoload_register(
	function ($class) {
		static $fileCache = [];

		$filepath = sprintf('%s%s.php', GRFM_PATH, $class);

		// Заменяем разделители и изменяем путь
		$filepath = str_replace(
			['\\', '/Grafema/', '/Dashboard/'],
			['/', '/core/', '/dashboard/core/'],
			$filepath
		);

		// Если класс 'Grafema2025', выводим отладочную информацию
		if ($class === 'Grafema2025') {
			echo '<pre>';
			var_dump($class);
			var_dump($filepath);
			echo '</pre>';
		}

		// Проверяем наличие файла с кэшированием
		if (!isset($fileCache[$filepath])) {
			$fileCache[$filepath] = file_exists($filepath);
		}

		if ($fileCache[$filepath]) {
			require_once $filepath;
		}
	}
);

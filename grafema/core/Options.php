<?php
namespace Grafema;

/**
 * Option class it is a self-contained class for creating, updating, and deleting options.
 * Uses static variables to store options, which allows to avoid using the object cache without losing performance.
 *
 * @since 2025.1
 */
class Options
{
	/**
	 * DataBase table name.
	 *
	 * @since 2025.1
	 */
	public static string $table = 'options';

	/**
	 * Create new table into database.
	 *
	 * @since 2025.1
	 */
	public static function migrate(): void
	{
		$table           = DB_PREFIX . self::$table;
		$charset_collate = '';
		if ( DB_CHARSET ) {
			$charset_collate = 'DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charset_collate .= ' COLLATE ' . DB_COLLATE;
		}

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table} (
				ID          bigint(20)   unsigned NOT NULL auto_increment,
				name        varchar(191) NOT NULL default '',
				value       longtext     NOT NULL,
				PRIMARY KEY (ID),
				UNIQUE KEY name (name)
			) {$charset_collate};"
		)->fetchAll();

		Db::updateSchema();
	}
}

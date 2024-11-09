<?php
namespace Grafema\Field;

use Grafema\Db;

/**
 *
 *
 * @since 2025.1
 */
class Schema {

	/**
	 * Create new table into database.
	 *
	 * @param string $table
	 * @param string $name
	 * @since 2025.1
	 */
	public static function migrate( string $table, string $name ): void
	{
		$indexLength    = GRFM_DB_MAX_INDEX_LENGTH;
		$charsetCollate = (new Db\Handler)->getCharsetCollate();

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table}_fields (
				`id`         BIGINT(20)   UNSIGNED NOT NULL AUTO_INCREMENT,
				`{$name}_id` BIGINT(20)   UNSIGNED NOT NULL DEFAULT '0',
				`key`        VARCHAR(255) DEFAULT NULL,
				`value`      TEXT,
				PRIMARY KEY (id),
				KEY `{$name}_id` ({$name}_id),
				KEY `key` (`key`({$indexLength})),
				KEY `value` (`value`({$indexLength}))
			) ENGINE=InnoDB {$charsetCollate};

			CREATE TRIGGER {$table}_cascade_delete
				AFTER DELETE ON {$table}
				FOR EACH ROW
					BEGIN
						DELETE FROM {$table}_fields WHERE {$name}_id = OLD.ID;
					END;"
		)->fetchAll();
	}
}

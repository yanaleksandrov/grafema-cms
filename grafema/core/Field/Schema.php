<?php
namespace Grafema\Field;

use Grafema\DB;
use Grafema\Sanitizer;

/**
 *
 *
 * @since 2025.1
 */
final class Schema {

	/**
	 * Create new table into database.
	 *
	 * @param string $table
	 * @param string $name
	 * @since 2025.1
	 */
	public static function migrate( string $table, string $name ): void
	{
		$length          = DB_MAX_INDEX_LENGTH;
		$table           = Sanitizer::tablename( DB_PREFIX . $table );
		$charsetCollate = '';
		if ( DB_CHARSET ) {
			$charsetCollate = ' DEFAULT CHARACTER SET ' . DB_CHARSET;
		}
		if ( DB_COLLATE ) {
			$charsetCollate .= ' COLLATE ' . DB_COLLATE;
		}

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table}_fields (
				`id`         BIGINT(20)   UNSIGNED NOT NULL AUTO_INCREMENT,
				`{$name}_id` BIGINT(20)   UNSIGNED NOT NULL DEFAULT '0',
				`key`        VARCHAR(255) DEFAULT NULL,
				`value`      TEXT,
				PRIMARY KEY (id),
				KEY `{$name}_id` ({$name}_id),
				KEY `key` (`key`({$length})),
				KEY `value` (`value`({$length}))
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

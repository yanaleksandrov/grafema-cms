<?php
namespace Grafema\Field;

use Grafema\Db;
use Grafema\Sanitizer;

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
	public static function migrate( string $table, string $name ): void {
		$charsetCollate = (new Db\Handler)->getCharsetCollate();

		$tableName = Sanitizer::tablename( $table );
		$name      = Sanitizer::tablename( $name );

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$tableName}_fields (
				`id`         BIGINT(20)   UNSIGNED NOT NULL AUTO_INCREMENT,
				`{$name}_id` BIGINT(20)   UNSIGNED NOT NULL DEFAULT '0',
				`key`        VARCHAR(255) DEFAULT NULL,
				`value`      MEDIUMTEXT,

				PRIMARY KEY (id),

				INDEX idx_{$name}_id_key ({$name}_id, `key`)
			) ENGINE=InnoDB {$charsetCollate};

			CREATE TRIGGER {$tableName}_cascade_delete
				AFTER DELETE ON {$tableName}
				FOR EACH ROW
					BEGIN
						DELETE FROM {$tableName}_fields WHERE {$name}_id = OLD.ID;
					END;"
		)->fetchAll();
	}
}

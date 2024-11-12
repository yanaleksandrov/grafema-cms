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

		$table = Sanitizer::tablename( $table );
		$name  = Sanitizer::tablename( $name );

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$table}_fields (
				`id`         BIGINT(20)   UNSIGNED NOT NULL AUTO_INCREMENT,
				`{$name}_id` BIGINT(20)   UNSIGNED NOT NULL DEFAULT '0',
				`key`        VARCHAR(255) DEFAULT NULL,

				`value_text`     TEXT,
				`value_int`      BIGINT SIGNED DEFAULT NULL,
				`value_float`    FLOAT  SIGNED DEFAULT NULL,
				`value_bool`     TINYINT(1) DEFAULT NULL,
				`value_time`     TIME DEFAULT NULL,
				`value_date`     DATE DEFAULT NULL,
				`value_datetime` DATETIME DEFAULT NULL,

				PRIMARY KEY (id),

				INDEX idx_{$name}_id ({$name}_id),
				INDEX idx_key_value_text (`value_text`(" . GRFM_DB_MAX_INDEX_LENGTH . "), `key`(" . GRFM_DB_MAX_INDEX_LENGTH . ")),
				INDEX idx_key_value_int (`value_int`, `key`(" . GRFM_DB_MAX_INDEX_LENGTH . ")),
				INDEX idx_key_value_float (`value_float`, `key`(" . GRFM_DB_MAX_INDEX_LENGTH . ")),
				INDEX idx_key_value_time (`value_time`, `key`(" . GRFM_DB_MAX_INDEX_LENGTH . ")),
				INDEX idx_key_value_date (`value_date`, `key`(" . GRFM_DB_MAX_INDEX_LENGTH . ")),
				INDEX idx_key_value_datetime (`value_datetime`, `key`(" . GRFM_DB_MAX_INDEX_LENGTH . "))
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

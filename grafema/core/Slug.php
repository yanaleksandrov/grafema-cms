<?php
namespace Grafema;

/**
 * @since 2025.1
 */
final class Slug {

	/**
	 * DB table name.
	 *
	 * @var string
	 * @since 2025.1
	 */
	private static string $table = 'slugs';

	/**
	 * Create new table into database.
	 *
	 * @since 2025.1
	 */
	public static function migrate(): void {
		$tableName      = (new Db\Handler)->getTableName( self::$table );
		$charsetCollate = (new Db\Handler)->getCharsetCollate();

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$tableName} (
				id           BIGINT(20)   UNSIGNED NOT NULL AUTO_INCREMENT,
			    entity_id    BIGINT(20)   UNSIGNED NOT NULL,
				entity_table VARCHAR(255) NOT NULL,
                slug         VARCHAR(255) NOT NULL,
			    locale       VARCHAR(100) NOT NULL DEFAULT '',
			    uuid         CHAR(36)     NOT NULL,

				PRIMARY KEY (id),

			    UNIQUE KEY unique_slug (slug),
			    UNIQUE KEY unique_uuid (uuid),

                INDEX idx_entity (entity_id, entity_table),
				INDEX idx_slug_locale (slug, locale)
			) ENGINE=InnoDB {$charsetCollate};

			CREATE TRIGGER before_insert_{$tableName}
				BEFORE INSERT ON {$tableName}
				FOR EACH ROW
					BEGIN
					    IF NEW.uuid IS NULL THEN
					        SET NEW.uuid = UUID();
					    END IF;
					END;"
		)->fetchAll();
	}
}

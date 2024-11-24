<?php
namespace Grafema;

use Grafema\Db\Medoo;

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
	 * @param int $entityId
	 * @param string $entityTable
	 * @param string $slug
	 * @param string $locale
	 * @return bool
	 */
	public static function add( int $entityId, string $entityTable, string $slug, string $locale = '' ): bool {
		$slugs = Db::select( self::$table, 'slug', [
			'AND' => [
				'slug[REGEXP]' => sprintf( '^%s(-[1-9][0-9]*)?$', preg_quote( $slug, '/' ) ),
			]
		] );

		// checking the uniqueness of a slug, add a numeric suffix if found
		$maxSuffix = 0;
		if ( count( $slugs ) === 1 && $slugs[0] === $slug ) {
			$maxSuffix = 1;
		} else {
			$slugs = array_diff( $slugs, [ $slug ] );
			if ( $slugs ) {
				$maxSuffix = max( array_map( fn( $item ) => (int) substr( strrchr( $item, '-' ), 1 ), $slugs ) );
			}
		}

		if ( $maxSuffix > 0 ) {
			$slug = sprintf( '%s-%d', $slug, $maxSuffix + 1 );
		}

		return Db::insert(
			self::$table,
			[
				'entity_id'    => $entityId,
				'entity_table' => $entityTable,
				'slug'         => $slug,
				'locale'       => $locale,
			]
		)->rowCount() > 0;
	}

	public static function get() {

	}

	public static function update() {

	}

	public static function delete() {

	}

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
			    uuid         CHAR(36)     NOT NULL,
			    entity_id    BIGINT(20)   UNSIGNED NOT NULL,
				entity_table VARCHAR(255) NOT NULL,
                slug         VARCHAR(255) NOT NULL,
			    locale       VARCHAR(100) NOT NULL DEFAULT '',

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

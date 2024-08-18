<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Grafema;

use Grafema\Db;

/**
 * Core class for managing plugins.
 *
 * @since 2025.1
 */
class Term
{
	/**
	 * DataBase table name.
	 *
	 * @since 2025.1
	 */
	public static string $table = 'term';

	/**
	 * @since 2025.1
	 */
	public static function register( string $term, string $object_type, array $args = [] ) {}

	/**
	 * @since 2025.1
	 */
	public static function unregister( string $term ) {}

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
			'CREATE TABLE IF NOT EXISTS ' . $table . " (
			term_id    bigint(20) unsigned NOT NULL auto_increment,
			name       varchar(200) NOT NULL default '',
			slug       varchar(200) NOT NULL default '',
			term_group bigint(10) NOT NULL default 0,
			PRIMARY    KEY (term_id),
			KEY slug (slug(" . DB_MAX_INDEX_LENGTH . ')),
			KEY name (name(' . DB_MAX_INDEX_LENGTH . '))
		) ' . $charset_collate . ';'
		)->fetchAll();

		Db::query(
			'CREATE TABLE IF NOT EXISTS ' . $table . "_fields (
			meta_id    bigint(20) unsigned NOT NULL auto_increment,
			term_id    bigint(20) unsigned NOT NULL default '0',
			meta_key   varchar(255) default NULL,
			meta_value longtext,
			PRIMARY KEY (meta_id),
			KEY term_id (term_id),
			KEY meta_key (meta_key(" . DB_MAX_INDEX_LENGTH . '))
		) ' . $charset_collate . ';'
		)->fetchAll();

		Db::updateSchema();
	}
}

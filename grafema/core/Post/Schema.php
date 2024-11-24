<?php
namespace Grafema\Post;

use Grafema\Db;

/**
 *
 *
 * @since 2025.1
 */
final class Schema {

	/**
	 * Get query for create new table into database.
	 *
	 * @param string $table
	 * @return void
	 * @since 2025.1
	 */
	public static function migrate( string $table ): void {
		$tableName      = (new Db\Handler)->getTableName( $table );
		$charsetCollate = (new Db\Handler)->getCharsetCollate();

		Db::query(
			"
			CREATE TABLE IF NOT EXISTS {$tableName} (
				id          int          unsigned NOT NULL auto_increment,
				title       text         NOT NULL default '',
				content     longtext     NOT NULL default '',
				author      smallint     unsigned NOT NULL default '0',
				comments    smallint     unsigned NOT NULL default '0',
				views       mediumint    unsigned NOT NULL default '0',
				status      varchar(255) NOT NULL default 'draft',
				discussion  varchar(255) NOT NULL default 'open',
				password    varchar(255) NOT NULL default '',
				parent      int          unsigned NOT NULL default '0',
				position    mediumint    unsigned NOT NULL default '0',
				created_at  datetime     NOT NULL default NOW(),
				updated_at  datetime     NOT NULL default NOW() ON UPDATE NOW(),
				PRIMARY KEY (ID),
				KEY parent (parent),
				KEY author (author),
				FULLTEXT KEY content (title,content)
			) ENGINE=InnoDB {$charsetCollate}
			COMMENT='post-type';"
		)->fetchAll();
	}
}

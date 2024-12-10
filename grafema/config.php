<?php

use Grafema\Db;
use Grafema\Api;
use Grafema\I18n;

/**
 * Launch database connection.
 * TODO: configure & launch db including
 *
 * @since  2025.1
 */
Db::init();

/**
 * Create a single entry point to the website.
 *
 * @since 2025.1
 */
// save as .htaccess
if ( ! file_exists( GRFM_PATH . '.htaccess' ) ) {
	file_put_contents(
		GRFM_PATH . '.htaccess',
		'Options +FollowSymLinks
# Options +SymLinksIfOwnerMatch
Options -Indexes
DirectoryIndex index.php index.html
AddDefaultCharset UTF-8

<IfModule mod_rewrite.c>
    RewriteEngine on

    # for 301-redirect http to https
    # RewriteCond %{HTTPS} !=on
    # RewriteRule (.*) https://%{HTTP_HOST}%{REQUEST_URI} [R=301,QSA,L]

    RewriteBase /
    RewriteCond $1 !^(index\.php|uploads|robots\.txt|favicon\.ico)
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ /index.php/$1 [L,QSA]
    # or for fastCGI
    # RewriteRule . /index.php [L]
</IfModule>

# Denies access to system folders and files that start with . or ~.
# For example: .htaccess & .env file, .svn directory
<IfModule mod_rewrite.c>
    RewriteRule (?:^|/)(?:\..*)$ - [F]
</IfModule>'
	);
}

/**
 * Setting up the priority rule for translations.
 *
 * @since 2025.1
 */
I18n::configure(
	[
		GRFM_CORE      => GRFM_DASHBOARD,
		GRFM_DASHBOARD => GRFM_DASHBOARD,
		GRFM_PLUGINS   => GRFM_PLUGINS . ':dirname',
		GRFM_THEMES    => GRFM_THEMES . ':dirname',
	],
	'i18n/%s'
);

/**
 * Add core API endpoints.
 * Important! If current request is request to API, stop code execution after Api::create().
 *
 * @since 2025.1
 */
Api::configure( '/api', sprintf( '%sapp/Api', GRFM_DASHBOARD ) );
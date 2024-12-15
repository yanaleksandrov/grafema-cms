<?php
use Grafema\I18n;
use Grafema\Dir;
use Grafema\Tree;

/**
 * File manager table
 *
 * This template can be overridden by copying it to themes/yourtheme/file-manager/templates/file-manager.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

$directories = (new Dir( GRFM_PATH ))->getFoldersTree();
$folders     = (new Dir( GRFM_PATH ))->getFolders();
$files       = [
	...(new Dir( GRFM_PATH ))->getFiles( '.[!.]*' ),
	...(new Dir( GRFM_PATH ))->getFiles( '*.*' ),
];
?>
<div class="grafema-main">
	<div class="fm">
		<div class="fm-header">
			<ul class="fm-header-list">
				<li class="fm-header-item" title="<?php I18n::t_attr( 'Go to parent folder' ); ?>">
					<button class="btn btn--icon btn--outline btn--xs"><i class="ph ph-house"></i></button>
				</li>
				<li class="fm-header-item" title="<?php I18n::t_attr( 'Back' ); ?>">
					<button class="btn btn--icon btn--outline btn--xs"><i class="ph ph-caret-left"></i></button>
				</li>
				<li class="fm-header-item" title="<?php I18n::t_attr( 'Next' ); ?>">
					<button class="btn btn--icon btn--outline btn--xs"><i class="ph ph-caret-right"></i></button>
				</li>
				<li class="fm-header-item" title="<?php I18n::t_attr( 'Update' ); ?>">
					<button class="btn btn--icon btn--outline btn--xs"><i class="ph ph-arrows-clockwise"></i></button>
				</li>
			</ul>
			<ul class="fm-header-list">
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-file-plus"></i> <?php I18n::t( 'Add file' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-folder-plus"></i> <?php I18n::t( 'Add folder' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-upload-simple"></i> <?php I18n::t( 'Upload' ); ?></button>
				</li>
			</ul>
			<ul class="fm-header-list">
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-copy"></i> <?php I18n::t( 'Copy' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-scissors"></i> <?php I18n::t( 'Cut' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-download-simple"></i> <?php I18n::t( 'Download' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-textbox"></i> <?php I18n::t( 'Rename' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-trash-simple"></i> <?php I18n::t( 'Delete' ); ?></button>
				</li>
			</ul>
			<ul class="fm-header-list">
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-file-archive"></i> <?php I18n::t( 'Archive' ); ?></button>
				</li>
				<li class="fm-header-item">
					<button class="btn btn--outline btn--xs"><i class="ph ph-file-zip"></i> <?php I18n::t( 'Extract files' ); ?></button>
				</li>
			</ul>
		</div>

		<div class="fm-top">
			<div class="df aic g-1">
				<i class="ph ph-folder-simple-dashed"></i> <code>public_html</code>/<code>dev</code>/<code>cody</code>/<code>list</code>
			</div>
			<div class="df aic g-3">
				<?php I18n::f( ':count items', count( [ ...$folders, ...$files ] ) ); ?>
				<div class="field field--xs field--outline">
					<label class="field-item">
						<input type="search" name="search" placeholder="Search">
					</label>
				</div>
			</div>
		</div>
		<div class="fm-main">
			<div class="fm-folders">
				<?php echo Tree::build( $directories, function ( int $depth, $directory ) { ?>
					<ul class="fm-folders-list" data-depth="<?php echo $depth; ?>">
						<li class="fm-folder-item">
							<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> <?php echo $directory; ?></span>
							@nested
						</li>
					</ul>
				<?php } ); ?>
			</div>
			<div class="fm-files">
				<div class="fm-files-head">
					<div>Name</div>
					<div>Modified</div>
					<div>Permissions</div>
					<div>Size</div>
				</div>
				<?php foreach ( $folders as $folder ) : ?>
					<label class="fm-files-item" data-type="folder">
						<span><input name="file" type="checkbox" value=""> <i class="ph ph-folder-simple"></i> <?php echo basename( $folder ); ?></span>
						<span>Sep 17, 2023 08:35 PM</span>
						<span title="Read & Write"><?php echo substr( sprintf( '%o', fileperms( $folder ) ), -4 ); ?></span>
						<span>177 KB</span>
					</label>
				<?php endforeach; ?>
				<?php foreach ( $files as $file ) : ?>
					<label class="fm-files-item" data-type="file">
						<span><input name="file" type="checkbox" value=""><i class="ph ph-file-pdf"></i> <?php echo basename( $file ); ?></span>
						<span><?php echo date( 'M d, Y h:i A', filemtime( $file ) ); ?></span>
						<span title="Read & Write"><?php echo substr( sprintf( '%o', fileperms( $file ) ), -4 ); ?></span>
						<span><?php echo Grafema\Helpers\Humanize::fromBytes( filesize( $file ) ); ?></span>
					</label>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</div>

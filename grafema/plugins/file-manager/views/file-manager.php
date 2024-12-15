<?php
use Grafema\I18n;
/**
 * Fields builder
 *
 * This template can be overridden by copying it to themes/yourtheme/file-manager/templates/file-manager.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
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
					<button class="btn btn--outline btn--xs"><i class="ph ph-file-plus"></i> <?php I18n::t( 'Move' ); ?></button>
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
			<code><i class="ph ph-folder-simple-dashed"></i> /public_html/dev/cody/com/list</code>
			<div class="field field--xs field--outline">
				<div class="field-item">
					<input type="search" name="search" placeholder="Search">
				</div>
			</div>
		</div>
		<div class="fm-main">
			<div class="fm-folders">
				<ul class="fm-folders-list">
					<li class="fm-folder-item">
						<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> folder</span>
						<ul class="fm-folders-list">
							<li class="fm-folder-item">
								<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> scripts</span>
							</li>
							<li class="fm-folder-item">
								<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> assets</span>
							</li>
							<li class="fm-folder-item">
								<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> views</span>
								<ul class="fm-folders-list">
									<li class="fm-folder-item">
										<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> scripts and superpuperclosedlinkand hello</span>
									</li>
									<li class="fm-folder-item">
										<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> assets</span>
									</li>
									<li class="fm-folder-item">
										<span class="fm-folder-item-name"><i class="ph ph-folder-simple"></i> views</span>
									</li>
								</ul>
							</li>
						</ul>
					</li>
				</ul>
			</div>
			<div class="fm-files">
				<div class="fm-files-head">
					<div>Name</div>
					<div>Modified</div>
					<div>Permissions</div>
					<div>Size</div>
				</div>
				<label class="fm-files-item">
					<span><i class="ph ph-folder-simple"></i> logs</span>
					<span>Sep 17, 2023 08:35 PM</span>
					<span title="Read & Write">777</span>
					<span>177 KB</span>
				</label>
				<label class="fm-files-item">
					<span><i class="ph ph-file-xls"></i> type.xml</span>
					<span>Sep 23, 2024 11:10 AM</span>
					<span title="Read & Write">655</span>
					<span>11 KB</span>
				</label>
			</div>
		</div>
	</div>
</div>

<?php
/**
 * Grafema dashboard menu.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/nav-menu.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<div class="nav-editor">
		<div class="nav-editor-side">
			<?php Dashboard\Form::print( 'grafema-menu-editor', GRFM_DASHBOARD . 'forms/grafema-menu-editor.php' ); ?>
		</div>
		<div class="nav-editor-main">
			<ul class="nav-editor-list">
				<li class="nav-editor-item">
					<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 1</span>
					<ul class="nav-editor-list">
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 1.1</span>
							<ul class="nav-editor-list"></ul>
						</li>
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 1.2</span>
							<ul class="nav-editor-list"></ul>
						</li>
					</ul>
				</li>
				<li class="nav-editor-item">
					<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 2</span>
					<ul class="nav-editor-list"></ul>
				</li>
				<li class="nav-editor-item">
					<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3</span>
					<ul class="nav-editor-list">
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.1</span>
							<ul class="nav-editor-list"></ul>
						</li>
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.2</span>
							<ul class="nav-editor-list"></ul>
						</li>
					</ul>
				</li>
				<li class="nav-editor-item">
					<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 4</span>
					<ul class="nav-editor-list">
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.1</span>
							<ul class="nav-editor-list"></ul>
						</li>
						<li class="nav-editor-item">
							<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.2</span>
							<ul class="nav-editor-list">
								<li class="nav-editor-item">
									<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.1</span>
									<ul class="nav-editor-list"></ul>
								</li>
								<li class="nav-editor-item">
									<span class="nav-editor-item-text"><i class="ph ph-dots-six-vertical"></i> Item 3.2</span>
									<ul class="nav-editor-list"></ul>
								</li>
							</ul>
						</li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="nav-editor-tools">

		</div>
	</div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        let nestedSortables = [].slice.call(document.querySelectorAll('.nav-editor-list'));
        nestedSortables.forEach(el => {
            new Sortable(el, {
                group: 'nested',
                animation: 150,
                fallbackOnBody: true,
                swapThreshold: 0.65,
            });
        });
    });
</script>
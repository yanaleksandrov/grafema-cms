<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Addons list for install.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/plugins-install.php
 *
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php
	View::print(
		'templates/table/header',
		[
			'title' => I18n::__( 'Add Plugins' ),
		]
	);
    ?>

	<!-- plugins list start -->
	<div class="plugin p-7 sm:p-5 dg g-5" x-init="$ajax('extensions/get').then(response => items = response.items)">
		<template x-for="item in items">
			<div class="card card-border card-hover jcsb">
				<div class="df aifs p-5 g-5">
					<div class="avatar avatar--md avatar--rounded" :style="`background-image: url(${plugin.image})`"></div>
					<div class="dg g-2 fs-14">
						<h4 class="fw-600 fs-18 df jcsb">
							<span x-text="item.title"></span>
							<span class="badge badge--green-lt">Installed</span>
						</h4>
						<div class="t-muted" x-text="item.description"></div>
						<div>by <a target="_blank" :href="`${item.author_link}`" x-text="item.author">Our Team</a></div>
					</div>
				</div>
				<div class="dg g-1 fs-13 p-5 pt-4 pb-4 bg-muted-lt">
					<div class="df jcsb">
						<span x-text="item.installations"></span> <span><strong>Last Updated</strong>: <span x-text="item.updated"></span></span>
					</div>
					<div class="df jcsb">
						<div class="df g-2">
							<span class="df aic g-1 t-orange">
								<template x-for="index in [1, 2, 3, 4, 5]">
									<i class="ph ph-star" :class="item.rating < index && 't-muted'"></i>
								</template>
							</span>
							<span x-text="`[${item.reviews}]`"></span>
						</div>
						<div><strong>Compatible</strong> with your JB version</div>
					</div>
				</div>
			</div>
		</template>
	</div>
	<!-- plugins list end -->
</div>

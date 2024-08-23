<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Themes list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/themes.php
 *
 * @package Grafema\Templates
 * @since   2025.1
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
			'title' => I18n::__( 'Themes' ),
		]
	);
    ?>
	<div class="themes">
		<div class="themes__item">
			<div class="themes__image" data-title="Theme Details" style="background-image: url(https://dev.codyshop.ru/wp-content/themes/rgbcode/screenshot.png)"></div>
			<div class="dg g-2 py-4 px-2">
				<div class="fw-600 fs-16 df jcsb aic">Rgbcode <span class="badge badge--green-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-orange">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v2025.1</span>
				</div>
			</div>
		</div>
		<div class="themes__item">
			<div class="themes__image" data-title="Theme Details" style="background-image: url(https://dev.codyshop.ru/wp-content/themes/daria/screenshot.jpg)"></div>
			<div class="dg g-2 py-4 px-2">
				<div class="fw-600 fs-16 df jcsb aic">Daria <span class="badge badge--green-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-orange">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v2025.1</span>
				</div>
			</div>
		</div>
		<div class="themes__item">
			<div class="themes__image" data-title="Theme Details" style="background-image: url(//ts.w.org/wp-content/themes/twentytwentytwo/screenshot.png)"></div>
			<div class="dg g-2 py-4 px-2">
				<div class="fw-600 fs-16 df jcsb aic">Twenty Twenty-Two <span class="badge badge--green-lt">Installed</span></div>
				<div class="t-muted">Multipurpose theme for blog, startup, portfolio, business & e-commerce.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-orange">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v2025.1</span>
				</div>
			</div>
		</div>
		<div class="themes__item">
			<div class="themes__image" data-title="Theme Details" style="background-image: url(//ts.w.org/wp-content/themes/lemmony/screenshot.png)"></div>
			<div class="dg g-2 py-4 px-2">
				<div class="fw-600 fs-16 df jcsb aic">Lemmony Health <span class="badge badge--green-lt">Installed</span></div>
				<div class="t-muted">Lemmony Health is multipurpose eCommerce theme.</div>
				<div class="df jcsb fs-12">
					<span class="t-muted">This theme has not been rated yet.</span>
					<span class="t-muted">v2025.1</span>
				</div>
			</div>
		</div>
		<div class="themes__item">
			<div class="themes__image" data-title="Theme Details" style="background-image: url(//i0.wp.com/themes.svn.wordpress.org/twentytwentyfour/1.2/screenshot.png)"></div>
			<div class="dg g-2 py-4 px-2">
				<div class="fw-600 fs-16 df jcsb aic">Threadwears <span class="badge badge--green-lt">Installed</span></div>
				<div class="t-muted">Threadwears is a light and elegant free eCommerce Grafema block theme.</div>
				<div class="df jcsb fs-12">
					<span class="df g-2">
						<span class="df aic g-1 t-orange">
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
							<i class="ph ph-star"></i>
						</span>
						[973]
					</span>
					<span class="t-muted">v2025.1</span>
				</div>
			</div>
		</div>
	</div>
</div>

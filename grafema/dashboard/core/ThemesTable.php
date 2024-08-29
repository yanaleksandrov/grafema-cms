<?php
namespace Dashboard;

use Grafema\I18n;

use Dashboard\Builders\Row;
use Dashboard\Builders\Cell;

final class ThemesTable {

	public function tag(): string {
		return '';
	}

	public function data(): array {
		return [
			[
				'title'       => 'Rgbcode',
				'description' => 'Multipurpose theme for blog, startup, portfolio, business & e-commerce.',
				'screenshot'  => 'https://dev.codyshop.ru/wp-content/themes/rgbcode/screenshot.png',
				'reviews'     => 973,
				'version'     => '2025.1',
				'rating'      => 3.75,
				'installed'   => true,
			],
			[
				'title'       => 'Daria',
				'description' => 'Multipurpose theme for blog, startup, portfolio, business & e-commerce.',
				'screenshot'  => 'https://dev.codyshop.ru/wp-content/themes/daria/screenshot.jpg',
				'reviews'     => 111,
				'version'     => '2025.1',
				'rating'      => 4.5,
				'installed'   => false,
			],
			[
				'title'       => 'Twenty Twenty-Two',
				'description' => 'Multipurpose theme for blog, startup, portfolio, business & e-commerce.',
				'screenshot'  => '//ts.w.org/wp-content/themes/twentytwentytwo/screenshot.png',
				'reviews'     => 200,
				'version'     => '2025.1',
				'rating'      => 5,
				'installed'   => false,
			],
			[
				'title'       => 'Lemony Health',
				'description' => 'Lemony Health is multipurpose eCommerce theme for any goals.',
				'screenshot'  => '//ts.w.org/wp-content/themes/lemmony/screenshot.png',
				'reviews'     => 0,
				'version'     => '2025.1',
				'rating'      => 0,
				'installed'   => false,
			],
			[
				'title'       => 'Threaders',
				'description' => 'Threaders is a light and elegant free eCommerce Grafema block theme.',
				'screenshot'  => '//i0.wp.com/themes.svn.wordpress.org/twentytwentyfour/1.2/screenshot.png',
				'reviews'     => 973,
				'version'     => '2025.1',
				'rating'      => 3.75,
				'installed'   => true,
			],
		];
	}

	public function dataBefore(): string {
		return '<div class="themes">';
	}

	public function dataAfter(): string {
		return '</div>';
	}

	public function rows(): array {
		return [
			Row::add( '' ),
		];
	}

	public function columns(): array {
		return [
			Cell::add( 'theme' )->view( 'theme' ),
		];
	}

	public function attributes(): array {
		return [
			'class' => 'themes',
		];
	}

	public function notFoundContent(): array {
		return [
			'title'       => I18n::_t( 'Themes not found' ),
			'description' => I18n::_t( 'You don\'t have any themes installed yet, <a @click="$modal.open(\'grafema-modals-post\')">download them</a>' ),
		];
	}

	public function headerContent(): array {
		return [
			'title' => I18n::_t( 'Themes' ),
		];
	}

	public function headerTemplate(): string {
		return '';
	}

	public function notFoundAfter(): string {
		return '';
	}

	public function notFoundBefore(): string {
		return '';
	}

	public function cellHeadTemplate(): string {
		return '';
	}
}

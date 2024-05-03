<?php
/**
 * This file is part of Grafema CMS.
 *
 * @link     https://www.grafema.io
 * @contact  team@core.io
 * @license  https://github.com/grafema-team/grafema/LICENSE.md
 */

namespace Dashboard\Api;

class Extensions extends \Grafema\Api\Handler
{
	/**
	 * Endpoint name.
	 */
	public string $endpoint = 'extensions';

	/**
	 * Get all items.
	 *
	 * @url    GET api/extensions
	 */
	public function index(): array {
		return [
			[
				'title'           => 'Classic Editor 1 and very longadable and many more text',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Active',
						'class' => 'bg-herbal-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'My test link',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Active',
						'class' => 'bg-herbal-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 3',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
					[
						'title' => 'New link',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Active',
						'class' => 'bg-herbal-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 4',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Active',
						'class' => 'bg-herbal-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Draft',
						'class' => 'bg-blue-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Draft',
						'class' => 'bg-blue-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Draft',
						'class' => 'bg-blue-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Draft',
						'class' => 'bg-blue-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
			[
				'title'           => 'Classic Editor 2',
				'description'     => 'The best anti-spam protection to block spam comments and spam in a contact form. The most trusted antispam solution.',
				'image'           => 'https://s.w.org/plugins/geopattern-icon/classic-widgets.svg',
				'author'          => [
					[
						'title' => 'Grafema Team',
						'href'  => 'https://core.com',
					],
				],
				'categories'      => [
					[
						'title' => 'Test category',
						'href'  => 'https://core.com',
					],
				],
				'installed'       => true,
				'active'          => false,
				'installations'   => '5+ Million Installations',
				'status'          => [
					[
						'title' => 'Install',
						'class' => 'bg-red-lt',
					],
				],
				'date'            => '2023/09/18 at 10:13 am',
				'reviews'         => 23,
				'rating'          => 4,
				'grafema_version' => 1,
			],
		];
	}
}

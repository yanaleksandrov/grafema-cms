<?php
use Grafema\I18n;
use Grafema\Url;
use Grafema\Option;
use Grafema\Sanitizer;

/**
 * Website settings in dashboard
 *
 * @since 2025.1
 */
Dashboard\Form::enqueue(
	'grafema-settings',
	[
		'class'   => 'tab tab--vertical',
		'x-data'  => sprintf( "tab('%s')", Sanitizer::prop( $_GET['tab'] ?? 'general' ) ),
		'@change' => '$ajax("options/update")',
	],
	[
		[
			'name'    => 'general',
			'type'    => 'tab',
			'label'   => I18n::_t( 'General' ),
			'caption' => I18n::_t( 'main settings' ),
			'icon'    => 'ph ph-tree-structure',
			'fields'  => [
				[
					'name'   => 'website',
					'type'   => 'group',
					'label'  => I18n::_t( 'Website' ),
					'fields' => [
						[
							'type'        => 'text',
							'name'        => 'site[name]',
							'label'       => I18n::_t( 'Name' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'A quick snapshot of your website' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'       => Option::get( 'site.name' ),
								'required'    => true,
								'placeholder' => I18n::_t( 'e.g. Google' ),
							],
						],
						[
							'type'        => 'text',
							'name'        => 'site[tagline]',
							'label'       => I18n::_t( 'Tagline' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'In a few words, explain what this site is about' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'       => Option::get( 'site.tagline' ),
								'placeholder' => I18n::_t( 'e.g. Just another Grafema site' ),
							],
						],
						[
							'type'        => 'select',
							'name'        => 'site[language]',
							'label'       => I18n::_t( 'Language' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'Some description' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'site.language' ),
							],
							'options' => [
								'us' => [
									'image'   => 'assets/images/flags/us.svg',
									'content' => I18n::_t( 'English - english' ),
								],
								'ru' => [
									'image'   => 'assets/images/flags/ru.svg',
									'content' => I18n::_t( 'Russian - русский' ),
								],
								'he' => [
									'image'   => 'assets/images/flags/il.svg',
									'content' => I18n::_t( 'עִבְרִית - Hebrew' ),
								],
							],
						],
						[
							'type'        => 'text',
							'name'        => 'site[url]',
							'label'       => I18n::_t( 'Site address (URL)' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'A quick snapshot of your website' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'       => Option::get( 'site.url' ),
								'placeholder' => I18n::_t( 'e.g. Google' ),
								'required'    => true,
							],
						],
					],
				],
				[
					'name'   => 'administrator',
					'type'   => 'group',
					'label'  => I18n::_t( 'Administrator' ),
					'fields' => [
						[
							'type'        => 'text',
							'name'        => 'owner[email]',
							'label'       => I18n::_t( 'Owner email address' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '<i class="ph ph-at"></i>',
							'after'       => '',
							'instruction' => I18n::_t( 'This address is used for admin purposes. If you change this, an email will be sent to your new address to confirm it. The new address will not become active until confirmed' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'    => Option::get( 'owner.email' ),
								'required' => true,
							],
						],
					],
				],
				[
					'type'    => 'group',
					'label'   => I18n::_t( 'Users' ),
					'name'    => 'users',
					'class'   => '',
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'toggle',
							'name'        => 'users[membership]',
							'label'       => I18n::_t( 'Anyone can register' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'users.membership' ),
							],
						],
						[
							'type'        => 'select',
							'name'        => 'users[role]',
							'label'       => I18n::_t( 'New user default role' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [
								[
									'field'    => 'users[membership]',
									'operator' => '===',
									'value'    => '123121',
								]
							],
							'attributes'  => [
								'value' => Option::get( 'users.role' ),
							],
							'options'     => [
								'subscriber'    => I18n::_t( 'Subscriber' ),
								'contributor'   => I18n::_t( 'Contributor' ),
								'author'        => I18n::_t( 'Author' ),
								'editor'        => I18n::_t( 'Editor' ),
								'administrator' => I18n::_t( 'Administrator' ),
							],
						],
					],
				],
				[
					'name'   => 'dates',
					'type'   => 'group',
					'label'  => I18n::_t( 'Dates & time' ),
					'fields' => [
						[
							'name'     => 'date-format',
							'type'     => 'custom',
							'callback' => function () {
								ob_start();
								?>
								<div class="dg g-2">
									<label class="dg">
										<span class="df aic jcsb fw-600"><?php I18n::t( 'Date Format' ); ?></span>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">April 3, 2021</span> <code class="badge badge--dark-lt">F j, Y</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">2021-04-03</span> <code class="badge badge--dark-lt">Y-m-d</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">04/03/2021</span> <code class="badge badge--dark-lt">m/d/Y</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">Custom</span> <input class="mw-80" type="text" name="item">
									</label>
									<div class="fs-13 t-muted"><a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get formats list</a> on php.net</div>
								</div>
								<?php
								return ob_get_clean();
							},
						],
						[
							'name'     => 'time-format',
							'type'     => 'custom',
							'callback' => function () {
								ob_start();
								?>
								<div class="dg g-2">
									<label class="dg">
										<span class="df aic jcsb fw-600"><?php I18n::t( 'Time Format' ); ?></span>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">17:22</span> <code class="badge badge--dark-lt">H:i</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">5:22 PM</span> <code class="badge badge--dark-lt">g:i A</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">12:50am</span> <code class="badge badge--dark-lt">g:ia</code>
									</label>
									<label class="df aic jcsb">
										<span><input class="mr-2" type="radio" name="item">Custom</span> <input class="mw-80" type="text" name="item">
									</label>
									<div class="fs-13 t-muted"><a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get full time formats list</a> on php.net</div>
								</div>
								<?php
								return ob_get_clean();
							},
						],
						[
							'type'        => 'select',
							'name'        => 'week-starts-on',
							'label'       => I18n::_t( 'Week Starts On' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get full time formats list</a> on php.net',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'week-starts-on' ),
							],
							'options' => [
								'0' => I18n::_t( 'Sunday' ),
								'1' => I18n::_t( 'Monday' ),
								'2' => I18n::_t( 'Tuesday' ),
								'3' => I18n::_t( 'Wednesday' ),
								'4' => I18n::_t( 'Thursday' ),
								'5' => I18n::_t( 'Friday' ),
								'6' => I18n::_t( 'Saturday' ),
							],
						],
						[
							'type'        => 'select',
							'name'        => 'timezone',
							'label'       => I18n::_t( 'Timezone' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'Choose either a city in the same timezone as you or a UTC (Coordinated Universal Time) time offset.' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'timezone' ),
							],
							'options' => [
								'subscriber'    => I18n::_t( 'Subscriber' ),
								'contributor'   => I18n::_t( 'Contributor' ),
								'author'        => I18n::_t( 'Author' ),
								'editor'        => I18n::_t( 'Editor' ),
								'administrator' => I18n::_t( 'Administrator' ),
							],
						],
					],
				],
			],
		],
		[
			'name'    => 'reading',
			'type'    => 'tab',
			'label'   => I18n::_t( 'Reading' ),
			'caption' => I18n::_t( 'displaying posts' ),
			'icon'    => 'ph ph-book-open-text',
			'fields'  => [
				[
					'name'    => 'search_engine',
					'type'    => 'group',
					'label'   => I18n::_t( 'Search engine' ),
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'toggle',
							'name'        => 'discourage',
							'label'       => I18n::_t( 'Discourage search engines from indexing this site' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'discourage' ),
							],
						],
					],
				],
			],
		],
		[
			'name'    => 'discussions',
			'type'    => 'tab',
			'label'   => I18n::_t( 'Discussions' ),
			'caption' => I18n::_t( 'comments' ),
			'icon'    => 'ph ph-chats-circle',
			'fields'  => [
				[
					'name'    => 'comments',
					'type'    => 'group',
					'label'   => I18n::_t( 'Post comments' ),
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'toggle',
							'name'        => 'comments[default_status]',
							'label'       => I18n::_t( 'Allow people to submit comments on new posts' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'comments.default_status' ),
							],
						],
						[
							'type'        => 'toggle',
							'name'        => 'comments[registration]',
							'label'       => I18n::_t( 'Allow people to submit comments on new posts' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'comments.registration' ),
							],
						],
						[
							'type'        => 'toggle',
							'name'        => 'comments[close][after]',
							'label'       => I18n::_t( 'Automatically close comments on posts older than' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'comments.close.after' ),
							],
						],
					],
				],
				[
					'name'    => 'comments',
					'type'    => 'group',
					'label'   => I18n::_t( 'Email me whenever' ),
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'toggle',
							'name'        => 'comments[notify][posts]',
							'label'       => I18n::_t( 'Anyone posts a comment' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'comments.notify.posts' ),
							],
						],
						[
							'type'        => 'toggle',
							'name'        => 'comments[notify][moderation]',
							'label'       => I18n::_t( 'A comment is held for moderation' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'comments.notify.moderation' ),
							],
						],
					],
				],
			],
		],
		[
			'name'    => 'storage',
			'type'    => 'tab',
			'label'   => I18n::_t( 'Storage' ),
			'caption' => I18n::_t( 'media options' ),
			'icon'    => 'ph ph-lockers',
			'fields'  => [
				[
					'name'    => 'images',
					'type'    => 'group',
					'label'   => I18n::_t( 'File uploading' ),
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'select',
							'name'        => 'images[format]',
							'label'       => I18n::_t( 'Convert images to format' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => I18n::_t( 'Will provide a higher compression ratio' ),
							'tooltip'     => I18n::_t( 'Can lead to loss of detail and image quality, as well as increase the cost of your hosting resources' ),
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [],
							'options'     => [
								''     => I18n::_t( 'Do not convert' ),
								'wepb' => I18n::_t( 'Webp' ),
							],
						],
						[
							'type'        => 'select',
							'name'        => 'images[organization]',
							'label'       => I18n::_t( 'Files organization' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [],
							'options'     => [
								'yearmonth' => I18n::_t( 'Into month- and year-based folders' ),
								'hash'      => I18n::_t( 'Into hash-based folders' ),
							],
						],
					],
				],
				[
					'name'    => 'images',
					'type'    => 'group',
					'label'   => I18n::_t( 'Limits' ),
					'columns' => 2,
					'fields'  => [
						[
							'type'        => 'number',
							'name'        => 'limits[owner]',
							'label'       => I18n::_t( 'For owner' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => I18n::_t( 'MB' ),
							'instruction' => I18n::_t( 'The amount of disk space for files.' ),
							'tooltip'     => I18n::_t( 'When the threshold is reached, the administrator will receive an email notification.' ),
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'limits.owner', 10 ),
							],
						],
						[
							'type'        => 'number',
							'name'        => 'limits[users]',
							'label'       => I18n::_t( 'For users' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '',
							'after'       => I18n::_t( 'MB' ),
							'instruction' => I18n::_t( 'The maximum size of files allowed per user.' ),
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value' => Option::get( 'limits.users' ),
							],
						],
					],
				],
			],
		],
		[
			'name'        => 'permalinks',
			'type'        => 'tab',
			'label'       => I18n::_t( 'Permalinks' ),
			'caption'     => I18n::_t( 'URLs structure' ),
			'description' => I18n::_t( 'custom URL structures can improve the aesthetics, usability, and forward-compatibility of your links' ),
			'icon'        => 'ph ph-link',
			'fields'      => [
				[
					'name'    => 'dates',
					'type'    => 'group',
					'label'   => I18n::_t( 'Pages' ),
					'columns' => 1,
					'fields'  => [
						[
							'type'        => 'text',
							'name'        => 'permalinks[pages][single]',
							'label'       => I18n::_t( 'Single page' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => sprintf( '<code class="badge"><i class="ph ph-link"></i> %s</code>', Url::site() ),
							'after'       => '',
							'instruction' => I18n::_t( 'Select the permalink structure for your website. Including the %slug% tag makes links easy to understand, and can help your posts rank higher in search engines.' ),
							'tooltip'     => I18n::_t( 'ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.' ),
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'    => Option::get( 'permalinks.pages.single' ),
								'required' => true,
							],
						],
						[
							'type'        => 'text',
							'name'        => 'permalinks[pages][categories]',
							'label'       => I18n::_t( 'Categories' ),
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => sprintf( '<code class="badge"><i class="ph ph-link"></i> %s</code>', Url::site() ),
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'value'    => Option::get( 'permalinks.pages.categories' ),
								'required' => true,
							],
						],
					],
				],
			],
		],
	]
);
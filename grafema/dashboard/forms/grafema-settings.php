<?php
use Grafema\I18n;
use Grafema\Option;

/**
 * Website settings in dashboard
 *
 * @since 1.0.0
 */
Form::register(
	'grafema-settings',
	[
		'class'   => 'tab tab--vertical',
		'x-data'  => "{tab:'general'}",
		'@change' => '$ajax("save-settings")',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'    => 'general',
					'type'    => 'tab',
					'label'   => I18n::__( 'General' ),
					'caption' => I18n::__( 'main settings' ),
					'icon'    => 'ph ph-browsers',
					'fields'  => [
						[
							'name'   => 'website',
							'type'   => 'group',
							'label'  => I18n::__( 'Website' ),
							'fields' => [
								[
									'name'        => 'site[name]',
									'type'        => 'text',
									'label'       => I18n::__( 'Name' ),
									'instruction' => I18n::__( 'A quick snapshot of your website.' ),
									'attributes'  => [
										'value'       => Option::get( 'site.name' ),
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Google' ),
									],
								],
								[
									'name'        => 'site[tagline]',
									'type'        => 'text',
									'label'       => I18n::__( 'Tagline' ),
									'instruction' => I18n::__( 'In a few words, explain what this site is about.' ),
									'attributes'  => [
										'value'       => Option::get( 'site.tagline' ),
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Just another Grafema site' ),
									],
								],
								[
									'name'        => 'site[language]',
									'type'        => 'select',
									'label'       => I18n::__( 'Language' ),
									'instruction' => I18n::__( 'Some description' ),
									'value'       => Option::get( 'site.language' ),
									'attributes'  => [
										'x-select' => '{"showSearch":1}',
									],
									'options' => [
										'us' => [
											'image'   => 'assets/images/flags/us.svg',
											'content' => I18n::__( 'English - english' ),
										],
										'ru' => [
											'image'   => 'assets/images/flags/ru.svg',
											'content' => I18n::__( 'Russian - русский' ),
										],
										'he' => [
											'image'   => 'assets/images/flags/il.svg',
											'content' => I18n::__( 'עִבְרִית - Hebrew' ),
										],
									],
								],
								[
									'name'        => 'site[url]',
									'type'        => 'text',
									'label'       => I18n::__( 'Site address (URL)' ),
									'instruction' => I18n::__( 'A quick snapshot of your website.' ),
									'attributes'  => [
										'value'       => Option::get( 'site.url' ),
										'required'    => true,
										'placeholder' => I18n::__( 'e.g. Google' ),
									],
								],
							],
						],
						[
							'name'   => 'administrator',
							'type'   => 'group',
							'label'  => I18n::__( 'Administrator' ),
							'fields' => [
								[
									'name'        => 'owner[email]',
									'type'        => 'text',
									'label'       => I18n::__( 'Owner email address!' ),
									'instruction' => I18n::__( 'This address is used for admin purposes. If you change this, an email will be sent to your new address to confirm it. The new address will not become active until confirmed.' ),
									'before'      => '<i class="ph ph-at"></i>',
									'attributes'  => [
										'value'    => Option::get( 'owner.email' ),
										'required' => true,
									],
								],
							],
						],
						[
							'name'   => 'users',
							'type'   => 'group',
							'label'  => I18n::__( 'Users' ),
							'fields' => [
								[
									'name'        => 'users[role]',
									'type'        => 'select',
									'label'       => I18n::__( 'New User Default Role' ),
									'instruction' => I18n::__( 'Some description' ),
									'value'       => Option::get( 'users.role' ),
									'attributes'  => [
										'x-select' => '',
									],
									'options' => [
										'subscriber'    => I18n::__( 'Subscriber' ),
										'contributor'   => I18n::__( 'Contributor' ),
										'author'        => I18n::__( 'Author' ),
										'editor'        => I18n::__( 'Editor' ),
										'administrator' => I18n::__( 'Administrator' ),
									],
								],
								[
									'name'        => 'users[membership]',
									'type'        => 'select',
									'label'       => I18n::__( 'Membership' ),
									'instruction' => I18n::__( 'Some description' ),
									'value'       => Option::get( 'users.membership' ),
									'attributes'  => [
										'x-select' => '',
									],
									'options' => [
										'subscriber' => I18n::__( 'Anyone can register' ),
									],
								],
							],
						],
						[
							'name'   => 'dates',
							'type'   => 'group',
							'label'  => I18n::__( 'Dates & time' ),
							'fields' => [
								[
									'name'     => 'date-format',
									'type'     => 'custom',
									'callback' => function () {
										?>
										<div class="dg g-2">
											<label class="dg">
												<span class="df aic jcsb fw-600"><?php I18n::e( 'Date Format' ); ?></span>
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
									},
								],
								[
									'name'     => 'time-format',
									'type'     => 'custom',
									'callback' => function () {
										?>
										<div class="dg g-2">
											<label class="dg">
												<span class="df aic jcsb fw-600"><?php I18n::e( 'Time Format' ); ?></span>
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
									},
								],
								[
									'name'        => 'week-starts-on',
									'type'        => 'select',
									'label'       => I18n::__( 'Week Starts On' ),
									'instruction' => '<a href="https://www.php.net/manual/en/datetime.format.php" target="_blank">Get full time formats list</a> on php.net',
									'value'       => Option::get( 'week-starts-on' ),
									'attributes'  => [
										'x-select' => '',
									],
									'options' => [
										'0' => I18n::__( 'Sunday' ),
										'1' => I18n::__( 'Monday' ),
										'2' => I18n::__( 'Tuesday' ),
										'3' => I18n::__( 'Wednesday' ),
										'4' => I18n::__( 'Thursday' ),
										'5' => I18n::__( 'Friday' ),
										'6' => I18n::__( 'Saturday' ),
									],
								],
								[
									'name'        => 'timezone',
									'type'        => 'select',
									'label'       => I18n::__( 'Timezone' ),
									'instruction' => I18n::__( 'Choose either a city in the same timezone as you or a UTC (Coordinated Universal Time) time offset.' ),
									'value'       => Option::get( 'timezone' ),
									'attributes'  => [
										'x-select' => '',
									],
									'options' => [
										'subscriber'    => I18n::__( 'Subscriber' ),
										'contributor'   => I18n::__( 'Contributor' ),
										'author'        => I18n::__( 'Author' ),
										'editor'        => I18n::__( 'Editor' ),
										'administrator' => I18n::__( 'Administrator' ),
									],
								],
							],
						],
					],
				],
				[
					'name'    => 'writing',
					'type'    => 'tab',
					'label'   => I18n::__( 'Writing' ),
					'caption' => I18n::__( 'displaying posts' ),
					'icon'    => 'ph ph-article-ny-times',
					'fields'  => [],
				],
				[
					'name'    => 'reading',
					'type'    => 'tab',
					'label'   => I18n::__( 'Reading' ),
					'caption' => I18n::__( 'displaying posts' ),
					'icon'    => 'ph ph-book-open-text',
					'fields'  => [
						[
							'name'    => 'search_engine',
							'type'    => 'group',
							'label'   => I18n::__( 'Search engine' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'       => 'discourage',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'Discourage search engines from indexing this site' ),
									'attributes' => [
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
					'label'   => I18n::__( 'Discussions' ),
					'caption' => I18n::__( 'comments & chat' ),
					'icon'    => 'ph ph-chats-circle',
					'fields'  => [
						[
							'name'    => 'comments',
							'type'    => 'group',
							'label'   => I18n::__( 'Post comments' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'       => 'comments[default_status]',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'Allow people to submit comments on new posts' ),
									'attributes' => [
										'value' => Option::get( 'comments.default_status' ),
									],
								],
								[
									'name'       => 'comments[registration]',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'Users must be registered and logged in to comment' ),
									'attributes' => [
										'value' => Option::get( 'comments.registration' ),
									],
								],
								[
									'name'       => 'comments[close][after]',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'Automatically close comments on posts older than' ),
									'attributes' => [
										'value' => Option::get( 'comments.close.after' ),
									],
								],
							],
						],
						[
							'name'    => 'comments',
							'type'    => 'group',
							'label'   => I18n::__( 'Email me whenever' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'       => 'comments[notify][posts]',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'Anyone posts a comment' ),
									'attributes' => [
										'value' => Option::get( 'comments.notify.posts' ),
									],
								],
								[
									'name'       => 'comments[notify][moderation]',
									'type'       => 'checkbox',
									'label'      => I18n::__( 'A comment is held for moderation' ),
									'attributes' => [
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
					'label'   => I18n::__( 'Storage' ),
					'caption' => I18n::__( 'media options' ),
					'icon'    => 'ph ph-camera',
					'fields'  => [
						[
							'name'    => 'images',
							'type'    => 'group',
							'label'   => I18n::__( 'Images' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'        => 'images[webp]',
									'type'        => 'checkbox',
									'label'       => I18n::__( 'Convert to WebP format' ),
									'instruction' => I18n::__( 'Will provide a higher compression ratio' ),
									'tooltip'     => I18n::__( 'Can lead to loss of detail and image quality, as well as increase the cost of your hosting resources' ),
									'attributes'  => [
										'value' => Option::get( 'images.webp' ),
									],
								],
							],
						],
						[
							'name'    => 'images',
							'type'    => 'group',
							'label'   => I18n::__( 'Limits' ),
							'columns' => 2,
							'fields'  => [
								[
									'name'        => 'limits[owner]',
									'type'        => 'number',
									'label'       => I18n::__( 'For owner' ),
									'after'       => I18n::__( 'MB' ),
									'instruction' => I18n::__( 'The amount of disk space for files.' ),
									'tooltip'     => I18n::__( 'When the threshold is reached, the administrator will receive an email notification.' ),
									'attributes'  => [
										'value' => Option::get( 'limits.owner' ),
									],
								],
								[
									'name'        => 'limits[users]',
									'type'        => 'number',
									'label'       => I18n::__( 'For users' ),
									'after'       => I18n::__( 'MB' ),
									'instruction' => I18n::__( 'The maximum size of files allowed per user.' ),
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
					'label'       => I18n::__( 'Permalinks' ),
					'caption'     => I18n::__( 'URLs structure' ),
					'description' => I18n::__( 'custom URL structures can improve the aesthetics, usability, and forward-compatibility of your links' ),
					'icon'        => 'ph ph-link',
					'fields'      => [
						[
							'name'    => 'dates',
							'type'    => 'group',
							'label'   => I18n::__( 'Pages' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'        => 'permalinks[pages][single]',
									'type'        => 'text',
									'label'       => I18n::__( 'Single page' ),
									'before'      => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'tooltip'     => I18n::__( 'ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.' ),
									'instruction' => I18n::__( 'Select the permalink structure for your website. Including the %slug% tag makes links easy to understand, and can help your posts rank higher in search engines.' ),
									'attributes'  => [
										'required' => true,
										'value'    => Option::get( 'permalinks.pages.single' ),
									],
								],
								[
									'name'       => 'permalinks[pages][categories]',
									'type'       => 'text',
									'label'      => I18n::__( 'Categories' ),
									'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'attributes' => [
										'required' => true,
										'value'    => Option::get( 'permalinks.pages.categories' ),
									],
								],
							],
						],
						[
							'name'    => 'dates',
							'type'    => 'group',
							'label'   => I18n::__( 'Products' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'        => 'permalinks[products][single]',
									'type'        => 'text',
									'label'       => I18n::__( 'Single product' ),
									'before'      => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'tooltip'     => I18n::__( 'ZIP Code must be US or CDN format. You can use an extended ZIP+4 code to determine address more accurately.' ),
									'instruction' => I18n::__( 'Select the permalink structure for your website. Including the %slug% tag makes links easy to understand, and can help your posts rank higher in search engines.' ),
									'attributes'  => [
										'required' => true,
										'value'    => Option::get( 'permalinks.products.single' ),
									],
								],
								[
									'name'       => 'permalinks[pages][categories]',
									'type'       => 'text',
									'label'      => I18n::__( 'Products categories' ),
									'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'attributes' => [
										'required' => true,
										'value'    => Option::get( 'permalinks.products.categories' ),
									],
								],
								[
									'name'       => 'permalinks[pages][tags]',
									'type'       => 'text',
									'label'      => I18n::__( 'Products tags' ),
									'before'     => '<i class="ph ph-link"></i><code class="badge">https://cms.codyshop.ru/</code>',
									'attributes' => [
										'required' => true,
										'value'    => Option::get( 'permalinks.products.tags' ),
									],
								],
							],
						],
					],
				],
			]
		);
	}
);
<?php
use Grafema\I18n;
use Grafema\Url;
use Grafema\Sanitizer;

/**
 * Profile page.
 *
 * @since 1.0.0
 */
Dashboard\Form::register(
	'grafema-user-profile',
	[
		'class'  => 'tab',
		'x-data' => sprintf( "tab('%s')", Sanitizer::key( $_GET['tab'] ?? 'profile' ) ),
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'type'        => 'image',
					'name'        => 'avatar',
					'label'       => I18n::__( 'Upload avatar' ),
					'label_class' => '',
					'class'       => 'dg p-7 pt-6 pb-5 bg-gray-lt',
					'description' => I18n::__( 'Click to upload or drag & drop' ),
					'tooltip'     => I18n::__( 'This is tooltip' ),
					'attributes'  => [
						'required' => false,
						'@change'  => '[...$refs.uploader.files].map(file => $ajax("upload/media").then(response => files.unshift(response[0])))',
					],
				],
				[
					'type'          => 'tab',
					'label'         => I18n::__( 'Profile' ),
					'name'          => 'profile',
					'caption'       => '',
					'description'   => '',
					'icon'          => 'ph ph-user',
					'class_menu'    => 'bg-gray-lt',
					'class_button'  => 'ml-7',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'   => 'theme',
							'type'   => 'group',
							'label'  => I18n::__( 'Contact info' ),
							'fields' => [
								[
									'type'        => 'email',
									'label'       => I18n::__( 'Your email (required)' ),
									'name'        => 'email',
									'value'       => Grafema\User::current()?->email,
									'placeholder' => I18n::__( 'Enter user email' ),
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'before'      => '<i class="ph ph-at"></i>',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Is not displayed anywhere. It is used to work with the account and system notifications' ),
									'attributes'  => [
										'x-autocomplete' => '',
										'placeholder'    => I18n::__( 'e.g. user@gmail.com' ),
									],
									'conditions' => [],
								],
							],
						],
						[
							'name'    => 'name',
							'type'    => 'group',
							'label'   => I18n::__( 'Name' ),
							'columns' => 2,
							'fields'  => [
								[
									'type'        => 'text',
									'label'       => I18n::__( 'Login' ),
									'name'        => 'login',
									'value'       => Grafema\User::current()?->login,
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'copy'        => 1,
									'before'      => '<i class="ph ph-user"></i>',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Cannot be changed because used to log in to your account' ),
									'attributes'  => [
										'readonly'    => true,
										'placeholder' => I18n::__( 'e.g. admin' ),
									],
									'conditions'  => [],
								],
								[
									'type'        => 'text',
									'label'       => I18n::__( 'Nicename (required)' ),
									'name'        => 'nicename',
									'value'       => Grafema\User::current()?->nicename,
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'copy'        => 1,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'This field is used as part of the profile page URL' ),
									'attributes'  => [
										'placeholder' => I18n::__( 'Username' ),
									],
									'conditions'  => [],
								],
								[
									'type'        => 'text',
									'label'       => I18n::__( 'First name' ),
									'name'        => 'firstname',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => '',
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => '',
									'attributes'  => [
										'placeholder' => I18n::__( 'e.g. John' ),
										'@input'      => 'display = `${firstname} ${lastname}`',
									],
									'conditions'  => [],
								],
								[
									'type'        => 'text',
									'label'       => I18n::__( 'Last name' ),
									'name'        => 'lastname',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => '',
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => '',
									'attributes' => [
										'placeholder' => I18n::__( 'e.g. Doe' ),
										'@input'      => 'display = `${firstname} ${lastname}`',
									],
									'conditions'  => [],
								],
								[
									'type'        => 'text',
									'label'       => I18n::__( 'Display name publicly as' ),
									'name'        => 'display',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'before'      => '<i class="ph ph-identification-badge"></i>',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Your name may appear around website where you contribute or are mentioned' ),
									'attributes'  => [
										'placeholder' => I18n::__( 'Display name' ),
									],
									'conditions'  => [],
								],
							],
						],
						[
							'type'    => 'group',
							'label'   => I18n::__( 'About yourself' ),
							'name'    => 'about-yourself',
							'columns' => 1,
							'fields'  => [
								[
									'type'        => 'textarea',
									'label'       => I18n::__( 'Biographical info' ),
									'name'        => 'bio',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Share a little biographical information to fill out your profile. This may be shown publicly.' ),
									'attributes'  => [],
									'conditions'  => [],
								],
							],
						],
						[
							'type'    => 'group',
							'label'   => I18n::__( 'About yourself' ),
							'name'    => 'about-yourself',
							'columns' => 1,
							'fields'  => [
								[
									'type'        => 'textarea',
									'label'       => I18n::__( 'Biographical info' ),
									'name'        => 'bio',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Share a little biographical information to fill out your profile. This may be shown publicly.' ),
									'attributes'  => [],
									'conditions'  => [],
								],
							],
						],
						[
							'type'    => 'group',
							'label'   => I18n::__( 'About yourself' ),
							'name'    => 'about-yourself',
							'columns' => 1,
							'fields'  => [
								[
									'type'        => 'textarea',
									'label'       => I18n::__( 'Biographical info' ),
									'name'        => 'bio',
									'value'       => '',
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'copy'        => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Share a little biographical information to fill out your profile. This may be shown publicly.' ),
									'attributes'  => [],
									'conditions'  => [],
								],
							],
						],
					],
				],
				[
					'name'          => 'appearance',
					'type'          => 'tab',
					'label'         => I18n::__( 'Appearance' ),
					'description'   => '',
					'icon'          => 'ph ph-paint-brush-broad',
					'class_button'  => '',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'    => 'theme',
							'type'    => 'group',
							'label'   => I18n::__( 'Theme preferences' ),
							'columns' => 1,
							'fields'  => [
								[
									'name'        => 'format',
									'type'        => 'radio',
									'variation'   => 'image',
									'instruction' => I18n::__( 'Choose how dashboard looks to you. Select a single theme, or sync with your system and automatically switch between day and night themes.' ),
									'value'       => 'light',
									'width'       => 180,
									'options'     => [
										'light' => [
											'image' => Url::site( 'dashboard/assets/images/dashboard-light.svg' ),
											'title' => I18n::__( 'Light mode' ),
										],
										'dark' => [
											'image' => Url::site( 'dashboard/assets/images/dashboard-dark.svg' ),
											'title' => I18n::__( 'Dark mode' ),
										],
										'system' => [
											'image' => Url::site( 'dashboard/assets/images/dashboard-system.svg' ),
											'title' => I18n::__( 'System' ),
										],
									],
								],
							],
						],
						[
							'name'   => 'theme',
							'type'   => 'group',
							'label'  => I18n::__( 'Toolbar' ),
							'fields' => [
								[
									'type'        => 'toggle',
									'label'       => I18n::__( 'Show when viewing site' ),
									'name'        => 'toolbar',
									'value'       => true,
									'placeholder' => '',
									'class'       => '',
									'reset'       => 0,
									'required'    => 0,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'these settings can be changed for each user separately' ),
									'attributes'  => [],
									'conditions'  => [],
									'options'     => [
										'yes' => I18n::__( 'Yes' ),
										'no'  => I18n::__( 'No' ),
									],
								],
							],
						],
						[
							'name'   => 'language',
							'type'   => 'group',
							'label'  => I18n::__( 'Language' ),
							'fields' => [
								[
									'name'        => 'language',
									'type'        => 'select',
									'instruction' => I18n::__( 'Language for your dashboard panel' ),
									'value'       => 'us',
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
							],
						],
					],
				],
				[
					'name'          => 'password',
					'type'          => 'tab',
					'label'         => I18n::__( 'Security' ),
					'caption'       => '',
					'icon'          => 'ph ph-password',
					'class_button'  => '',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'   => 'theme',
							'type'   => 'group',
							'label'  => I18n::__( 'Web sessions' ),
							'fields' => [
								[
									'name'     => 'title',
									'type'     => 'custom',
									'callback' => function () {
										ob_start();
										?>
										<div class="dg g-2 ga-4">
											<div>This is a list of devices that have logged into your account. Revoke any sessions that you do not recognize.</div>
											<div class="p-4 df fdr g-4 card card-border">
												<div class="avatar">
													<i class="badge"></i>
													<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
														<path d="M224 74h-18V64a22 22 0 0 0-22-22H40a22 22 0 0 0-22 22v96a22 22 0 0 0 22 22h114v10a22 22 0 0 0 22 22h48a22 22 0 0 0 22-22V96a22 22 0 0 0-22-22ZM40 170a10 10 0 0 1-10-10V64a10 10 0 0 1 10-10h144a10 10 0 0 1 10 10v10h-18a22 22 0 0 0-22 22v74Zm194 22a10 10 0 0 1-10 10h-48a10 10 0 0 1-10-10V96a10 10 0 0 1 10-10h48a10 10 0 0 1 10 10Zm-100 16a6 6 0 0 1-6 6H88a6 6 0 0 1 0-12h40a6 6 0 0 1 6 6Zm80-96a6 6 0 0 1-6 6h-16a6 6 0 0 1 0-12h16a6 6 0 0 1 6 6Z"/>
													</svg>
												</div>
												<div class="dg g-1">
													<h6 class="fs-15">Turkey, Antalya 46.197.118.72</h6>
													<code class="fs-12">Microsoft Edge on Windows</code>
													<div class="fs-12 t-muted lh-xs">Your current session</div>
												</div>
												<div class="ml-auto">
													<button class="btn btn--outline" type="button">Delete</button>
												</div>
											</div>
											<div class="p-4 df fdr g-4 card card-border">
												<div class="avatar">
													<i class="badge badge--green"></i>
													<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
														<path d="M176 18H80a22 22 0 0 0-22 22v176a22 22 0 0 0 22 22h96a22 22 0 0 0 22-22V40a22 22 0 0 0-22-22Zm10 198a10 10 0 0 1-10 10H80a10 10 0 0 1-10-10V40a10 10 0 0 1 10-10h96a10 10 0 0 1 10 10ZM138 60a10 10 0 1 1-10-10 10 10 0 0 1 10 10Z"/>
													</svg>
												</div>
												<div class="dg g-1">
													<h6 class="fs-15">Germany, Berlin 26.144.105.72</h6>
													<code class="fs-12">Chromium on Linux</code>
													<div class="fs-12 t-muted lh-xs">Your current session</div>
												</div>
												<div class="ml-auto">
													<button class="btn btn--outline" type="button">Delete</button>
												</div>
											</div>
										</div>
										<?php
										return ob_get_clean();
									},
								],
							],
						],
						[
							'name'   => 'theme',
							'type'   => 'group',
							'label'  => I18n::__( 'Change password' ),
							'fields' => [
								[
									'type'        => 'password',
									'label'       => I18n::__( 'New password' ),
									'name'        => 'password-new',
									'value'       => '',
									'placeholder' => I18n::__( 'New password' ),
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => I18n::__( 'Make sure it\'s at least 15 characters OR at least 12 characters including a number and a lowercase letter.' ),
									'attributes'  => [],
									'conditions'  => [],
									'switcher'    => 1,
									'generator'   => 1,
									'indicator'   => 0,
									'characters' => [
										'lowercase' => 2,
										'uppercase' => 2,
										'special'   => 2,
										'length'    => 12,
										'digit'     => 2,
									],
								],
								[
									'type'        => 'password',
									'label'       => I18n::__( 'Old password' ),
									'name'        => 'password-old',
									'value'       => '',
									'placeholder' => I18n::__( 'Old password' ),
									'class'       => '',
									'reset'       => 0,
									'required'    => 1,
									'before'      => '',
									'after'       => '',
									'tooltip'     => '',
									'instruction' => '',
									'attributes'  => [],
									'conditions'  => [],
									'switcher'    => 1,
									'generator'   => 0,
									'indicator'   => 0,
									'characters' => [],
								],
								[
									'name'     => 'password-save',
									'type'     => 'custom',
									'callback' => function () {
										ob_start();
										?>
										<button class="btn btn--primary" type="button" @click="$ajax('user/password-update', $data)" :disabled="!(passwordnew && passwordold)" disabled><?php I18n::t( 'Update password' ); ?></button>
										<?php
										return ob_get_clean();
									},
								],
							],
						],
					],
				],
				[
					'name'          => 'applications',
					'type'          => 'tab',
					'label'         => I18n::__( 'API keys' ),
					'description'   => '',
					'icon'          => 'ph ph-key',
					'class_button'  => '',
					'class_content' => 'p-7',
					'fields'        => [
						[
							'name'   => 'theme',
							'type'   => 'group',
							'label'  => I18n::__( 'Authentication keys' ),
							'fields' => [
								[
									'name'     => 'title',
									'type'     => 'custom',
									'callback' => function () {
										ob_start();
										?>
										<div class="dg g-2 ga-4">
											<p><?php I18n::t( 'Application passwords allow authentication via non-interactive systems, such as REST API, without providing your actual password. Application passwords can be easily revoked. They cannot be used for traditional logins to your website.' ); ?></p>
											<div class="p-4 df fdr g-4 card card-border">
												<svg xmlns="http://www.w3.org/2000/svg" width="36" height="36" viewBox="0 0 256 256">
													<path d="M160 18a78 78 0 0 0-73.8 103.3l-58.4 58.5A6 6 0 0 0 26 184v40a6 6 0 0 0 6 6h40a6 6 0 0 0 6-6v-18h18a6 6 0 0 0 6-6v-18h18a6 6 0 0 0 4.2-1.8l10.5-10.4A78 78 0 1 0 160 18Zm0 144a65.6 65.6 0 0 1-24.4-4.7 6 6 0 0 0-6.7 1.3L117.5 170H96a6 6 0 0 0-6 6v18H72a6 6 0 0 0-6 6v18H38v-31.5L97.4 127a6 6 0 0 0 1.3-6.7A66 66 0 1 1 160 162Zm30-86a10 10 0 1 1-10-10 10 10 0 0 1 10 10Z"/>
												</svg>
												<div class="dg g-1">
													<h6 class="fs-15">Amplication</h6>
													<code class="fs-12 bg-green-lt t-green"><span class="badge badge--sm badge--green-lt">Active</span> SHA256:Ai2xqyVBORX9PJJigJxfrdzXfKPajJHZMYw3+dOo+nw</code>
													<div class="fs-12 t-muted lh-xs">Added on Nov 15, 2022</div>
												</div>
												<div class="ml-auto">
													<button class="btn btn--outline" type="button">Delete</button>
												</div>
											</div>
										</div>
										<?php
										return ob_get_clean();
									},
								],
							],
						],
					],
				],
			]
		);
	}
);
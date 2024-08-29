<?php
use Grafema\I18n;
use Grafema\View;

/**
 * Register form for first Grafema installation.
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'system-install',
	[
		'class'           => 'dg g-6',
		'@submit.prevent' => '$ajax("system/install").then(response => installed = response)',
		'x-data'          => '{approved: {}, site: {}, db: {}, user: {}, installed: false}',
		'x-init'          => '$watch("installed", () => $wizard.goNext())'
	],
	[
		[
			'type'       => 'step',
			'attributes' => [
				'class'         => 'dg g-7 pt-8 px-8',
				'x-wizard:step' => 'site.name?.trim()',
			],
			'fields'     => [
				[
					'type'        => 'header',
					'label'       => I18n::_t( 'Welcome to Grafema!' ),
					'name'        => 'title',
					'class'       => 't-center',
					'instruction' => I18n::_t( 'This is installation wizard. Before start, you need to set some settings. Please fill the information about your website.' ),
				],
				[
					'name'  => 'website-data',
					'type'  => 'divider',
					'label' => I18n::_t( 'Website data' ),
				],
				[
					'name'       => 'site[name]',
					'type'       => 'text',
					'label'      => I18n::_t( 'Site name' ),
					'class'      => '',
					'attributes' => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Example: My Blog' ),
						'x-autocomplete' => '',
					],
				],
				[
					'name'        => 'site[tagline]',
					'type'        => 'text',
					'label'       => I18n::_t( 'Site tagline' ),
					'instruction' => I18n::_t( 'Don\'t worry, you can always change these settings later' ),
					'class'       => '',
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Example: Just another Grafema site' ),
						'x-autocomplete' => '',
					],
				],
			],
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'           => 'dg g-7 pt-8 px-8',
				'x-cloak'         => true,
				'x-wizard:step'   => '[db.database, db.username, db.password, db.host, db.prefix].every(value => value !== undefined && value.trim())',
				'x-wizard:action' => 'approved = {}',
			],
			'fields'     => [
				[
					'name'        => 'title',
					'type'        => 'header',
					'label'       => I18n::_t( 'Step 1: Database' ),
					'instruction' => I18n::_t( 'Information about connecting to the database. If you are not sure about it, contact your hosting provider.' ),
				],
				[
					'name'  => 'credits',
					'type'  => 'divider',
					'label' => I18n::_t( 'Database credits' ),
				],
				[
					'name'        => 'db[database]',
					'type'        => 'text',
					'label'       => I18n::_t( 'Database name' ),
					'instruction' => I18n::_t( 'Specify the name of the empty database' ),
					'class'       => '',
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'database_name' ),
						'x-autocomplete' => '',
					],
				],
				[
					'name'        => 'db[username]',
					'type'        => 'text',
					'label'       => I18n::_t( 'MySQL database user name' ),
					'instruction' => I18n::_t( 'User of the all privileges in the database' ),
					'class'       => '',
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'user_name' ),
						'x-autocomplete' => '',
					],
				],
				[
					'name'        => 'db[password]',
					'type'        => 'text',
					'label'       => I18n::_t( 'MySQL password' ),
					'instruction' => I18n::_t( 'Password for the specified user' ),
					'class'       => '',
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Password' ),
						'x-autocomplete' => '',
					],
				],
				[
					'type'    => 'group',
					'columns' => 2,
					'class'   => '',
					'fields'  => [
						[
							'name'       => 'db[host]',
							'type'       => 'text',
							'label'      => I18n::_t( 'Hostname' ),
							'attributes' => [
								'value'      => 'localhost',
								'required' => true,
								'placeholder' => I18n::_t( 'Hostname' ),
								'x-autocomplete' => '',
							],
						],
						[
							'name'       => 'db[prefix]',
							'type'       => 'text',
							'label'      => I18n::_t( 'Prefix' ),
							'attributes' => [
								'value'      => 'grafema_',
								'required' => true,
								'placeholder' => I18n::_t( 'Prefix' ),
								'x-autocomplete' => '',
							],
						],
					],
				],
			],
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'           => 'dg g-7 pt-8 px-8',
				'x-wizard:step'   => 'Object.values(approved).every(Boolean) === true',
				'x-wizard:action' => '$ajax("system/test", db).then(response => approved = response)',
				'x-cloak'         => true,
			],
			'fields'     => [
				[
					'name'        => 'title',
					'type'        => 'header',
					'label'       => I18n::_t( 'Step 2: System check' ),
					'instruction' => I18n::_t( 'This is an important step that will help make sure that your server is ready for installation and properly configured.' ),
				],
				[
					'name'  => 'website-data',
					'type'  => 'divider',
					'label' => I18n::_t( 'System check' ),
				],
				[
					'name'     => 'date-format',
					'type'     => 'custom',
					'callback' => function() {
						$checks = [
							'connection' => I18n::_t( 'Testing the database connection' ),
							'pdo'        => I18n::_t( 'PDO PHP Extension' ),
							'curl'       => I18n::_t( 'cURL PHP Extension' ),
							'mbstring'   => I18n::_t( 'Mbstring PHP Extension' ),
							'gd'         => I18n::_t( 'GD PHP Extension' ),
							'memory'     => I18n::_t( '128MB or more allocated memory' ),
							'php'        => I18n::_t( 'PHP version 8.1 or higher' ),
							'mysql'      => I18n::_t( 'MySQL version 5.6 or higher' ),
						];
						?>
						<ul class="dg g-1">
							<?php
							foreach ( $checks as $icon => $title ) :
								?>
								<li class="df aic">
													<span class="badge badge--xl badge--round badge--icon" :class="approved.<?php echo $icon; ?> === undefined ? 'badge--load' : (approved.<?php echo $icon; ?> ? 't-green' : 't-red')">
														<i class="ph" :class="approved.<?php echo $icon; ?> ? 'ph-check' : 'ph-x'"></i>
													</span>
									<span class="ml-4"><?php echo $title; ?></span>
								</li>
							<?php endforeach; ?>
						</ul>
						<?php
					},
				],
			],
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'         => 'dg g-7 pt-8 px-8',
				'x-cloak'       => true,
				'x-wizard:step' => '[user.login, user.email, user.password].every(value => value !== undefined && value.trim())',
			],
			'fields'     => [
				[
					'name'        => 'title',
					'type'        => 'header',
					'label'       => I18n::_t( 'Step 3: Create account' ),
					'instruction' => I18n::_t( 'Almost everything is ready! The last step: add website owner information.' ),
				],
				[
					'name'  => 'user-credits',
					'type'  => 'divider',
					'label' => I18n::_t( 'Owner credits' ),
				],
				[
					'name'        => 'user[login]',
					'type'        => 'text',
					'label'       => I18n::_t( 'User login' ),
					'instruction' => I18n::_t( 'Can use only alphanumeric characters, underscores, hyphens and @ symbol' ),
					'class'       => '',
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Enter login' ),
						'x-autocomplete' => '',
					],
				],
				[
					'name'        => 'user[email]',
					'type'        => 'email',
					'label'       => I18n::_t( 'Email address' ),
					'instruction' => I18n::_t( 'Double-check your email address before continuing' ),
					'class'       => '',
					'attributes'  => [
						'placeholder'    => I18n::_t( 'Enter email' ),
						'x-autocomplete' => '',
						'required'       => true,
					],
				],
				[
					'type'        => 'password',
					'label'       => I18n::_t( 'Password' ),
					'name'        => 'user[password]',
					'value'       => '',
					'placeholder' => I18n::_t( 'Password' ),
					'class'       => '',
					'required'    => 1,
					'tooltip'     => '',
					'instruction' => I18n::_t( 'You will need this password to sign-in. Please store it in a secure location' ),
					'attributes'  => [
						'required'       => true,
						'placeholder'    => I18n::_t( 'Password' ),
						'x-autocomplete' => '',
					],
					'conditions' => [],
					'switcher'   => 1,
					'generator'  => 1,
					'indicator'  => 0,
					'characters' => [
						'lowercase' => 2,
						'uppercase' => 2,
						'special'   => 2,
						'length'    => 12,
						'digit'     => 2,
					],
				]
			],
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'   => 'dg g-7 pt-8 px-8',
				'x-cloak' => true,
			],
			'fields'     => [
				[
					'type'     => 'custom',
					'callback' => View::get(
						GRFM_PATH . 'dashboard/templates/states/completed',
						[
							'title'       => I18n::_t( 'Woo-hoo, Grafema has been successfully installed!' ),
							'description' => I18n::_t( 'We hope the installation process was easy. Thank you, and enjoy.' ),
						]
					),
				],
			],
		],
		[
			'type'     => 'custom',
			'callback' => function() {
				ob_start();
				?>
				<!-- buttons -->
				<div class="p-8 df jcsb g-2">
					<button type="button" class="btn btn--outline" x-show="$wizard.isNotLast()" :disabled="$wizard.cannotGoBack()" @click="$wizard.goBack()" disabled><?php I18n::t( 'Back' ); ?></button>
					<button type="button" class="btn btn--primary" x-show="$wizard.isNotLast() && !$wizard.isStep(3)" :disabled="$wizard.cannotGoNext()" @click="$wizard.goNext()" disabled><?php I18n::t( 'Continue' ); ?></button>
					<button type="submit" class="btn btn--primary" x-show="$wizard.isStep(3)" :disabled="![user.login, user.email, user.password].every(value => value.trim())" x-cloak disabled><?php I18n::t( 'Install Grafema' ); ?></button>
					<a href="/dashboard/" class="btn btn--primary btn--full" x-show="$wizard.isLast()" x-cloak><?php I18n::t( 'Go to dashboard' ); ?></a>
				</div>
				<?php
				return ob_get_clean();
			},
		],
	]
);
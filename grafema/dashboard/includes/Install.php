<?php
use Grafema\Hook;
use Grafema\Route;
use Grafema\I18n;
use Grafema\Asset;
use Grafema\View;
use Grafema\Url;
use Grafema\Html;
use Grafema\Api;
use Grafema\Patterns\Singleton;

/**
 *
 *
 * @package Grafema
 */
final class Install extends Grafema\App\App {

	use Singleton;

	/**
	 * Class constructor
	 *
	 * @return void|bool
	 * @since 1.0.0
	 */
	public function __construct() {

		/**
		 * Define declare the necessary constants
		 *
		 * @since 1.0.0
		 */
		$this->define( 'GRFM_IS_INSTALL', true );

		/**
		 * Override response
		 *
		 * @since 1.0.0
		 */
		Hook::add(
			'grafema_api_response',
			function ( $data, $slug ) {
				return match($slug) {
					'system/tests' => [
						[
							'fragment' => $data,
							'target'   => 'body',
							'method'   => 'alpine',
						],
					],
					default => $data,
				};
			},
			10,
			3
		);

		/**
		 * Add core API endpoints.
		 * Important! If current request is request to API, stop code execution after Api::create().
		 *
		 * @since 1.0.0
		 */
		Api::create( sprintf( '%sdashboard/api', GRFM_PATH ), '/api' );

		/**
		 * Register new form for install wizard
		 *
		 * @since 1.0.0
		 */
		$this->form( 'system-install' );

		/**
		 * Register new routes
		 *
		 * @since 1.0.0
		 */
		$this->route();
	}

	private function route(): void {
		$route = new Route();
		$route->get(
			'(.*)',
			function( $slug ) use ( $route ) {
				http_response_code( 200 );

				/**
				 * Redirect to installer wizard if Grafema is not installed.
				 *
				 * @since 1.0.0
				 */
				if ( $slug !== 'install' ) {
					View::redirect( Url::site( 'install' ) );
					exit;
				}

				/**
				 * Run the installer wizard.
				 *
				 * @since 1.0.0
				 */
				$styles = [ 'main', 'phosphor' ];
				foreach ( $styles as $style ) {
					Asset::enqueue( $style, Url::site( 'dashboard/assets/css/' . $style . '.css' ) );
				}

				$scripts = [ 'index', 'ajax', 'alpine.min' ];
				foreach ( $scripts as $script ) {
					$data = [];
					if ( $script === 'index' ) {
						$data = [
							'data' => [
								'apiurl' => 'https://cms.codyshop.ru/api/',
							],
						];
					}
					Asset::enqueue( $script, Url::site( 'dashboard/assets/js/' . $script . '.js' ), $data );
				}

				/**
				 * Include assets before calling hooks, but after they are registered.
				 *
				 * @since 1.0.0
				 */
				Hook::add( 'grafema_dashboard_header', fn () => Asset::plug( '*.css' ) );
				Hook::add( 'grafema_dashboard_footer', fn () => Asset::plug( '*.js' ) );

				/**
				 * The administrative panel also has a single entry point.
				 *
				 * @since 1.0.0
				 */
				ob_start();
				View::output( GRFM_PATH . 'dashboard/install.php' );
				echo ( new Html() )->beautify( ob_get_clean() );

				die();
			}
		);

		$route->run( fn() => die() );
	}

	/**
	 * Register form for first Grafema installation
	 *
	 * @param string $name
	 * @return void
	 */
	private function form( string $name ): void {
		Form::register(
			$name,
			[
				'class'           => 'card card-border',
				'@submit.prevent' => '$ajax("system/install").then(response => installed = response)',
				'x-data'          => '{approved: {}, site: {}, db: {}, user: {}, installed: false}',
                'x-init'          => '$watch("installed", () => $wizard.goNext())'
			],
			function( $form ) {
				$form->addFields(
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
									'label'       => I18n::__( 'Welcome to Grafema!' ),
									'name'        => 'title',
									'class'       => 't-center',
									'instruction' => I18n::__( 'This is installation wizard. Before start, you need to set some settings. Please fill the information about your website.' ),
								],
								[
									'name'  => 'website-data',
									'type'  => 'divider',
									'label' => I18n::__( 'Website data' ),
								],
								[
									'name'       => 'site[name]',
									'type'       => 'text',
									'label'      => I18n::__( 'Site name' ),
									'class'      => '',
									'attributes' => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Example: My Blog' ),
										'x-autocomplete' => '',
									],
								],
								[
									'name'        => 'site[tagline]',
									'type'        => 'text',
									'label'       => I18n::__( 'Site tagline' ),
									'instruction' => I18n::__( 'Don\'t worry, you can always change these settings later' ),
									'class'       => '',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Example: Just another Grafema site' ),
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
									'label'       => I18n::__( 'Step 1: Database' ),
									'instruction' => I18n::__( 'Information about connecting to the database. If you are not sure about it, contact your hosting provider.' ),
								],
								[
									'name'  => 'credits',
									'type'  => 'divider',
									'label' => I18n::__( 'Database credits' ),
								],
								[
									'name'        => 'db[database]',
									'type'        => 'text',
									'label'       => I18n::__( 'Database name' ),
									'instruction' => I18n::__( 'Specify the name of the empty database' ),
									'class'       => '',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'database_name' ),
										'x-autocomplete' => '',
									],
								],
								[
									'name'        => 'db[username]',
									'type'        => 'text',
									'label'       => I18n::__( 'MySQL database user name' ),
									'instruction' => I18n::__( 'User of the all privileges in the database' ),
									'class'       => '',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'user_name' ),
										'x-autocomplete' => '',
									],
								],
								[
									'name'        => 'db[password]',
									'type'        => 'text',
									'label'       => I18n::__( 'MySQL password' ),
									'instruction' => I18n::__( 'Password for the specified user' ),
									'class'       => '',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Password' ),
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
											'label'      => I18n::__( 'Hostname' ),
											'attributes' => [
												'value'      => 'localhost',
												'required' => true,
												'placeholder' => I18n::__( 'Hostname' ),
												'x-autocomplete' => '',
											],
										],
										[
											'name'       => 'db[prefix]',
											'type'       => 'text',
											'label'      => I18n::__( 'Prefix' ),
											'attributes' => [
												'value'      => 'grafema_',
												'required' => true,
												'placeholder' => I18n::__( 'Prefix' ),
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
									'label'       => I18n::__( 'Step 2: System check' ),
									'instruction' => I18n::__( 'This is an important step that will help make sure that your server is ready for installation and properly configured.' ),
								],
								[
									'name'  => 'website-data',
									'type'  => 'divider',
									'label' => I18n::__( 'System check' ),
								],
								[
									'name'     => 'date-format',
									'type'     => 'custom',
									'callback' => function() {
										$checks = [
											'connection' => I18n::__( 'Testing the database connection' ),
											'pdo'        => I18n::__( 'PDO PHP Extension' ),
											'curl'       => I18n::__( 'cURL PHP Extension' ),
											'mbstring'   => I18n::__( 'Mbstring PHP Extension' ),
											'gd'         => I18n::__( 'GD PHP Extension' ),
											'memory'     => I18n::__( '128MB or more allocated memory' ),
											'php'        => I18n::__( 'PHP version 8.1 or higher' ),
											'mysql'      => I18n::__( 'MySQL version 5.6 or higher' ),
										];
										?>
										<ul class="dg g-1">
											<?php
											foreach ( $checks as $icon => $title ) :
												?>
												<li class="df aic">
													<span class="badge badge--xl badge--round badge--icon" :class="approved.<?php echo $icon; ?> === undefined ? 'badge--load' : (approved.<?php echo $icon; ?> ? 't-herbal' : 't-reddish')">
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
									'label'       => I18n::__( 'Step 3: Create account' ),
									'instruction' => I18n::__( 'Almost everything is ready! The last step: add website owner information.' ),
								],
								[
									'name'  => 'user-credits',
									'type'  => 'divider',
									'label' => I18n::__( 'Owner credits' ),
								],
								[
									'name'        => 'user[login]',
									'type'        => 'text',
									'label'       => I18n::__( 'User login' ),
									'instruction' => I18n::__( 'Can use only alphanumeric characters, underscores, hyphens and @ symbol' ),
									'class'       => '',
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Enter login' ),
										'x-autocomplete' => '',
									],
								],
								[
									'name'        => 'user[email]',
									'type'        => 'email',
									'label'       => I18n::__( 'Email address' ),
									'instruction' => I18n::__( 'Double-check your email address before continuing' ),
									'class'       => '',
									'attributes'  => [
										'placeholder'    => I18n::__( 'Enter email' ),
										'x-autocomplete' => '',
										'required'       => true,
									],
								],
                                [
									'type'        => 'password',
									'label'       => I18n::__( 'Password' ),
									'name'        => 'user[password]',
									'value'       => '',
									'placeholder' => I18n::__( 'Password' ),
									'class'       => '',
									'required'    => 1,
									'tooltip'     => '',
									'instruction' => I18n::__( 'You will need this password to sign-in. Please store it in a secure location' ),
									'attributes'  => [
										'required'       => true,
										'placeholder'    => I18n::__( 'Password' ),
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
									'callback' => fn() => View::include(
										GRFM_PATH . 'dashboard/templates/states/completed.php',
										[
											'title'       => I18n::__( 'Woo-hoo, Grafema has been successfully installed!' ),
											'description' => I18n::__( 'We hope the installation process was easy. Thank you, and enjoy.' ),
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
									<button type="button" class="btn btn--outline" x-show="$wizard.isNotLast()" :disabled="$wizard.cannotGoBack()" @click="$wizard.goBack()" disabled><?php I18n::e( 'Back' ); ?></button>
									<button type="button" class="btn btn--primary" x-show="$wizard.isNotLast() && !$wizard.isStep(3)" :disabled="$wizard.cannotGoNext()" @click="$wizard.goNext()" disabled><?php I18n::e( 'Continue' ); ?></button>
									<button type="submit" class="btn btn--primary" x-show="$wizard.isStep(3)" :disabled="![user.login, user.email, user.password].every(value => value.trim())" x-cloak disabled><?php I18n::e( 'Install Grafema' ); ?></button>
                                    <a href="/dashboard/" class="btn btn--primary btn--full" x-show="$wizard.isLast()" x-cloak><?php I18n::e( 'Go to dashboard' ); ?></a>
								</div>
								<?php
								return ob_get_clean();
							},
						],
					]
				);
			}
		);
	}
}

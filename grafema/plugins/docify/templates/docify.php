<?php
use Grafema\I18n;

/**
 * Documentation creator.
 *
 * This template can be overridden by copying it to themes/yourtheme/toolkit/templates/docify.php
 *
 * @since 2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

/**
 * Get all uploaded plugins.
 *
 * @since 2025.1
 */
$list    = [];
$plugins = Plugins\Manager::init(
	function () {
		$paths = ( new Dir\Dir( GRFM_PLUGINS ) )->getFiles( '*.php', 1 );
		if ( ! $paths ) {
			return null;
		}
	}
);
if ( $plugins->plugins ) {
	foreach ( $plugins->plugins as $class => $path ) {
		require_once $path;

		$plugin = new $class();
		if ( $plugin instanceof Plugins\Skeleton ) {
			$list[$path] = array_combine(
				['content', 'description'],
				( new Grafema\Sanitizer() )->apply(
					$plugin->manifest(),
					[
						'name'        => 'html',
						'description' => 'html',
					]
				)
			);
		}
	}
}

Dashboard\Form::enqueue(
	'import/documents',
	[
		'class'           => 'card card-border',
		'@submit.prevent' => "\$ajax('import/documents').then(response => {completed = response})",
		'x-data'          => '{project:"",completed:""}',
	],
	[
		[
			'name'     => 'title',
			'type'     => 'custom',
			'callback' => function () {
				ob_start();
				?>
				<div class="progress" :style="'--grafema-progress:' + $wizard.progress().progress"></div>
				<div class="p-8 pt-7 pb-7 df aic jcsb">
					<span x-text="$wizard.current().title"><?php I18n::t( 'Choose project' ); ?></span>
					<span class="t-muted">
								step <strong x-text="$wizard.progress().current">1</strong> from <strong x-text="$wizard.progress().total">2</strong>
							</span>
				</div>
				<div class="card-hr"></div>
				<?php
				return ob_get_clean();
			},
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'          => 'pl-8 pr-8',
				'x-wizard:title' => I18n::_t( 'Choose project' ),
			],
			'fields' => [
				[
					'name'        => 'title',
					'type'        => 'header',
					'class'       => 'p-8 t-center',
					'label'       => I18n::_t( 'Select the project you want to export to docs' ),
					'instruction' => I18n::_t( 'This tool allows you to convert docblock comments into docs pages. You can also use markdown.' ),
				],
				[
					'type'        => 'select',
					'label'       => I18n::_t( 'Select a project to document' ),
					'name'        => 'project',
					'value'       => 'none',
					'placeholder' => '',
					'class'       => '',
					'reset'       => 0,
					'required'    => 0,
					'before'      => '',
					'after'       => '',
					'tooltip'     => '',
					'instruction' => '',
					'attributes'  => [],
					'conditions' => [],
					'options'    => [
						'optgroup' => [
							'label'   => I18n::_t( 'Plugins' ),
							'options' => [
								'none' => I18n::_t( 'Nothing is selected' ),
								...$list,
							],
						],
					],
				],
			],
		],
		[
			'type'       => 'step',
			'attributes' => [
				'class'          => 'pl-8 pr-8',
				'x-cloak'        => true,
				'x-wizard:title' => I18n::_t( 'Project import is completed' ),
			],
			'fields' => [
				[
					'type'     => 'custom',
					'callback' => fn () => '<div class="dg" x-html="completed"></div>',
				],
			],
		],
		[
			'type'     => 'custom',
			'callback' => function () {
				ob_start();
				?>
				<!-- buttons -->
				<div class="p-8 df jcfe g-2">
					<button type="submit" class="btn btn--primary" :disabled="project.trim() === 'none'" disabled><?php I18n::t( 'Run the importer' ); ?></button>
				</div>
				<?php
				return ob_get_clean();
			},
		],
	]
);
?>
<div class="grafema-main p-7 bg-gray-lt">
	<div class="mw-600 m-auto">
		<?php Dashboard\Form::print( 'import/documents' ); ?>
	</div>
</div>


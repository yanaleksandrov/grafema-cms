<?php
use Grafema\I18n;
use Grafema\Url;

/**
 * Form for build custom fields
 *
 * @since 2025.1
 */
Dashboard\Form::register(
	'grafema-posts-export',
	[
		'class'           => 'card card-border px-8 pb-8 g-8',
		'@submit.prevent' => '$ajax("posts/export").then(response => output = response.output)',
		'x-data'          => '{posts:[]}',
	],
	function ( $form ) {
		$form->addFields(
			[
				[
					'name'        => 'title',
					'type'        => 'header',
					'class'       => 'pt-8 px-8 t-center',
					'label'       => I18n::_t( 'Map fields for export' ),
					'instruction' => I18n::_t( 'Select fields from your CSV file that you want to map to fields in the posts, or that you want to ignore during import' ),
				],
				[
					'name'        => 'types[]',
					'type'        => 'select',
					'label'       => I18n::_t( 'Post types' ),
					'instruction' => I18n::_t( 'Choose which types of posts you want to export' ),
					'value'       => 'pages',
					'attributes'  => [
						'x-select' => '{"showSearch":1}',
						'multiple' => true,
					],
					'options' => [
						'pages' => I18n::_t( 'Pages' ),
						'media' => I18n::_t( 'Media' ),
					],
				],
				[
					'name'        => 'format',
					'type'        => 'radio',
					'variation'   => 'described', // can be: simple, image, described
					'label'       => I18n::_t( 'File format' ),
					'instruction' => I18n::_t( 'Select the appropriate format for subsequent work' ),
					'value'       => 'csv',
					'width'       => 180,
					'options'     => [
						'csv' => [
							'image'   => Url::site( 'dashboard/assets/images/dashboard-light.svg' ),
							'title'   => I18n::_t( 'CSV file' ),
							'content' => I18n::_t( '25 GB of Storage' ),
							'hidden'  => I18n::_t( 'Hello world!' ),
						],
						'json' => [
							'image'   => Url::site( 'dashboard/assets/images/dashboard-dark.svg' ),
							'title'   => I18n::_t( 'JSON file' ),
							'content' => I18n::_t( '35 GB of Storage' ),
							'hidden'  => I18n::_t( 'This is hidden content!' ),
						],
					],
				],
				[
					'type'     => 'custom',
					'callback' => function () {
						ob_start();
						?>
                        <button type="submit" class="btn btn--primary btn--lg"><?php I18n::t( 'Export posts' ); ?></button>
						<?php
						return ob_get_clean();
					},
				],
			]
		);
	}
);
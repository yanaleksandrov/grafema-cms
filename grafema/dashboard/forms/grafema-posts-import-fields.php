<?php
use Grafema\I18n;
use Grafema\Post\Status;
use Grafema\Post\Type;
use Grafema\Sanitizer;

$samples  = Sanitizer::array( $args['samples'] ?? [] );
$filepath = Sanitizer::attribute( $args['filepath'] ?? '' );
if ( empty( $samples ) ) {
    return;
}

Dashboard\Form::register(
	'posts-import-fields',
	function: function ( $form ) use ( $filepath, $samples ) {
		$form->addFields(
			[
				[
					'type'    => 'group',
					'label'  => I18n::__( 'Required Data' ),
					'name'    => 'data',
					'class'   => '',
					'columns' => 1,
					'fields' => [
						[
							'type'        => 'select',
							'label'       => I18n::__( 'Post type' ),
							'name'        => 'type',
							'value'       => '',
							'placeholder' => '',
							'class'       => '',
							'reset'       => 0,
							'required'    => 0,
							'copy'        => 0,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => I18n::_s( 'Sample: <samp>%s</samp>', 'pages' ),
							'attributes'  => [],
							'conditions'  => [],
							'options'     => Type::get(),
						],
						[
							'type'        => 'select',
							'label'       => I18n::__( 'Post status' ),
							'name'        => 'status',
							'value'       => '',
							'placeholder' => '',
							'class'       => '',
							'reset'       => 0,
							'required'    => 0,
							'copy'        => 0,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => I18n::__( 'Set default post status, if not specified' ),
							'attributes'  => [],
							'conditions'  => [],
							'options'     => Status::get(),
						],
						[
							'type'        => 'select',
							'label'       => I18n::__( 'Post author' ),
							'name'        => 'author',
							'value'       => '',
							'placeholder' => '',
							'class'       => '',
							'reset'       => 0,
							'required'    => 0,
							'copy'        => 0,
							'before'      => '',
							'after'       => '',
							'tooltip'     => '',
							'instruction' => I18n::__( 'Set post author, if not specified' ),
							'attributes'  => [],
							'conditions'  => [],
							'options'     => [
								'1' => 'Yan Aleksandrov',
							],
						],
					],
				],
				[
					'type'    => 'group',
					'label'   => I18n::__( 'Map Data' ),
					'name'    => 'map-data',
					'class'   => '',
					'columns' => 1,
					'fields'  => array_map(fn($index, $sample) => [
						'type'        => 'select',
						'label'       => '',
						'name'        => 'map[' . $index . ']',
						'value'       => '',
						'placeholder' => '',
						'class'       => 'dg g-1 ga-2',
						'reset'       => 0,
						'required'    => 0,
						'copy'        => 0,
						'before'      => '',
						'after'       => '',
						'tooltip'     => '',
						'instruction' => I18n::_s( 'Sample: %s', '<samp>' . $sample . '</samp>' ),
						'attributes'  => [],
						'conditions'  => [],
						'options'     => [
							''         => I18n::__( 'No import' ),
							'optgroup' => [
								'label'   => I18n::__( 'Main fields' ),
								'options' => [
									'name'     => I18n::__( 'Post ID' ),
									'author'   => I18n::__( 'Author ID' ),
									'views'    => I18n::__( 'Views count' ),
									'type'     => I18n::__( 'Type' ),
									'title'    => I18n::__( 'Title' ),
									'content'  => I18n::__( 'Content' ),
									'created'  => I18n::__( 'Created at' ),
									'modified' => I18n::__( 'Modified at' ),
									'status'   => I18n::__( 'Status' ),
								],
							],
						],
					], array_keys($samples), $samples),
				],
				[
					'name'     => 'custom',
					'type'     => 'custom',
					'callback' => fn () => '<input type="hidden" value="' . $filepath . '" name="filename">',
				],
			]
        );
	}
);
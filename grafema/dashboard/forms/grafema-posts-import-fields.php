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

Dashboard\Form::enqueue(
	'posts-import-fields',
	fields: [
		[
			'type'    => 'group',
			'label'  => I18n::_t( 'Required Data' ),
			'name'    => 'data',
			'class'   => '',
			'columns' => 1,
			'fields' => [
				[
					'type'        => 'select',
					'name'        => 'type',
					'label'       => I18n::_t( 'Post type' ),
					'class'       => '',
					'label_class' => '',
					'reset'       => 0,
					'before'      => '',
					'after'       => '',
					'instruction' => I18n::_f( 'Sample: <samp>%s</samp>', 'pages' ),
					'tooltip'     => '',
					'copy'        => 0,
					'sanitizer'   => '',
					'validator'   => '',
					'conditions'  => [],
					'attributes'  => [],
					'options'     => Type::get(),
				],
				[
					'type'        => 'select',
					'name'        => 'status',
					'label'       => I18n::_t( 'Post status' ),
					'class'       => '',
					'label_class' => '',
					'reset'       => 0,
					'before'      => '',
					'after'       => '',
					'instruction' => I18n::_t( 'Set default post status, if not specified' ),
					'tooltip'     => '',
					'copy'        => 0,
					'sanitizer'   => '',
					'validator'   => '',
					'conditions'  => [],
					'attributes'  => [],
					'options'     => Status::get(),
				],
				[
					'type'        => 'select',
					'name'        => 'author',
					'label'       => I18n::_t( 'Post author' ),
					'class'       => '',
					'label_class' => '',
					'reset'       => 0,
					'before'      => '',
					'after'       => '',
					'instruction' => I18n::_t( 'Set post author, if not specified' ),
					'tooltip'     => '',
					'copy'        => 0,
					'sanitizer'   => '',
					'validator'   => '',
					'conditions'  => [],
					'attributes'  => [],
					'options'     => [
						'1' => 'Yan Aleksandrov',
					],
				],
			],
		],
		[
			'type'    => 'group',
			'label'   => I18n::_t( 'Map Data' ),
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
				'instruction' => I18n::_f( 'Sample: %s', '<samp>' . $sample . '</samp>' ),
				'attributes'  => [],
				'conditions'  => [],
				'options'     => [
					''         => I18n::_t( 'No import' ),
					'optgroup' => [
						'label'   => I18n::_t( 'Main fields' ),
						'options' => [
							'name'     => I18n::_t( 'Post ID' ),
							'author'   => I18n::_t( 'Author ID' ),
							'views'    => I18n::_t( 'Views count' ),
							'type'     => I18n::_t( 'Type' ),
							'title'    => I18n::_t( 'Title' ),
							'content'  => I18n::_t( 'Content' ),
							'created'  => I18n::_t( 'Created at' ),
							'modified' => I18n::_t( 'Modified at' ),
							'status'   => I18n::_t( 'Status' ),
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
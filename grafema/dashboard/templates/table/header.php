<?php
use Grafema\I18n;
use Grafema\View;
use Grafema\Sanitizer;

/**
 * Table header
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/table/header.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}

[ $title, $badge, $show, $content, $uploader, $filter, $actions, $search, $translation ] = ( new Sanitizer(
	$args ?? [],
	[
		'title'       => 'trim',
		'badge'       => 'trim',
		'show'        => 'bool:true',
		'content'     => 'trim',
		'uploader'    => 'bool:false',
		'filter'      => 'bool:false',
		'actions'     => 'bool:false',
		'search'      => 'bool:false',
		'translation' => 'bool:false',
	]
) )->values();
?>
<!-- table head start -->
<div class="table__header">
    <div class="mw df fww aic jcsb g-3 py-5 px-7 md:p-5">
		<?php if ( $title ) : ?>
            <h4><?php echo $title; ?>
	            <?php $badge && print( '<span class="badge">' . $badge . '</span>' ); ?>
            </h4>
		<?php endif; ?>
	    <?php if ( $filter || $show || $uploader || $actions || $translation ) : ?>
		    <div class="df aic g-1">
			    <?php if ( $filter ) : ?>
				    <div class="df aic g-1">
					    <button class="btn btn--sm btn--outline" type="reset" form="grafema-items-filter" @click="showFilter = !showFilter" :class="showFilter && 't-red'" :title="showFilter ? '<?php I18n::t_attr( 'Reset Filter' ); ?>' : '<?php I18n::t( 'Filter' ); ?>'">
						    <i class="ph ph-funnel" :class="showFilter ? 'ph-funnel-x' : 'ph-funnel'"></i>
						    <span x-text="showFilter ? '<?php I18n::t_attr( 'Reset' ); ?>' : '<?php I18n::t_attr( 'Filter' ); ?>'"><?php I18n::t( 'Filter' ); ?></span>
					    </button>
					    <?php
					    View::print(
						    'templates/form/number',
						    [
							    'type'        => 'number',
							    'uid'         => 'page',
							    'label'       => '',
							    'class'       => 'field field--sm field--outline',
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
								    'min'   => 0,
								    'value' => 3,
							    ],
						    ]
					    );
					    ?>
				    </div>
			    <?php endif; ?>
			    <?php if ( $search ) : ?>
				    <div class="df aic g-1">
					    <?php
					    View::print(
						    'templates/form/input',
						    [
							    'type'        => 'text',
							    'uid'         => 's',
							    'label'       => '',
							    'class'       => 'field field--sm field--outline',
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
								    'type'        => 'search',
								    'name'        => 's',
								    'placeholder' => I18n::_t( 'Search plugins' ),
							    ],
						    ]
					    );
					    ?>
				    </div>
			    <?php endif; ?>
			    <?php if ( $show ) : ?>
			        <div class="df aic g-1" x-show="!bulk">
						<?php
						View::print(
							'templates/form/details',
							[
								'label'       => '<i class="ph ph-dots-three-outline-vertical"></i>',
								'instruction' => I18n::_t( 'Test content' ),
								'class'       => 'btn btn--sm btn--outline btn--icon',
								'content'     => Dashboard\Form::get( 'grafema-posts-options', GRFM_DASHBOARD . 'forms/grafema-posts-options.php' ),
							]
						);
						?>
			        </div>
			    <?php endif; ?>
			    <?php if ( $uploader ) : ?>
				    <div class="df aic g-1">
					    <button class="btn btn--sm" @click="$modal.open('grafema-modals-uploader')">
						    <i class="ph ph-upload-simple"></i> <?php I18n::t( 'Add new file' ); ?>
					    </button>
				    </div>
			    <?php endif; ?>
			    <?php if ( $actions ) : ?>
			        <div class="df aic g-1" x-show="bulk" x-cloak>
						<?php Dashboard\Form::print( 'grafema-posts-actions', GRFM_DASHBOARD . 'forms/grafema-posts-actions.php' ); ?>
			            <button type="button" class="btn btn--sm t-red" x-bind="reset"><?php I18n::tf( '%s Reset', '<i class="ph ph-trash"></i>' ); ?></button>
			        </div>
			    <?php endif; ?>
			    <?php if ( $translation ) : ?>
				    <div class="df aic g-2">
					    <div class="df aic g-1">
						    <img src="/dashboard/assets/images/flags/us.svg" alt="USA" width="16" height="16"> English
					    </div>
					    <span class="badge badge--round badge--icon badge--lg"><i class="ph ph-arrows-left-right"></i></span>
					    <?php
					    View::print(
						    'templates/form/select',
						    [
							    'type'        => 'select',
							    'uid'         => 'language',
							    'label'       => '',
							    'class'       => 'field field--sm field--outline',
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
								    'name' => 'language',
							    ],
							    'options'     => [
								    'ru_RU' => [
									    'content' => I18n::_t( 'Russian' ),
									    'image'   => 'assets/images/flags/ru.svg',
								    ],
								    'de_DE' => [
									    'content' => I18n::_t( 'Germany' ),
									    'image'   => 'assets/images/flags/de.svg',
								    ],
							    ],
						    ]
					    );

					    View::print(
						    'templates/form/select',
						    [
							    'type'        => 'select',
							    'uid'         => 'project',
							    'label'       => '',
							    'class'       => 'field field--sm field--outline',
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
								    'name' => 'project',
							    ],
							    'options'     => [
								    'plugins' => [
									    'label'   => I18n::_t( 'Plugins' ),
									    'options' => [
										    'one' => [
											    'content'     => I18n::_t( 'Plugin #1' ),
											    'description' => I18n::_f( 'completion %d%%', 11 ),
										    ],
										    'two' => [
											    'content'     => I18n::_t( 'Plugin #2' ),
											    'description' => I18n::_f( 'completion %d%%', 66 ),
										    ],
									    ],
								    ],
								    'themes' => [
									    'label'   => I18n::_t( 'Themes' ),
									    'options' => [
										    'theme1' => [
											    'content'     => I18n::_t( 'Theme #1' ),
											    'description' => I18n::_f( 'completion %d%%', 99 ),
										    ],
										    'theme2' => [
											    'content'     => I18n::_t( 'Theme #2' ),
											    'description' => I18n::_f( 'completion %d%%', 55 ),
										    ],
									    ],
								    ],
							    ],
						    ]
					    );
					    ?>
				    </div>
			    <?php endif; ?>
		    </div>
	    <?php endif; ?>
    </div>
	<?php Dashboard\Form::print( 'grafema-fields-builder', GRFM_DASHBOARD . 'forms/grafema-fields-builder.php' ); ?>
	<?php Dashboard\Form::print( 'grafema-items-filter' ); ?>
    <?php $content && print( $content . PHP_EOL ); ?>
</div>

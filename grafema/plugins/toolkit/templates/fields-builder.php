<?php
use Grafema\I18n;
use Grafema\View;

/**
 * Fields builder
 *
 * This template can be overridden by copying it to themes/yourtheme/toolkit/templates/forms-builder.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<div class="fields-builder" x-data>
		<div class="fields-builder__side">
			<div class="fields-builder__header">
				<h4>Fields Builder</h4>
			</div>
			<?php Dashboard\Form::print( 'builder/fields' ); ?>
		</div>
		<div class="fields-builder__main">
			<div class="fields-builder__code">
				<div class="fields-builder__preview">
					<div class="fields-builder__preview-field" data-title="Field Preview">
						<?php
						View::print(
							'templates/form/image',
							[
								'name'        => 'perpage',
								'type'        => 'select',
								'label'       => I18n::_t( 'Number of items per page' ),
								'label_class' => 'df aic fs-12 t-muted',
								'value'       => '',
								'reset'       => false,
								'attributes'  => [
									'x-select' => '{"showSearch":0}',
								],
								'options'     => [
									'25'  => 25,
									'50'  => 50,
									'100' => 100,
									'250' => 250,
									'500' => 500,
								],
							]
						);
						?>
					</div>
				</div>
				<div class="fields-builder__title">
					Code Preview <i class="ph ph-copy" title="<?php I18n::t_attr( 'Copy to clipboard' ); ?>" x-copy="$refs.code.innerText"></i>
				</div>
				<pre x-ref="code" x-highlight.php>
View::print(
	'templates/form/image',
	[
		'name'        =&gt; 'perpage',
		'type'        =&gt; 'select',
		'label'       =&gt; I18n::_t( 'Number of items per page' ),
		'label_class' =&gt; 'df aic fs-12 t-muted',
		'value'       =&gt; '',
		'reset'       =&gt; false,
		'attributes'  =&gt; [
			'x-select' =&gt; '{"showSearch":1}',
		],
		'options'     =&gt; [
			'25'  =&gt; 25,
			'50'  =&gt; 50,
			'100' =&gt; 100,
			'250' =&gt; 250,
			'500' =&gt; 500,
		],
	]
);
</pre>
			</div>
		</div>
	</div>
</div>

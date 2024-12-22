<?php
use Dashboard\Form;
use Grafema\I18n;
use Grafema\View;
use Grafema\Url;

/**
 * Attributes list
 *
 * This template can be overridden by copying it to themes/yourtheme/ecommerce/templates/attributes.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
?>
<div class="grafema-main">
	<div class="attributes">
		<form class="attributes-wrapper" x-data="{values: []}">
			<div class="attributes-editor">
				<h5 class="attributes-title">
					<a class="btn btn--icon btn--sm" href="<?php echo Url::dashboard( '/attributes' ); ?>"><i class="ph ph-arrow-left"></i></a>
					<span class="fw-600 mr-auto"><?php I18n::t( 'Add new attribute' ); ?></span>
					<button class="btn btn--danger" type="button" hidden x-cloak><?php I18n::t( 'Delete' ); ?></button>
					<button class="btn btn--primary" type="submit" disabled><?php I18n::t( 'Save' ); ?></button>
				</h5>
				<div class="attributes-description">
					<p><?php I18n::t( 'Deleting an attribute removes it from all assigned products. Recreating it won’t reassign it automatically.' ); ?></p>
				</div>
				<?php Form::print( GRFM_PLUGINS . 'ecommerce/core/attributes.php', true ); ?>
			</div>
			<div class="attributes-side">
				<div x-text="`<?php I18n::f_attr( ':valuesCount items', '${values.length}' ); ?>`">0 items</div>
				<div class="attributes-list">
					<?php
					View::print(
						'views/form/input',
						[
							'type'        => 'text',
							'name'        => 'value',
							'label'       => '',
							'class'       => '',
							'label_class' => '',
							'reset'       => 0,
							'before'      => '<i class="ph ph-text-t"></i>',
							'after'       => '',
							'instruction' => '',
							'tooltip'     => '',
							'copy'        => 0,
							'sanitizer'   => '',
							'validator'   => '',
							'conditions'  => [],
							'attributes'  => [
								'name'                 => 'value',
								'placeholder'          => I18n::_t( 'Add attribute value and press Enter' ),
								'@keyup.enter.prevent' => '$el.value.trim() && values.push({title: $el.value.trim(), slug: $safe.slug($el.value)}), $el.value = "", values.sort((a, b) => a.title.localeCompare(b.title))',
							],
						],
					);
					?>
					<div class="attributes-values">
						<template x-if="values.length">
							<template x-for="(value, i) in values" :key="i">
								<div class="attributes-value">
									<div class="attributes-value-title">
										<input type="text" :name="`values.${i}.title`" x-model="value.title" placeholder="<?php I18n::t( 'Title' ); ?>" required>
									</div>
									<div class="attributes-value-slug">
										<input type="text" :name="`values.${i}.slug`" x-model="value.slug" placeholder="<?php I18n::t( 'Slug' ); ?>" required>
									</div>
									<div class="btn btn--icon t-red" @click="values.splice(i, 1)"><i class="ph ph-trash"></i></div>
								</div>
							</template>
						</template>
						<template x-if="!values.length">
							<?php
							View::print(
								'views/global/state',
								[
									'icon'        => 'empty-pack',
									'class'       => 'dg jic m-auto t-center p-8 mw-320',
									'title'       => I18n::_t( 'Values not found' ),
									'description' => I18n::_t( 'Try to add new attribute value, there will be results here' ),
								]
							);
							?>
						</template>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

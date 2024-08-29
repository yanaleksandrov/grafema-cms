<?php
use Grafema\I18n;

/**
 * Query builder
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/fields/builder.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="builder">
	<div class="builder-wrapper">
		<?php if ( 0 ) : ?>
			<div class="builder__fields">
				<div class="dg g-4 ga-4 gtc-4">
					<div class="dg g-1">
						<label class="dg g-1">
							<span class="df aic jcsb fw-600">Type</span>
							<select id="name">
								<optgroup label="Post">
									<option value="post_type">Post Type</option>
									<option value="post_template">Post Template</option>
									<option value="post_status">Post Status</option>
									<option value="post_format">Post Format</option>
									<option value="post_category">Post Category</option>
									<option value="post_taxonomy">Post Taxonomy</option>
									<option value="post">Post</option>
								</optgroup>
							</select>
						</label>
					</div>
					<div class="dg g-1">
						<label class="dg g-1">
							<span class="df aic jcsb fw-600">Label</span>
							<span class="field ff-code">
						<input name="permalinks[post]" placeholder="e.g. Just another Grafema site" required>
					</span>
						</label>
					</div>
					<div class="dg g-1">
						<label class="dg g-1">
							<span class="df aic jcsb fw-600">Name</span>
							<span class="field ff-code">
						<input name="permalinks[post]" placeholder="e.g. Just another Grafema site" required>
						<i class="ph ph-info" x-tooltip.click.prevent="'Single word, no spaces. Underscores and dashes allowed'"></i>
					</span>
						</label>
					</div>
					<div class="dg g-1">
						<label class="dg g-1">
							<span class="df aic jcsb fw-600">Default value</span>
							<span class="field ff-code">
						<input name="permalinks[post]" placeholder="e.g. Just another Grafema site" required>
					</span>
						</label>
					</div>
				</div>
			</div>
		<?php endif; ?>
		<template x-for="(group, key) in groups">
			<div class="builder-group" data-or="<?php I18n::t_attr( 'or' ); ?>">
				<template x-for="(rule, i) in group.rules">
					<div class="builder__rules">
						<div class="dg g-1">
							<select class="select select--sm select--outline" id="location" :name="`group.rules[${i}][location]`" x-model="rule.location">
								<optgroup label="Post">
									<option value="post_type">Post Type</option>
									<option value="post_template">Post Template</option>
									<option value="post_status">Post Status</option>
									<option value="post_format">Post Format</option>
									<option value="post_category">Post Category</option>
									<option value="post_taxonomy">Post Taxonomy</option>
									<option value="post">Post</option>
								</optgroup>
							</select>
						</div>
						<div class="dg g-1">
							<select class="select select--sm select--outline" id="operator" :name="`group.rules[${i}][operator]`" x-model="rule.operator">
								<option value="===">is equal to</option>
								<option value="!=">is not equal to</option>
							</select>
						</div>
						<div class="dg g-1">
							<select class="select select--sm select--outline" id="value" :name="`group.rules[${i}][value]`" x-model="rule.value">
								<option value="subscriber">Subscriber</option>
								<option value="contributor">Contributor</option>
								<option value="author">Author</option>
								<option value="editor">Editor</option>
								<option value="administrator">Administrator</option>
							</select>
						</div>
						<div class="dg g-1" x-show="group.rules.length > 1">
							<button type="button" class="btn btn--sm btn--icon t-red" @click="removeRule(key,i)"><i class="ph ph-trash-simple"></i></button>
						</div>
					</div>
				</template>
				<div class="builder__buttons">
					<button type="button" class="btn btn--sm t-purple" @click="addRule(key)"><?php I18n::tf( '%s and condition', '<i class="ph ph-plus"></i>' ); ?></button>
					<button type="button" class="btn btn--sm t-red" @click="removeGroup(key)" x-show="groups.length > 1"><?php I18n::tf( '%s Remove', '<i class="ph ph-trash-simple"></i>' ); ?></button>
				</div>
			</div>
		</template>
		<div class="builder__buttons mt-2">
			<button class="btn btn--sm btn--outline" type="button" @click="addGroup"><?php I18n::tf( '%s Add Group', '' ); ?></button>
			<button class="btn btn--sm btn--primary" type="submit"><?php I18n::tf( '%s Save', '<i class="ph ph-floppy-disk"></i>' ); ?></button>
		</div>
	</div>
</div>

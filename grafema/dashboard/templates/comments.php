<?php
use Grafema\I18n;
use Grafema\View;

/*
 * Comments list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/comments.php
 *
 * @package     Grafema\Templates
 * @version     1.0.0
 */
if ( ! defined( 'GRFM_PATH' ) ) {
	exit;
}
?>
<div class="grafema-main">
	<?php
	View::print(
		'templates/table/header',
		[
			'title' => I18n::__( 'Comments' ),
		]
	);
    ?>
	<div class="p-0">
		<table class="table" x-data="table">
			<thead>
				<tr>
					<th class="cb"><input type="checkbox" x-bind="trigger"/></th>
					<th></th>
					<th>Title</th>
					<th>Author</th>
					<th>Categories</th>
					<th>Date</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">JakeAlexandrovpetrenko</a></td>
					<td><a href="#">VeryLongCategoryOrTag</a>, <a href="#">WordPress</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">WordPress Team</a></td>
					<td><a href="#">—</td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Helen</a></td>
					<td><a href="#">WordPress</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
				<tr>
					<td class="cb"><input type="checkbox" name="post[]" x-bind="switcher"></td>
					<td><img src="https://via.placeholder.com/100x100?text=IMG"></td>
					<td><a href="#" class="strong">How to make WordPress theme for Evato Market</a> — Draft</td>
					<td><a href="#">Jake Alex</a></td>
					<td><a href="#">Envato</a></td>
					<td>Nov 24, 2021 at 12:30</td>
					<td><i class="dashicons dashicons-trash"></i></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>

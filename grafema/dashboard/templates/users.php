<?php
use Grafema\View;
use Grafema\I18n;

/**
 * Users list.
 *
 * This template can be overridden by copying it to themes/yourtheme/dashboard/templates/users.php
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
			'title' => I18n::__( 'Users' ),
		]
	);

	( new Dashboard\Tables\UsersTable() )->render();
	?>
	<table class="table" x-data="table">
		<thead>
            <tr>
                <th class="is-fit"><input type="checkbox" x-bind="trigger"/></th>
                <th class="img is-fit"></th>
                <th class="name">Name</th>
                <th class="role is-fit">Role</th>
                <th class="status is-fit">Last visit</th>
                <th class="actions is-fit"></th>
            </tr>
		</thead>
		<tbody>
            <tr>
                <td class="cb is-fit"><input type="checkbox" name="post[]" x-bind="switcher"></td>
                <td class="img is-fit">
                    <span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=1)"></span>
                </td>
                <td class="name">
                    <a class="fs-15 fw-600" href="#">Izabella Tabakova</a>
                    <div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
                </td>
                <td class="role is-fit">
                    <span class="badge badge--blue-lt">Admin</span>
                </td>
                <td class="status is-fit">
                    <span class="badge badge--dark-lt">3 days ago</span>
                </td>
                <td class="actions is-fit">
                    <span class="btn btn--icon t-red"><i class="ph ph-trash"></i></span>
                </td>
            </tr>
            <tr>
                <td class="cb is-fit">
                    <input type="checkbox" name="post[]" x-bind="switcher">
                </td>
                <td class="img is-fit">
                    <span class="avatar avatar--rounded" style="background-image: url(https://i.pravatar.cc/150?img=2)" title="Jakob Batallone is online"><span class="badge badge--green"></span></span>
                </td>
                <td class="name">
                    <a class="fs-15 fw-600" href="#">Leslee Moss</a>
                    <div class="fs-13"><a href="mailto:codyshop@team.com" class="t-muted">codyshop@team.com</a></div>
                </td>
                <td class="role is-fit">
                    <span class="badge badge--blue-lt">Subscriber</span>
                </td>
                <td class="status is-fit">
                    <span class="badge badge--dark-lt">3 days ago</span>
                </td>
                <td class="actions is-fit">
                    <span class="btn btn--icon t-red"><i class="ph ph-trash"></i></span>
                </td>
            </tr>
		</tbody>
	</table>
</div>

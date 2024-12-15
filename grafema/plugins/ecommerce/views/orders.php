<?php
use Grafema\View;
use Grafema\I18n;

/**
 * Orders list
 *
 * This template can be overridden by copying it to themes/yourtheme/ecommerce/templates/orders.php
 *
 * @package Grafema\Templates
 * @since   2025.1
 */
?>
<div class="grafema-main">
	<?php
	View::print(
		'views/table/header',
		[
			'title' => I18n::_t( 'Orders' ),
		]
	);
	?>
	<div class="kanban">
		<div class="kanban__wrapper">
			<div class="kanban__col">
				<div class="kanban__title">
					<i class="ph ph-info" x-tooltip.click.prevent="'<?php I18n::t_attr( 'The order has been received and is awaiting processing by the moderator' ); ?>'"></i>
					<span class="fs-15 fw-500 mr-auto">New</span>
					<span class="badge badge--azure-lt">Add order</span>
					<span class="badge">3</span>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> 1 hour ago</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10246
						<span class="badge badge--green-lt">Paid: $ 230.00</span>
					</div>
					<div class="kanban__progress">
						12 items ship to Russia, Moscow
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> John Doe</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
			</div>
			<div class="kanban__col">
				<div class="kanban__title">
					<i class="ph ph-info" x-tooltip.click.prevent="'<?php I18n::t_attr( 'The order has been verified by a moderator. The items from the order are reserved and are being prepared for shipment.' ); ?>'"></i>
					<span class="fs-15 fw-500 mr-auto">In Progress</span>
					<span class="badge">3</span>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10246
						<span class="badge badge--orange-lt">Unpaid: $ 230.00</span>
					</div>
					<div class="kanban__progress">
						12 items ship to Russia, Moscow
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> John Doe</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10246
						<span class="badge badge--orange-lt">Unpaid: $ 230.00</span>
					</div>
					<div class="kanban__progress">
						12 items ship to Russia, Moscow
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> John Doe</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
			</div>
			<div class="kanban__col">
				<div class="kanban__title">
					<i class="ph ph-info" x-tooltip.click.prevent="'<?php I18n::t_attr( 'At this stage, we are preparing the products for shipment' ); ?>'"></i>
					<span class="fs-15 fw-500 mr-auto">Picking</span>
					<span class="badge">3</span>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
			</div>
			<div class="kanban__col">
				<div class="kanban__title">
					<i class="ph ph-info" x-tooltip.click.prevent="'<?php I18n::t_attr( 'The products of order have been transferred to the courier, transport company or postal service' ); ?>'"></i>
					<span class="fs-15 fw-500 mr-auto">Shipping</span>
					<span class="badge">3</span>
				</div>
				<div class="kanban__item">
					<div class="kanban__name">
						#10245
						<span class="badge badge--green-lt">Paid: $ 550.45</span>
					</div>
					<div class="kanban__progress">
						2 items to USA, New Jersey
					</div>
					<div class="kanban__status">
						<span class="fs-12 t-muted"><i class="ph ph-person-simple-run"></i> Jake Aleksandrov</span>
						<span class="kanban__meta" x-tooltip.click.prevent="'Created September 24, 2024 at 10:34 AM'"><i class="ph ph-timer"></i> Sep 24, 2024</span>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
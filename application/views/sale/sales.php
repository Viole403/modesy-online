<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo $title; ?></li>
					</ol>
				</nav>

				<h1 class="page-title"><?php echo $title; ?></h1>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-3">
				<div class="row-custom">
					<!-- load profile nav -->
					<?php $this->load->view("sale/_sale_tabs"); ?>
				</div>
			</div>

			<div class="col-sm-12 col-md-9">
				<div class="row-custom">
					<div class="profile-tab-content">
						<!-- include message block -->
						<?php $this->load->view('partials/_messages'); ?>

						<div class="table-responsive">
							<table class="table table-orders table-striped">
								<thead>
								<tr>
									<th scope="col"><?php echo trans("sale"); ?></th>
									<th scope="col"><?php echo trans("total"); ?></th>
									<th scope="col"><?php echo trans("payment"); ?></th>
									<th scope="col"><?php echo trans("status"); ?></th>
									<th scope="col"><?php echo trans("date"); ?></th>
									<th scope="col"><?php echo trans("options"); ?></th>
								</tr>
								</thead>
								<tbody>
								<?php if (!empty($orders)): ?>
									<?php foreach ($orders as $order):
										$sale = get_order($order->id);
										$total = $this->order_model->get_seller_total_price($order->id);
										if (!empty($sale)):?>
											<tr>
												<td>#<?php echo $sale->order_number; ?></td>
												<td><?php echo print_price($total, $sale->price_currency); ?></td>
												<td>
													<?php if ($sale->payment_status == 'payment_received'):
														echo trans("payment_received");
													else:
														echo trans("awaiting_payment");
													endif; ?>
												</td>
												<td>
													<strong class="font-600">
														<?php if ($sale->payment_status == 'awaiting_payment'):
															if ($sale->payment_method == 'Cash On Delivery') {
																echo trans("order_processing");
															} else {
																echo trans("awaiting_payment");
															}
														else:
															if ($active_tab == "active_sales"):
																echo trans("order_processing");
															else:
																echo trans("completed");
															endif;
														endif; ?>
													</strong>
												</td>
												<td><?php echo date("Y-m-d / h:i", strtotime($sale->created_at)); ?></td>
												<td>
													<a href="<?php echo lang_base_url(); ?>sale/<?php echo $sale->order_number; ?>" class="btn btn-sm btn-table-info"><?php echo trans("details"); ?></a>
												</td>
											</tr>
										<?php endif;
									endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>


						<?php if (empty($orders)): ?>
							<p class="text-center">
								<?php echo trans("no_records_found"); ?>
							</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="row-custom m-t-15">
					<div class="float-right">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->


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
				<div class="row">
					<div class="col-12">
						<!-- include message block -->
						<?php $this->load->view('product/_messages'); ?>
					</div>
				</div>

				<div class="order-details-container">
					<div class="order-head">
						<h2 class="title"><?php echo trans("sale"); ?>:&nbsp;#<?php echo $order->order_number; ?></h2>
					</div>
					<div class="order-body">
						<div class="row">
							<div class="col-12">
								<div class="row order-row-item">
									<div class="col-3">
										<?php echo trans("status"); ?>
									</div>
									<div class="col-9">
										<?php
										$order_status = 1;
										foreach ($order_products as $item):
											if ($item->order_status != 'completed') {
												$order_status = 0;
											}
										endforeach; ?>

										<?php if ($order_status == 1): ?>
											<strong><?php echo trans("completed"); ?></strong>
										<?php else: ?>
											<strong><?php echo trans("order_processing"); ?></strong>
										<?php endif; ?>
									</div>
								</div>
								<div class="row order-row-item">
									<div class="col-3">
										<?php echo trans("payment_status"); ?>
									</div>
									<div class="col-9">
										<?php echo trans($order->payment_status); ?>
									</div>
								</div>
								<div class="row order-row-item">
									<div class="col-3">
										<?php echo trans("payment_method"); ?>
									</div>
									<div class="col-9">
										<?php
										if ($order->payment_method == "Bank Transfer") {
											echo trans("bank_transfer");
										} elseif ($order->payment_method == "Cash On Delivery") {
											echo trans("cash_on_delivery");
										} else {
											echo $order->payment_method;
										} ?>
									</div>
								</div>
								<div class="row order-row-item">
									<div class="col-3">
										<?php echo trans("date"); ?>
									</div>
									<div class="col-9">
										<?php echo date("Y-m-d / h:i", strtotime($order->created_at)); ?>
									</div>
								</div>
								<div class="row order-row-item">
									<div class="col-3">
										<?php echo trans("updated"); ?>
									</div>
									<div class="col-9">
										<?php echo time_ago($order->updated_at); ?>
									</div>
								</div>
							</div>
						</div>

						<?php $shipping = get_order_shipping($order->id);
						if (!empty($shipping)):?>
							<div class="row shipping-container">
								<div class="col-md-12 col-lg-6">
									<h3 class="block-title"><?php echo trans("shipping_address"); ?></h3>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("first_name"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_first_name; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("last_name"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_last_name; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("email"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_email; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("phone_number"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_phone_number; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("address"); ?>&nbsp;1
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_address_1; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("address"); ?>&nbsp;2
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_address_2; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("country"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_country; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("state"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_state; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("city"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_city; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("zip_code"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->shipping_zip_code; ?>
										</div>
									</div>
								</div>
								<div class="col-md-12 col-lg-6">
									<h3 class="block-title"><?php echo trans("billing_address"); ?></h3>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("first_name"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_first_name; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("last_name"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_last_name; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("email"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_email; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("phone_number"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_phone_number; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("address"); ?>&nbsp;1
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_address_1; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("address"); ?>&nbsp;2
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_address_2; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("country"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_country; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("state"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_state; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("city"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_city; ?>
										</div>
									</div>
									<div class="row shipping-row-item">
										<div class="col-5">
											<?php echo trans("zip_code"); ?>
										</div>
										<div class="col-7">
											<?php echo $shipping->billing_zip_code; ?>
										</div>
									</div>
								</div>
							</div>
						<?php endif; ?>

						<div class="row table-orders-container">
							<div class="col-12">
								<h3 class="block-title"><?php echo trans("products"); ?></h3>
								<div class="table-responsive">
									<table class="table table-orders">
										<thead>
										<tr>
											<th scope="col"><?php echo trans("product"); ?></th>
											<th scope="col"><?php echo trans("status"); ?></th>
											<th scope="col"><?php echo trans("updated"); ?></th>
											<th scope="col"><?php echo trans("options"); ?></th>
										</tr>
										</thead>
										<tbody>
										<?php
										$sale_subtotal = 0;
										$sale_shipping = 0;
										$sale_total = 0;
										foreach ($order_products as $item):
											if ($item->seller_id == user()->id):
												$sale_subtotal += $item->product_unit_price * $item->product_quantity;
												$sale_shipping += $item->product_shipping_cost;
												$sale_total += $item->product_total_price; ?>
												<tr>
													<td>
														<div class="table-item-product">
															<div class="left">
																<div class="img-table">
																	<a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank">
																		<img src="<?php echo get_product_image($item->product_id, 'image_small'); ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
																	</a>
																</div>
															</div>
															<div class="right">
																<a href="<?php echo base_url() . $item->product_slug; ?>" target="_blank" class="table-product-title">
																	<?php echo html_escape($item->product_title); ?>
																</a>
																<p>
																	<span><?php echo trans("seller"); ?>:</span>
																	<?php $seller = get_user($item->seller_id); ?>
																	<?php if (!empty($seller)): ?>
																		<a href="<?php echo base_url(); ?>profile/<?php echo $seller->slug; ?>" target="_blank" class="table-product-title">
																			<strong class="font-600"><?php echo get_shop_name($seller); ?></strong>
																		</a>
																	<?php endif; ?>
																</p>
																<p><span class="span-product-dtl-table"><?php echo trans("unit_price"); ?>:</span><?php echo print_price($item->product_unit_price, $item->product_currency); ?></p>
																<p><span class="span-product-dtl-table"><?php echo trans("quantity"); ?>:</span><?php echo $item->product_quantity; ?></p>
																<?php if ($item->product_type == 'physical'): ?>
																	<p><span class="span-product-dtl-table"><?php echo trans("shipping"); ?>:</span><?php echo print_price($item->product_shipping_cost, $item->product_currency); ?></p>
																<?php endif; ?>
																<p><span class="span-product-dtl-table"><?php echo trans("total"); ?>:</span><?php echo print_price($item->product_total_price, $item->product_currency); ?></p>
															</div>
														</div>
													</td>
													<td>
														<strong><?php echo trans($item->order_status) ?></strong>
													</td>
													<td>
														<?php if ($item->product_type == 'physical') {
															echo time_ago($item->updated_at);
														} ?>
													</td>
													<td>
														<?php if ($item->order_status == "completed"): ?>
															<strong class="font-600"><i class="icon-check"></i>&nbsp;<?php echo trans("approved"); ?></strong>
														<?php else: ?>
															<p class="m-b-5">
																<button type="button" class="btn btn-sm btn-secondary btn-sale-options" data-toggle="modal" data-target="#updateStatusModal_<?php echo $item->id; ?>"><?php echo trans('update_order_status'); ?></button>
															</p>
															<?php if ($item->product_type == 'physical'): ?>
																<p>
																	<button type="button" class="btn btn-sm btn-secondary btn-sale-options" data-toggle="modal" data-target="#addTrackingNumberModal_<?php echo $item->id; ?>"><?php echo trans('add_tracking_number'); ?></button>
																</p>
															<?php endif; ?>
														<?php endif; ?>
													</td>
												</tr>
												<?php if ($item->order_status == "shipped"): ?>
												<tr class="tr-shipping">
													<td colspan="4">
														<div class="order-shipping-tracking-number">
															<p><strong><?php echo trans("shipping") ?></strong></p>
															<p><?php echo trans("tracking_number") ?>:&nbsp;<?php echo html_escape($item->shipping_tracking_number); ?></p>
															<p><?php echo trans("url") ?>: <a href="<?php echo html_escape($item->shipping_tracking_url); ?>" target="_blank" class="link-underlined"><?php echo html_escape($item->shipping_tracking_url); ?></a></p>
														</div>
													</td>
												</tr>
												<tr class="tr-shipping-seperator">
													<td colspan="4"></td>
												</tr>
											<?php endif; ?>
											<?php endif;
										endforeach; ?>
										</tbody>
									</table>
								</div>
							</div>
						</div>

						<div class="row">
							<div class="col-12">
								<div class="order-total">
									<div class="row">
										<div class="col-6 col-left">
											<?php echo trans("subtotal"); ?>
										</div>
										<div class="col-6 col-right">
											<strong class="font-600"><?php echo print_price($sale_subtotal, $order->price_currency); ?></strong>
										</div>
									</div>
									<div class="row">
										<div class="col-6 col-left">
											<?php echo trans("shipping"); ?>
										</div>
										<div class="col-6 col-right">
											<strong class="font-600"><?php echo print_price($sale_shipping, $order->price_currency); ?></strong>
										</div>
									</div>
									<div class="row">
										<div class="col-12">
											<div class="row-seperator"></div>
										</div>
									</div>
									<div class="row">
										<div class="col-6 col-left">
											<?php echo trans("total"); ?>
										</div>
										<div class="col-6 col-right">
											<strong class="font-600"><?php echo print_price($sale_total, $order->price_currency); ?></strong>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Wrapper End-->
<?php foreach ($order_products as $item):
	if ($item->seller_id == user()->id):?>
		<div class="modal fade" id="updateStatusModal_<?php echo $item->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content modal-custom">
					<!-- form start -->
					<?php echo form_open_multipart('order_controller/update_order_product_status_post'); ?>
					<input type="hidden" name="id" value="<?php echo $item->id; ?>">
					<div class="modal-header">
						<h5 class="modal-title"><?php echo trans("update_order_status"); ?></h5>
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="icon-close"></i> </span>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-12">
								<div class="form-group">
									<label class="control-label"><?php echo trans('status'); ?></label>
									<div class="selectdiv">
										<select name="order_status" class="form-control order-status-select" data-order-product-id="<?php echo $item->id; ?>">
											<?php if ($item->product_type == 'physical'): ?>
												<?php if ($order->payment_method == "Bank Transfer"): ?>
													<option value="awaiting_payment" <?php echo ($item->order_status == 'awaiting_payment') ? 'selected' : ''; ?>><?php echo trans("awaiting_payment"); ?></option>
												<?php endif; ?>
											<?php endif; ?>
											<?php if ($order->payment_method != "Cash On Delivery"): ?>
												<option value="payment_received" <?php echo ($item->order_status == 'payment_received') ? 'selected' : ''; ?>><?php echo trans("payment_received"); ?></option>
											<?php endif; ?>
											<?php if ($item->product_type == 'physical'): ?>
												<option value="order_processing" <?php echo ($item->order_status == 'order_processing') ? 'selected' : ''; ?>><?php echo trans("order_processing"); ?></option>
												<option value="shipped" <?php echo ($item->order_status == 'shipped') ? 'selected' : ''; ?>><?php echo trans("shipped"); ?></option>
											<?php endif; ?>
										</select>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?php echo trans("close"); ?></button>
						<button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
					</div>
					<?php echo form_close(); ?><!-- form end -->
				</div>
			</div>
		</div>

		<div class="modal fade" id="addTrackingNumberModal_<?php echo $item->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content modal-custom">
					<!-- form start -->
					<?php echo form_open_multipart('order_controller/add_shipping_tracking_number_post'); ?>
					<input type="hidden" name="id" value="<?php echo $item->id; ?>">
					<div class="modal-header">
						<h5 class="modal-title"><?php echo trans("add_shipping_tracking_number"); ?></h5>
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="icon-close"></i> </span>
						</button>
					</div>
					<div class="modal-body">

						<div class="row tracking-number-container">
							<div class="col-12">
								<div class="form-group">
									<label><?php echo trans('tracking_number'); ?></label>
									<input type="text" name="shipping_tracking_number" class="form-control form-input" value="<?php echo html_escape($item->shipping_tracking_number); ?>">
								</div>
								<div class="form-group">
									<label><?php echo trans('url'); ?></label>
									<input type="text" name="shipping_tracking_url" class="form-control form-input" value="<?php echo html_escape($item->shipping_tracking_url); ?>">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-md btn-red" data-dismiss="modal"><?php echo trans("close"); ?></button>
						<button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
					</div>
					<?php echo form_close(); ?><!-- form end -->
				</div>
			</div>
		</div>
	<?php endif;
endforeach; ?>

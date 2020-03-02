<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('email/_header', ['title' => trans("email_text_thank_for_order")]); ?>
	<!-- START CENTERED WHITE CONTAINER -->
	<table role="presentation" class="main">
		<!-- START MAIN CONTENT AREA -->
		<?php if (!empty($order)): ?>
			<tr>
				<td class="wrapper">
					<table role="presentation" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td>
								<h1 style="text-decoration: none; font-size: 24px;line-height: 28px;font-weight: bold"><?php echo trans("email_text_thank_for_order"); ?></h1>
								<div class="mailcontent" style="line-height: 26px;font-size: 14px;">
									<p style='text-align: left;color: #555;'>
										<?php echo trans("email_text_new_order"); ?>
									</p><br>
									<h2 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("order_information"); ?></h2>
									<p style="color: #555;">
										<?php echo trans("order"); ?>:&nbsp;#<?php echo $order->order_number; ?><br>
										<?php echo trans("payment_status"); ?>:&nbsp;<?php echo trans($order->payment_status); ?><br>
										<?php echo trans("payment_method"); ?>:&nbsp;<?php
										if ($order->payment_method == "Bank Transfer") {
											echo trans("bank_transfer");
										} elseif ($order->payment_method == "Cash On Delivery") {
											echo trans("cash_on_delivery");
										} else {
											echo $order->payment_method;
										} ?>
										<br>
										<?php echo trans("date"); ?>:&nbsp;<?php echo date("Y-m-d / h:i", strtotime($order->created_at)); ?><br>
									</p>
								</div>

								<?php $shipping = get_order_shipping($order->id);
								if (!empty($shipping)):?>
									<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="margin-top: 30px;">
										<tr>
											<td>
												<h3 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("shipping_address"); ?></h3>
												<p style="color: #555; padding-right: 10px;">
													<?php echo trans("first_name"); ?>:&nbsp;<?php echo $shipping->shipping_first_name; ?><br>
													<?php echo trans("last_name"); ?>:&nbsp;<?php echo $shipping->shipping_last_name; ?><br>
													<?php echo trans("email"); ?>:&nbsp;<?php echo $shipping->shipping_email; ?><br>
													<?php echo trans("phone_number"); ?>:&nbsp;<?php echo $shipping->shipping_phone_number; ?><br>
													<?php echo trans("address"); ?> 1:&nbsp;<?php echo $shipping->shipping_address_1; ?><br>
													<?php echo trans("address"); ?> 2:&nbsp;<?php echo $shipping->shipping_address_2; ?><br>
													<?php echo trans("country"); ?>:&nbsp;<?php echo $shipping->shipping_country; ?><br>
													<?php echo trans("state"); ?>:&nbsp;<?php echo $shipping->shipping_state; ?><br>
													<?php echo trans("city"); ?>:&nbsp;<?php echo $shipping->shipping_city; ?><br>
													<?php echo trans("zip_code"); ?>:&nbsp;<?php echo $shipping->shipping_zip_code; ?><br>
												</p>
											</td>
											<td>
												<h3 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;"><?php echo trans("billing_address"); ?></h3>
												<p style="color: #555; padding-right: 10px;">
													<?php echo trans("first_name"); ?>:&nbsp;<?php echo $shipping->billing_first_name; ?><br>
													<?php echo trans("last_name"); ?>:&nbsp;<?php echo $shipping->billing_last_name; ?><br>
													<?php echo trans("email"); ?>:&nbsp;<?php echo $shipping->billing_email; ?><br>
													<?php echo trans("phone_number"); ?>:&nbsp;<?php echo $shipping->billing_phone_number; ?><br>
													<?php echo trans("address"); ?> 1:&nbsp;<?php echo $shipping->billing_address_1; ?><br>
													<?php echo trans("address"); ?> 2:&nbsp;<?php echo $shipping->billing_address_2; ?><br>
													<?php echo trans("country"); ?>:&nbsp;<?php echo $shipping->billing_country; ?><br>
													<?php echo trans("state"); ?>:&nbsp;<?php echo $shipping->billing_state; ?><br>
													<?php echo trans("city"); ?>:&nbsp;<?php echo $shipping->billing_city; ?><br>
													<?php echo trans("zip_code"); ?>:&nbsp;<?php echo $shipping->billing_zip_code; ?><br>
												</p>
											</td>
										</tr>
									</table>
								<?php endif; ?>

								<h3 style="margin-bottom: 10px; font-size: 16px;font-weight: 600;border-bottom: 1px solid #d1d1d1;padding-bottom: 5px; margin-top: 30px;"><?php echo trans("products"); ?></h3>
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="text-align: left" class="table-products">
									<tr>
										<th style=""><?php echo trans("product"); ?></th>
										<th style=""><?php echo trans("unit_price"); ?></th>
										<th><?php echo trans("quantity"); ?></th>
										<th><?php echo trans("shipping"); ?></th>
										<th><?php echo trans("price"); ?></th>
									</tr>
									<?php foreach ($order_products as $item): ?>
										<tr>
											<td><?php echo $item->product_title; ?></td>
											<td><?php echo print_price($item->product_unit_price, $item->product_currency); ?></td>
											<td><?php echo $item->product_quantity; ?></td>
											<td><?php echo print_price($item->product_shipping_cost, $item->product_currency); ?></td>
											<td><?php echo print_price($item->product_total_price, $item->product_currency); ?></td>
										</tr>
									<?php endforeach; ?>
								</table>
								<table role="presentation" border="0" cellpadding="0" cellspacing="0" style="text-align: right;margin-top: 20px;">
									<tr>
										<td style="width: 70%"><?php echo trans("subtotal"); ?></td>
										<td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo print_price($order->price_subtotal, $order->price_currency); ?></td>
									</tr>
									<tr>
										<td style="width: 70%"><?php echo trans("shipping"); ?></td>
										<td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo print_price($order->price_shipping, $order->price_currency); ?></td>
									</tr>
									<tr>
										<td style="width: 70%"><?php echo trans("total"); ?></td>
										<td style="width: 30%;padding-right: 15px;font-weight: 600;"><?php echo print_price($order->price_total, $order->price_currency); ?></td>
									</tr>
								</table>
								<?php if ($order->buyer_type != 'guest'): ?>
									<p style='text-align: center;margin-top: 40px;'>
										<a href="<?php echo lang_base_url() . 'order/' . $order->order_number; ?>" style='font-size: 14px;text-decoration: none;padding: 14px 40px;background-color: #09b1ba;color: #ffffff !important; border-radius: 3px;'>
											<?php echo trans("see_order_details"); ?>
										</a>
									</p>
								<?php endif; ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		<?php endif; ?>
		<!-- END MAIN CONTENT AREA -->
	</table>
<?php $this->load->view('email/_footer'); ?>

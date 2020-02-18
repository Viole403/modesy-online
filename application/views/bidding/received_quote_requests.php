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
			<div class="col-12">
				<ul class="section-tab-links">
					<li class="active">
						<a href="<?php echo lang_base_url(); ?>quote-requests"><?php echo trans('received_quote_requests'); ?>&nbsp;(<?php echo $received_request_count; ?>)</a>
					</li>
					<li>
						<a href="<?php echo lang_base_url(); ?>sent-quote-requests"><?php echo trans('sent_quote_requests'); ?>&nbsp;(<?php echo $sent_request_count; ?>)</a>
					</li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12">
				<div class="row-custom">
					<div class="profile-tab-content">
						<!-- include message block -->
						<?php $this->load->view('partials/_messages'); ?>
						<div class="table-responsive">
							<table class="table table-quote_requests table-striped">
								<thead>
								<tr>
									<th scope="col"><?php echo trans("quote"); ?></th>
									<th scope="col"><?php echo trans("product"); ?></th>
									<th scope="col"><?php echo trans("buyer"); ?></th>
									<th scope="col"><?php echo trans("status"); ?></th>
									<th scope="col"><?php echo trans("sellers_bid"); ?></th>
									<th scope="col"><?php echo trans("updated"); ?></th>
									<th scope="col"><?php echo trans("options"); ?></th>
								</tr>
								</thead>
								<tbody>
								<?php if (!empty($quote_requests)): ?>
									<?php foreach ($quote_requests as $quote_request):
										$quote_product = get_product($quote_request->product_id); ?>
										<tr>
											<td>#<?php echo $quote_request->id; ?></td>
											<td>
												<?php if (!empty($quote_product)): ?>
													<div class="table-item-product">
														<div class="left">
															<div class="img-table">
																<a href="<?php echo base_url() . $quote_product->slug; ?>" target="_blank">
																	<img src="<?php echo get_product_image($quote_product->id, 'image_small'); ?>" data-src="" alt="" class="lazyload img-responsive post-image"/>
																</a>
															</div>
														</div>
														<div class="right">
															<a href="<?php echo base_url() . $quote_product->slug; ?>" target="_blank">
																<h3 class="table-product-title"><?php echo $quote_request->product_title; ?></h3>
															</a>
															<?php echo trans("quantity") . ": " . $quote_request->product_quantity; ?>
														</div>
													</div>
												<?php else: ?>
													<h3 class="table-product-title"><?php echo $quote_request->product_title; ?></h3>
												<?php endif; ?>
											</td>
											<td>
												<?php $buyer = get_user($quote_request->buyer_id);
												if (!empty($buyer)): ?>
													<a href="<?php echo generate_profile_url($buyer); ?>" target="_blank" class="font-600">
														<?php echo html_escape($buyer->username); ?>
													</a>
												<?php endif; ?>
											</td>
											<td><?php echo trans($quote_request->status); ?></td>
											<td>
												<?php if ($quote_request->status != 'new_quote_request' && $quote_request->price_offered != 0): ?>
													<div class="table-seller-bid">
														<p><b><?php echo trans("price"); ?>:&nbsp;</b><strong><?php echo print_price($quote_request->price_offered, $quote_request->price_currency); ?></strong></p>
														<?php if (!empty($quote_product) && $quote_product->product_type == 'digital'): ?>
															<p><b><?php echo trans("shipping"); ?>:&nbsp;</b><strong><?php echo trans("no_shipping"); ?></strong></p>
														<?php else: ?>
															<p><b><?php echo trans("shipping"); ?>:&nbsp;</b><strong><?php echo print_price($quote_request->shipping_cost, $quote_request->price_currency); ?></strong></p>
														<?php endif; ?>
														<p><b><?php echo trans("total"); ?>:&nbsp;</b><strong><?php echo print_price($quote_request->price_offered + $quote_request->shipping_cost, $quote_request->price_currency); ?></strong></p>
													</div>
												<?php endif; ?>
											</td>
											<td><?php echo time_ago($quote_request->updated_at); ?></td>
											<td>
												<?php if ($quote_request->status == 'new_quote_request'): ?>
													<button type="button" class="btn btn-sm btn-info btn-table-option btn_submit_quote" data-toggle="modal" data-target="#modalSubmitQuote<?php echo $quote_request->id; ?>"><?php echo trans("submit_a_quote"); ?></button>
												<?php elseif ($quote_request->status == 'pending_quote'): ?>
													<button type="button" class="btn btn-sm btn-info btn-table-option btn_update_quote" data-toggle="modal" data-target="#modalSubmitQuote<?php echo $quote_request->id; ?>"><?php echo trans("update_quote"); ?></button>
												<?php elseif ($quote_request->status == 'rejected_quote'): ?>
													<button type="button" class="btn btn-sm btn-info btn-table-option btn_submit_quote" data-toggle="modal" data-target="#modalSubmitQuote<?php echo $quote_request->id; ?>"><?php echo trans("submit_a_new_quote"); ?></button>
												<?php endif; ?>
												<button type="button" class="btn btn-sm btn-danger btn-table-option" onclick="delete_quote_request(<?php echo $quote_request->id; ?>,'<?php echo trans("confirm_quote_request"); ?>');"><?php echo trans("delete_quote"); ?></button>
											</td>
										</tr>
									<?php endforeach; ?>
								<?php endif; ?>
								</tbody>
							</table>
						</div>


						<?php if (empty($quote_requests)): ?>
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

<!-- Modal -->
<?php if (!empty($quote_requests)):
	foreach ($quote_requests as $quote_request):
		$quote_product = get_product($quote_request->product_id); ?>
		<div class="modal fade" id="modalSubmitQuote<?php echo $quote_request->id; ?>" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered" role="document">
				<div class="modal-content modal-custom">
					<!-- form start -->
					<?php echo form_open_multipart('bidding_controller/submit_quote'); ?>
					<div class="modal-header">
						<h5 class="modal-title"><?php echo trans("submit_a_quote"); ?></h5>
						<button type="button" class="close" data-dismiss="modal">
							<span aria-hidden="true"><i class="icon-close"></i> </span>
						</button>
					</div>
					<div class="modal-body">
						<input type="hidden" name="id" class="form-control" value="<?php echo $quote_request->id; ?>">

						<div class="form-group">
							<label class="control-label"><?php echo trans('price'); ?></label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text input-group-text-currency" id="basic-addon1"><?php echo get_currency($payment_settings->default_product_currency); ?></span>
									<input type="hidden" name="currency" value="<?php echo $payment_settings->default_product_currency; ?>">
								</div>
								<input type="text" name="price" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" data-item-id="<?php echo $quote_request->id; ?>" data-product-quantity="<?php echo $quote_request->product_quantity; ?>"
									   placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
							</div>
						</div>
						<?php if (!empty($quote_product) && $quote_product->product_type == 'digital'): ?>
							<input type="hidden" name="shipping_cost" value="0">
							<input type="hidden" name="currency" value="<?php echo $payment_settings->default_product_currency; ?>">
						<?php else: ?>
							<div class="form-group">
								<label class="control-label"><?php echo trans('shipping_cost'); ?></label>
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text input-group-text-currency" id="basic-addon1"><?php echo get_currency($payment_settings->default_product_currency); ?></span>
										<input type="hidden" name="currency" value="<?php echo $payment_settings->default_product_currency; ?>">
									</div>
									<input type="text" name="shipping_cost" aria-describedby="basic-addon1" class="form-control form-input price-input validate-price-input" placeholder="<?php echo $this->input_initial_price; ?>" onpaste="return false;" maxlength="32" required>
								</div>
							</div>
						<?php endif; ?>
						<div class="form-group">
							<p class="calculated-price">
								<strong><?php echo trans("unit_price"); ?> (<?php echo get_currency($payment_settings->default_product_currency); ?>):&nbsp;&nbsp;
									<i id="unit_price_<?php echo $quote_request->id; ?>" class="earned-price">
										<?php echo number_format(0, 2, '.', ''); ?>
									</i>
								</strong><br>
								<strong><?php echo trans("you_will_earn"); ?> (<?php echo get_currency($payment_settings->default_product_currency); ?>):&nbsp;&nbsp;
									<i id="earned_price_<?php echo $quote_request->id; ?>" class="earned-price">
										<?php $earned_price = $quote_product->price - (($quote_product->price * $general_settings->commission_rate) / 100);
										$earned_price = number_format($earned_price, 2, '.', '');
										echo price_format_input($earned_price); ?>
									</i>
								</strong>&nbsp;&nbsp;&nbsp;
								<small> (<?php echo trans("commission_rate"); ?>:&nbsp;&nbsp;<?php echo $general_settings->commission_rate; ?>%)</small>
							</p>
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
	<?php endforeach;
endif; ?>


<script>
    //calculate product earned value
    var thousands_separator = '<?php echo $this->thousands_separator; ?>';
    var commission_rate = '<?php echo $this->general_settings->commission_rate; ?>';
    $(document).on("input keyup paste change", ".price-input", function () {
        var input_val = $(this).val();
        var data_item_id = $(this).attr('data-item-id');
        var data_product_quantity = $(this).attr('data-product-quantity');
        input_val = input_val.replace(',', '.');
        var price = parseFloat(input_val);
        commission_rate = parseInt(commission_rate);
        //calculate earned price
        if (!Number.isNaN(price)) {
            var earned_price = price - ((price * commission_rate) / 100);
            earned_price = earned_price.toFixed(2);
            if (thousands_separator == ',') {
                earned_price = earned_price.replace('.', ',');
            }
        } else {
            earned_price = '0' + thousands_separator + '00';
        }

        //calculate unit price
        if (!Number.isNaN(price)) {
            var unit_price = price / data_product_quantity;
            unit_price = unit_price.toFixed(2);
            if (thousands_separator == ',') {
                unit_price = unit_price.replace('.', ',');
            }
        } else {
            unit_price = '0' + thousands_separator + '00';
        }

        $("#earned_price_" + data_item_id).html(earned_price);
        $("#unit_price_" + data_item_id).html(unit_price);
    });

    $(document).on("click", ".btn_submit_quote", function () {
        $('.modal-title').text("<?php echo trans("submit_a_quote"); ?>");
    });
    $(document).on("click", ".btn_update_quote", function () {
        $('.modal-title').text("<?php echo trans("update_quote"); ?>");
    });
</script>


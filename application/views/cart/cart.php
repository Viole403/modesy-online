<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $disable_checkout_button = false; ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<?php if ($cart_items != null): ?>
					<div class="shopping-cart">
						<div class="row">
							<div class="col-sm-12 col-lg-8">
								<div class="left">
									<h1 class="cart-section-title"><?php echo trans("my_cart"); ?> (<?php echo get_cart_product_count(); ?>)</h1>
									<?php if (!empty($cart_items)):
										$last_product_url = "";
										foreach ($cart_items as $cart_item):
											$product = get_available_product($cart_item->product_id);
											if (!empty($product)): ?>
												<div class="item">
													<div class="cart-item-image">
														<div class="img-cart-product">
															<a href="<?php echo generate_product_url($product); ?>">
																<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($cart_item->product_id, 'image_small'); ?>" alt="<?php echo html_escape($cart_item->product_title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
															</a>
														</div>
													</div>
													<div class="cart-item-details">
														<?php if ($product->product_type == 'digital'): ?>
															<div class="list-item">
																<label class="label-instant-download label-instant-download-sm"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
															</div>
														<?php endif; ?>
														<div class="list-item">
															<a href="<?php echo generate_product_url($product); ?>">
																<?php echo html_escape($cart_item->product_title); ?>
															</a>
															<?php if (empty($cart_item->is_quantity_available)):
																$disable_checkout_button = true; ?>
																<div class="lbl-enough-quantity"><?php echo trans("not_enough_quantity"); ?></div>
															<?php endif; ?>
														</div>
														<div class="list-item seller">
															<?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo get_shop_name_product($product); ?></a>
														</div>
														<div class="list-item m-t-15">
															<label><?php echo trans("unit_price"); ?>:</label>
															<strong class="lbl-price"><?php echo print_price($cart_item->unit_price, $cart_item->currency); ?></strong>
														</div>
														<div class="list-item">
															<label><?php echo trans("total"); ?>:</label>
															<strong class="lbl-price"><?php echo print_price($cart_item->total_price, $cart_item->currency); ?></strong>
														</div>
														<?php if ($product->product_type != 'digital' && $this->form_settings->shipping == 1): ?>
															<div class="list-item">
																<label><?php echo trans("shipping"); ?>:</label>
																<strong><?php echo print_price($cart_item->shipping_cost, $cart_item->currency); ?></strong>
															</div>
														<?php endif; ?>
														<a href="javascript:void(0)" class="btn btn-md btn-outline-gray btn-cart-remove" onclick="remove_from_cart('<?php echo $cart_item->cart_item_id; ?>');"><i class="icon-close"></i> <?php echo trans("remove"); ?></a>
													</div>
													<div class="cart-item-quantity">
														<?php if ($cart_item->purchase_type == 'bidding'): ?>
															<div class="touchspin-container">
																<span><?php echo $cart_item->quantity; ?></span>
															</div>
														<?php else: ?>
															<?php if ($product->quantity > 1): ?>
																<div class="touchspin-container">
																	<input id="quantity_touchspin_<?php echo $cart_item->cart_item_id; ?>" type="text" value="<?php echo $cart_item->quantity; ?>" class="form-input">
																</div>
															<?php endif; ?>
														<?php endif; ?>
													</div>
												</div>
												<?php
												$last_product_url = generate_product_url($product);
											endif;
										endforeach;
									endif; ?>
								</div>
								<a href="<?php echo $last_product_url; ?>" class="btn btn-md btn-custom m-t-15"><i class="icon-arrow-left m-r-2"></i><?php echo trans("keep_shopping") ?></a>
							</div>
							<div class="col-sm-12 col-lg-4">
								<div class="right">
									<p>
										<strong><?php echo trans("subtotal"); ?><span class="float-right"><?php echo print_price($cart_total->subtotal, $cart_total->currency); ?></span></strong>
									</p>
									<?php if ($cart_has_physical_product == true && $this->form_settings->shipping == 1): ?>
										<p>
											<?php echo trans("shipping"); ?><span class="float-right"><?php echo print_price($cart_total->shipping_cost, $cart_total->currency); ?></span>
										</p>
									<?php endif; ?>
									<p class="line-seperator"></p>
									<p>
										<strong><?php echo trans("total"); ?><span class="float-right"><?php echo print_price($cart_total->total, $cart_total->currency); ?></span></strong>
									</p>
									<p class="m-t-30">
										<?php if ($disable_checkout_button): ?>
											<a href="javascript:void(0)" class="btn btn-block"><?php echo trans("continue_to_checkout"); ?></a>
										<?php else:
											if (empty($this->auth_check) && $general_settings->guest_checkout != 1): ?>
												<a href="#" class="btn btn-block" data-toggle="modal" data-target="#loginModal"><?php echo trans("continue_to_checkout"); ?></a>
											<?php else:
												if ($cart_has_physical_product == true && $this->form_settings->shipping == 1): ?>
													<a href="<?php echo lang_base_url(); ?>cart/shipping" class="btn btn-block"><?php echo trans("continue_to_checkout"); ?></a>
												<?php else: ?>
													<a href="<?php echo lang_base_url(); ?>cart/payment-method" class="btn btn-block"><?php echo trans("continue_to_checkout"); ?></a>
												<?php endif;
											endif;
										endif; ?>
									</p>
									<div class="payment-icons">
										<img src="<?php echo base_url(); ?>assets/img/payment/visa.svg" alt="visa">
										<img src="<?php echo base_url(); ?>assets/img/payment/mastercard.svg" alt="mastercard">
										<img src="<?php echo base_url(); ?>assets/img/payment/maestro.svg" alt="maestro">
										<img src="<?php echo base_url(); ?>assets/img/payment/amex.svg" alt="amex">
										<img src="<?php echo base_url(); ?>assets/img/payment/discover.svg" alt="discover">
									</div>
								</div>
							</div>
						</div>
					</div>
				<?php else: ?>
					<div class="shopping-cart-empty">
						<p><strong class="font-600"><?php echo trans("your_cart_is_empty"); ?></strong></p>
						<a href="<?php echo lang_base_url(); ?>" class="btn btn-lg btn-custom"><i class="icon-arrow-left"></i>&nbsp;<?php echo trans("shop_now"); ?></a>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->

<script src="<?php echo base_url(); ?>assets/vendor/touchspin/jquery.bootstrap-touchspin.min.js"></script>
<?php if (!empty($cart_items)):
	foreach ($cart_items as $cart_item):
		$product = get_available_product($cart_item->product_id);
		if (!empty($product) && $product->quantity > 1):?>
			<script>
                $("#quantity_touchspin_<?php echo $cart_item->cart_item_id; ?>").TouchSpin({
                    min: 1,
                    max: <?php echo $product->quantity; ?>,
                    verticalbuttons: true,
                    verticalupclass: 'icon-arrow-up',
                    verticaldownclass: 'icon-arrow-down'
                });
                $("#quantity_touchspin_<?php echo $cart_item->cart_item_id; ?>").change(function () {
                    var quantity = $(this).val();
                    var data = {
                        'product_id': '<?php echo $cart_item->product_id; ?>',
                        'cart_item_id': '<?php echo $cart_item->cart_item_id; ?>',
                        'quantity': quantity
                    };
                    data[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "cart_controller/update_cart_product_quantity",
                        data: data,
                        success: function (response) {
                            location.reload();
                        }
                    });
                });
			</script>
		<?php
		endif;
	endforeach;
endif; ?>

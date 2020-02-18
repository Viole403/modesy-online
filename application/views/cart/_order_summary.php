<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-sm-12 col-lg-5 order-summary-container">
	<h2 class="cart-section-title"><?php echo trans("order_summary"); ?> (<?php echo get_cart_product_count(); ?>)</h2>
	<div class="right">
		<?php $is_physical = false; ?>
		<div class="cart-order-details">
			<?php if (!empty($cart_items)):
				foreach ($cart_items as $cart_item):
					$product = get_available_product($cart_item->product_id);
					if (!empty($product)):
						if ($product->product_type == 'physical') {
							$is_physical = true;
						} ?>
						<div class="item">
							<div class="item-left">
								<div class="img-cart-product">
									<a href="<?php echo generate_product_url($product); ?>">
										<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($cart_item->product_id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
									</a>
								</div>
							</div>
							<div class="item-right">
								<?php if ($product->product_type == 'digital'): ?>
									<div class="list-item">
										<label class="label-instant-download label-instant-download-sm"><i class="icon-download-solid"></i><?php echo trans("instant_download"); ?></label>
									</div>
								<?php endif; ?>
								<div class="list-item">
									<a href="<?php echo generate_product_url($product); ?>">
										<?php echo html_escape($cart_item->product_title); ?>
									</a>
								</div>
								<div class="list-item seller">
									<?php echo trans("by"); ?>&nbsp;<a href="<?php echo lang_base_url() . 'profile' . '/' . $product->user_slug; ?>"><?php echo get_shop_name_product($product); ?></a>
								</div>
								<div class="list-item m-t-15">
									<label><?php echo trans("quantity"); ?>:</label>
									<strong class="lbl-price"><?php echo $cart_item->quantity; ?></strong>
								</div>
								<div class="list-item">
									<label><?php echo trans("price"); ?>:</label>
									<strong class="lbl-price"><?php echo print_price($cart_item->total_price, $cart_item->currency); ?></strong>
								</div>
								<?php if ($product->product_type != 'digital' && $this->form_settings->shipping == 1): ?>
									<div class="list-item">
										<label><?php echo trans("shipping"); ?>:</label>
										<strong><?php echo print_price($cart_item->shipping_cost, $cart_item->currency); ?></strong>
									</div>
								<?php endif; ?>

							</div>
						</div>
					<?php endif;
				endforeach;
			endif; ?>
		</div>
		<p class="m-t-30">
			<strong><?php echo trans("subtotal"); ?><span class="float-right"><?php echo print_price($cart_total->subtotal, $this->payment_settings->default_product_currency); ?></span></strong>
		</p>
		<?php if ($is_physical  && $this->form_settings->shipping == 1): ?>
			<p>
				<?php echo trans("shipping"); ?><span class="float-right"><?php echo print_price($cart_total->shipping_cost, $this->payment_settings->default_product_currency); ?></span>
			</p>
		<?php endif; ?>
		<p class="line-seperator"></p>
		<p>
			<strong><?php echo trans("total"); ?><span class="float-right"><?php echo print_price($cart_total->total, $this->payment_settings->default_product_currency); ?></span></strong>
		</p>

	</div>
</div>


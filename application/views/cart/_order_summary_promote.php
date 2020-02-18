<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="col-sm-12 col-lg-5 order-summary-container">
	<h2 class="cart-section-title"><?php echo trans("order_summary"); ?> (1)</h2>
	<div class="right">
		<?php if (!empty($promoted_plan)):
			$product = get_product($promoted_plan->product_id);
			if (!empty($product)):?>
				<div class="cart-order-details">
					<div class="item">
						<div class="item-left">
							<div class="img-cart-product">
								<a href="<?php echo generate_product_url($product); ?>">
									<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
								</a>
							</div>
						</div>
						<div class="item-right">
							<div class="list-item">
								<a href="<?php echo generate_product_url($product); ?>">
									<?php echo html_escape($product->title); ?>
								</a>
							</div>
						</div>
					</div>
					<div class="item">
						<div class="item-right">
							<div class="list-item m-t-15">
								<label><?php echo trans("promote_plan"); ?>:</label>
								<strong class="lbl-price"><?php echo $promoted_plan->purchased_plan; ?></strong>
							</div>
							<div class="list-item">
								<label><?php echo trans("price"); ?>:</label>
								<strong class="lbl-price"><?php echo print_price($promoted_plan->total_amount, $payment_settings->promoted_products_payment_currency); ?></strong>
							</div>
						</div>
					</div>
				</div>
				<p class="m-t-30">
					<strong><?php echo trans("subtotal"); ?><span class="float-right"><?php echo print_price($promoted_plan->total_amount, $payment_settings->promoted_products_payment_currency); ?></span></strong>
				</p>
				<p class="line-seperator"></p>
				<p>
					<strong><?php echo trans("total"); ?><span class="float-right"><?php echo print_price($promoted_plan->total_amount, $payment_settings->promoted_products_payment_currency); ?></span></strong>
				</p>
			<?php endif;
		endif; ?>
	</div>
</div>





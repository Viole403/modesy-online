<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-item product-item-horizontal">
	<div class="row row-product">
		<div class="col-12 col-sm-4">
			<div class="item-image">
				<a class="item-favorite-button item-favorite-enable <?php echo (is_product_in_favorites($product->id) == true) ? 'item-favorited' : ''; ?>" data-product-id="<?php echo $product->id; ?>"></a>
				<a href="<?php echo generate_product_url($product); ?>">
					<div class="img-product-container">
						<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
					</div>
				</a>
			</div>
		</div>
		<div class="col-12 col-sm-6">
			<div class="row-custom item-details">
				<h3 class="product-title">
					<a href="<?php echo generate_product_url($product); ?>"><?php echo html_escape($product->title); ?></a>
				</h3>
				<?php if ($product->is_promoted && $promoted_products_enabled == 1 && isset($promoted_badge) && $promoted_badge == true): ?>
					<span class="badge badge-dark badge-promoted"><?php echo trans("promoted"); ?>&nbsp;&nbsp;&nbsp;(<?php echo date_difference($product->promote_end_date, date('Y-m-d H:i:s')) . " " . trans("days_left"); ?>)</span>
				<?php endif; ?>
				<p class="product-user text-truncate">
					<a href="<?php echo lang_base_url() . "profile" . '/' . html_escape($product->user_slug); ?>">
						<?php echo get_shop_name_product($product); ?>
					</a>
				</p>
				<!--stars-->
				<?php if ($general_settings->product_reviews == 1) {
					$this->load->view('partials/_review_stars', ['review' => $product->rating]);
				} ?>
				<div class="item-meta">
					<div class="price-product-item-horizontal">
						<?php $this->load->view('product/_price_product_item', ['product' => $product]); ?>
					</div>
					<?php if ($general_settings->product_reviews == 1): ?>
						<span class="item-comments"><i class="icon-comment"></i>&nbsp;<?php echo get_product_comment_count($product->id); ?></span>
					<?php endif; ?>
					<span class="item-favorites"><i class="icon-heart-o"></i>&nbsp;<?php echo get_product_favorited_count($product->id); ?></span>
				</div>
			</div>
			<div class="row-custom m-t-10">
				<?php if (!$product->is_promoted && $promoted_products_enabled == 1): ?>
					<a href="<?php echo lang_base_url() . "promote-product/pricing/" . $product->id; ?>?type=exist" class="btn btn-sm btn-outline-gray btn-profile-option"><i class="icon-plus"></i>&nbsp;<?php echo trans("promote"); ?></a>
				<?php endif; ?>
				<?php if ($product->is_sold == 1): ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="set_product_as_sold(<?php echo $product->id; ?>);"><i class="icon-price-tag"></i>&nbsp;<?php echo trans("set_as_unsold"); ?></a>
				<?php else: ?>
					<a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="set_product_as_sold(<?php echo $product->id; ?>);"><i class="icon-price-tag"></i>&nbsp;<?php echo trans("set_as_sold"); ?></a>
				<?php endif; ?>
				<a href="<?php echo lang_base_url() . "sell-now/edit-product/" . $product->id; ?>" class="btn btn-sm btn-outline-gray btn-profile-option"><i class="icon-edit"></i>&nbsp;<?php echo trans("edit"); ?></a>
				<a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="delete_product(<?php echo $product->id; ?>,'<?php echo trans("confirm_product"); ?>');"><i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
			</div>
		</div>
	</div>
</div>

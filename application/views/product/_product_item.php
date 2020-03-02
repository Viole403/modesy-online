<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="product-item">
	<div class="row-custom">
		<a class="item-favorite-button item-favorite-enable <?php echo (is_product_in_favorites($product->id) == true) ? 'item-favorited' : ''; ?>" data-product-id="<?php echo $product->id; ?>"></a>
		<a href="<?php echo generate_product_url($product); ?>">
			<div class="img-product-container">
				<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
			</div>
		</a>
		<?php if ($product->is_promoted && $promoted_products_enabled == 1 && isset($promoted_badge) && $promoted_badge == true): ?>
			<span class="badge badge-dark badge-promoted"><?php echo trans("promoted"); ?></span>
		<?php endif; ?>
	</div>
	<div class="row-custom item-details">
		<h3 class="product-title">
			<a href="<?php echo generate_product_url($product); ?>"><?php echo html_escape($product->title); ?></a>
		</h3>
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
			<?php $this->load->view('product/_price_product_item', ['product' => $product]); ?>
			<?php if ($general_settings->product_reviews == 1): ?>
				<span class="item-comments"><i class="icon-comment"></i>&nbsp;<?php echo get_product_comment_count($product->id); ?></span>
			<?php endif; ?>
			<span class="item-favorites"><i class="icon-heart-o"></i>&nbsp;<?php echo get_product_favorited_count($product->id); ?></span>
		</div>
	</div>
</div>


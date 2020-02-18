<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="product-item product-item-horizontal">
    <div class="row">
        <div class="col-12 col-sm-4">
            <div class="item-image">
                <a class="item-favorite-button item-favorite-enable <?php echo (is_product_in_favorites($product->id) == true) ? 'item-favorited' : ''; ?>" data-product-id="<?php echo $product->id; ?>"></a>
                <div class="img-product-container">
                    <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-8">
            <div class="row-custom item-details">
                <h3 class="product-title">
                    <?php echo html_escape($product->title); ?>
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
            <div class="row-custom m-t-10">
                <a href="<?php echo lang_base_url() . "sell-now/" . $product->id; ?>" class="btn btn-sm btn-outline-gray btn-profile-option m-r-2"><i class="icon-edit"></i>&nbsp;<?php echo trans("edit"); ?></a>
                <a href="javascript:void(0)" class="btn btn-sm btn-outline-gray btn-profile-option" onclick="delete_draft(<?php echo $product->id; ?>,'<?php echo trans("confirm_product"); ?>');"><i class="icon-times"></i>&nbsp;<?php echo trans("delete"); ?></a>
            </div>
        </div>
    </div>
</div>

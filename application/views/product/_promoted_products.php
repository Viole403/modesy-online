<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="promoted_posts">
    <h3 class="title"><?php echo trans("promoted_products"); ?></h3>
    <p class="title-exp"><?php echo trans("promoted_products_exp"); ?></p>
    <input type="hidden" id="input_promoted_products_count" value="<?php echo $promoted_products_count; ?>">
    <div id="row_promoted_products" class="row row-product">
        <!--print products-->
        <?php
        $count = 1;
        foreach ($promoted_products as $product):
            if ($count <= $promoted_products_limit):?>
                <div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
                    <?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => false]); ?>
                </div>
            <?php endif;
            $count++;
        endforeach; ?>
    </div>
    <div id="load_promoted_spinner" class="col-12 load-more-spinner">
        <div class="row">
            <div class="spinner">
                <div class="bounce1"></div>
                <div class="bounce2"></div>
                <div class="bounce3"></div>
            </div>
        </div>
    </div>
    <?php if ($promoted_products_limit < $promoted_products_count): ?>
        <div class="row-custom text-center">
            <a href="javascript:void(0)" id="btn_load_more_promoted" class="link-see-more"><span><?php echo trans("load_more"); ?>&nbsp;<i class="icon-arrow-down"></i></span></a>
        </div>
    <?php endif; ?>
</div>
<input type="hidden" id="input_promoted_products_per_page" value="<?php echo $promoted_products_limit; ?>">
<input type="hidden" id="input_promoted_products_limit" value="<?php echo $promoted_products_limit; ?>">
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--featured categories-->
<div class="row featured-categories">
    <div class="col">
        <?php $f_cat_1 = get_featured_category(1); ?>
        <?php if (!empty($f_cat_1)): ?>
            <div class="featured-category featured-category-1">
                <div class="inner">
                    <a href="<?php echo generate_category_url($f_cat_1); ?>">
                        <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($f_cat_1, 'image_2'); ?>" alt="<?php echo html_escape($f_cat_1->name); ?>" class="lazyload img-fluid" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                        <div class="caption text-truncate">
                            <span><?php echo html_escape($f_cat_1->name); ?></span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col">
        <?php $f_cat_2 = get_featured_category(2); ?>
        <?php if (!empty($f_cat_2)): ?>
            <div class="featured-category featured-category-2">
                <div class="inner">
                    <a href="<?php echo generate_category_url($f_cat_2); ?>">
                        <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($f_cat_2, 'image_1'); ?>" alt="<?php echo html_escape($f_cat_2->name); ?>" class="lazyload img-fluid" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                        <div class="caption text-truncate">
                            <span><?php echo html_escape($f_cat_2->name); ?></span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php $f_cat_3 = get_featured_category(3); ?>
        <?php if (!empty($f_cat_3)): ?>
            <div class="featured-category featured-category-3">
                <div class="inner">
                    <a href="<?php echo generate_category_url($f_cat_3); ?>">
                        <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($f_cat_3, 'image_1'); ?>" alt="<?php echo html_escape($f_cat_3->name); ?>" class="lazyload img-fluid" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                        <div class="caption text-truncate">
                            <span><?php echo html_escape($f_cat_3->name); ?></span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
    <div class="col">
        <?php $f_cat_4 = get_featured_category(4); ?>
        <?php if (!empty($f_cat_4)): ?>
            <div class="featured-category featured-category-4">
                <div class="inner">
                    <a href="<?php echo generate_category_url($f_cat_4); ?>">
                        <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($f_cat_4, 'image_1'); ?>" alt="<?php echo html_escape($f_cat_4->name); ?>" class="lazyload img-fluid" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
                        <div class="caption text-truncate">
                            <span><?php echo html_escape($f_cat_4->name); ?></span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <?php $f_cat_5 = get_featured_category(5); ?>
        <?php if (!empty($f_cat_5)): ?>
            <div class="featured-category featured-category-5">
                <div class="inner">
                    <a href="<?php echo generate_category_url($f_cat_5); ?>">
                        <img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($f_cat_5, 'image_1'); ?>" alt="<?php echo html_escape($f_cat_5->name); ?>" class="lazyload img-fluid">
                        <div class="caption text-truncate">
                            <span><?php echo html_escape($f_cat_5->name); ?></span>
                        </div>
                    </a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
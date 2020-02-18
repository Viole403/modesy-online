<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<input type="hidden" value="<?php echo $review_limit; ?>" id="product_review_limit">
<?php if (auth_check()): ?>
    <div class="row">
        <div class="col-12">
            <div class="row-custom">
                <div class="rating-bar">
                    <span><?php echo trans("your_rating"); ?></span>
                    <div class="rating-stars">
                        <input type="radio" id="star5" name="rating-star" value="5"/><label class="label-star" data-star="5" for="star5"></label>
                        <input type="radio" id="star4" name="rating-star" value="4"/><label class="label-star" data-star="4" for="star4"></label>
                        <input type="radio" id="star3" name="rating-star" value="3"/><label class="label-star" data-star="3" for="star3"></label>
                        <input type="radio" id="star2" name="rating-star" value="2"/><label class="label-star" data-star="2" for="star2"></label>
                        <input type="radio" id="star1" name="rating-star" value="1"/><label class="label-star" data-star="1" for="star1"></label>
                        <input type="hidden" name="rating" id="user_rating">
                        <input type="hidden" name="product_id" id="review_product_id" value="<?php echo $product->id; ?>">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea name="review" id="user_review" class="form-control form-input form-textarea" placeholder="<?php echo trans("write_review"); ?>" required></textarea>
            </div>
            <button type="submit" id="submit_review" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <div class="row-custom">
                <div class="rating-bar">
                    <span><?php echo trans("your_rating"); ?></span>
                    <div class="rating-stars">
                        <input type="radio" id="star5" name="rating-star" value="5"/><label class="label-star" data-star="5" for="star5"></label>
                        <input type="radio" id="star4" name="rating-star" value="4"/><label class="label-star" data-star="4" for="star4"></label>
                        <input type="radio" id="star3" name="rating-star" value="3"/><label class="label-star" data-star="3" for="star3"></label>
                        <input type="radio" id="star2" name="rating-star" value="2"/><label class="label-star" data-star="2" for="star2"></label>
                        <input type="radio" id="star1" name="rating-star" value="1"/><label class="label-star" data-star="1" for="star1"></label>
                        <input type="hidden" id="user_rating">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <textarea name="comment" class="form-control form-input form-textarea" placeholder="<?php echo trans("write_review"); ?>" required></textarea>
            </div>
            <button type="submit" class="btn btn-md btn-custom" data-toggle="modal" data-target="#loginModal"><?php echo trans("submit"); ?></button>
        </div>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-12">
        <div class="row-custom error-reviewed">
            <p><?php echo trans("review_error"); ?></p>
        </div>
        <div class="row-custom error-own-product">
            <p><?php echo trans("review_own_error"); ?></p>
        </div>
        <div class="reviews">
            <?php if ($review_count > 0): ?>
                <div class="row-custom review-total">
                    <label class="label-review"><?php echo trans("reviews"); ?></label>
                    <!--stars-->
                    <?php $this->load->view('partials/_review_stars', ['review' => $product->rating]); ?>
                    <span>(<?php echo $review_count; ?>)</span>
                </div>
            <?php endif; ?>
            <ul class="review-list">
                <?php foreach ($reviews as $review): ?>
                    <li>
                        <div class="left">
                            <a href="<?php echo lang_base_url(); ?>profile/<?php echo html_escape($review->user_slug); ?>">
                                <img src="<?php echo get_user_avatar_by_id($review->user_id); ?>" alt="<?php echo get_shop_name_by_user_id($review->user_id); ?>">
                            </a>
                        </div>
                        <div class="right">
                            <div class="row-custom">
                                <a href="<?php echo lang_base_url(); ?>profile/<?php echo html_escape($review->user_slug); ?>">
                                    <span class="username"><?php echo get_shop_name_by_user_id($review->user_id); ?></span>
                                </a>
                                <!--stars-->
                                <?php $this->load->view('partials/_review_stars', ['review' => $review->rating]); ?>
                            </div>
                            <div class="row-custom comment">
                                <?php echo html_escape($review->review); ?>
                            </div>
                            <div class="row-custom">
                                <span class="date"><?php echo time_ago($review->created_at); ?></span>
                                <?php if (auth_check()):
                                    if (user()->role == "admin" || $review->user_id == user()->id): ?>
                                        <a href="javascript:void(0)" class="btn-delete-comment" onclick="delete_review('<?php echo $review->id; ?>','<?php echo $product->id; ?>','<?php echo $review->user_id; ?>','<?php echo trans("confirm_review"); ?>');">&nbsp;<i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
                                    <?php endif;
                                endif; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <?php if ($review_count > $review_limit): ?>
        <div id="load_review_spinner" class="col-12 load-more-spinner">
            <div class="row">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="row">
                <button type="button" class="btn-load-more" onclick="load_more_review('<?php echo $product->id; ?>');">
                    <?php echo trans("load_more"); ?>
                </button>
            </div>
        </div>
    <?php endif; ?>
</div>
<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (auth_check()): ?>
    <div class="sub-comment-form-registered">
        <div class="row">
            <div class="col-12">
                <form id="make_subcomment_registered_<?php echo $parent_comment->product_id; ?>">
                    <div class="form-group">
                        <textarea name="comment" class="form-control form-input form-textarea form-comment-text" placeholder="<?php echo trans("comment"); ?>"></textarea>
                    </div>
                    <input type="hidden" name="parent_id" value="<?php echo $parent_comment->id; ?>">
                    <input type="hidden" name="user_id" value="<?php echo user()->id; ?>">
                    <input type="hidden" name="product_id" value="<?php echo $parent_comment->product_id; ?>">
                    <input type="hidden" name="name" value="">
                    <input type="hidden" name="email" value="">
                    <input type="hidden" name="limit" value="<?php echo $comment_limit; ?>">
                    <button type="button" class="btn btn-md btn-custom btn-subcomment-registered" data-comment-id="<?php echo $parent_comment->product_id; ?>"><?php echo trans("submit"); ?></button>
                </form>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="sub-comment-form">
        <div class="row">
            <div class="col-12">
                <form id="make_subcomment_<?php echo $parent_comment->product_id; ?>">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label><?php echo trans("name"); ?></label>
                            <input type="text" name="name" class="form-control form-input form-comment-name" placeholder="<?php echo trans("name"); ?>">
                        </div>
                        <div class="form-group col-md-6">
                            <label><?php echo trans("email_address"); ?></label>
                            <input type="email" name="email" class="form-control form-input form-comment-email" placeholder="<?php echo trans("email_address"); ?>">
                        </div>
                    </div>
                    <div class="form-group">
                        <label><?php echo trans("comment"); ?></label>
                        <textarea name="comment" class="form-control form-input form-textarea form-comment-text" placeholder="<?php echo trans("comment"); ?>"></textarea>
                    </div>
                    <?php generate_recaptcha(); ?>
                    <input type="hidden" name="limit" value="<?php echo $comment_limit; ?>">
                    <input type="hidden" name="parent_id" value="<?php echo $parent_comment->id; ?>">
                    <input type="hidden" name="user_id" value="0">
                    <input type="hidden" name="product_id" value="<?php echo $parent_comment->product_id; ?>">
                    <button type="button" class="btn btn-md btn-custom btn-subcomment" data-comment-id="<?php echo $parent_comment->product_id; ?>"><?php echo trans("submit"); ?></button>
                </form>
            </div>
        </div>
    </div>
<?php endif; ?>



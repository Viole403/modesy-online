<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<input type="hidden" value="<?php echo $comment_limit; ?>" id="product_comment_limit">
<div class="row">
    <div class="col-12">
        <div class="comments">
            <?php if ($comment_count > 0): ?>
                <div class="row-custom comment-total">
                    <label class="label-comment"><?php echo trans("comments"); ?></label>
                    <span>(<?php echo $comment_count; ?>)</span>
                </div>
            <?php endif; ?>
            <ul class="comment-list">
                <?php foreach ($comments as $comment): ?>
                    <li>
                        <div class="left">
                            <img src="<?php echo get_user_avatar_by_id($comment->user_id); ?>" alt="<?php echo html_escape($comment->name); ?>">
                        </div>
                        <div class="right">
                            <div class="row-custom">
                                <span class="username">
                                    <?php if (!empty($comment->user_id)) {
                                        echo get_shop_name_by_user_id($comment->user_id);
                                    } else {
                                        echo html_escape($comment->name);
                                    } ?>
                                </span>
                            </div>
                            <div class="row-custom comment">
                                <?php echo html_escape($comment->comment); ?>
                            </div>
                            <div class="row-custom">
                                <span class="date"><?php echo time_ago($comment->created_at); ?></span>
                                <a href="javascript:void(0)" class="btn-reply" onclick="show_comment_box('<?php echo $comment->id; ?>');"><i class="icon-reply"></i> <?php echo trans('reply'); ?></a>
                                <?php if (auth_check()):
                                    if ($comment->user_id == user()->id || user()->role == "admin"): ?>
                                        <a href="javascript:void(0)" class="btn-delete-comment" onclick="delete_comment('<?php echo $comment->id; ?>','<?php echo $product->id; ?>','<?php echo trans("confirm_comment"); ?>');">&nbsp;<i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
                                    <?php endif;
                                endif; ?>
                            </div>
                            <div id="sub_comment_form_<?php echo $comment->id; ?>" class="row-custom row-sub-comment visible-sub-comment">

                            </div>
                            <div class="row-custom row-sub-comment">
                                <!-- include subcomments -->
                                <?php $this->load->view('product/details/_subcomments', ['parent_comment' => $comment]); ?>
                            </div>

                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>
    <?php if ($comment_count > $comment_limit): ?>
        <div id="load_comment_spinner" class="col-12 load-more-spinner">
            <div class="row">
                <div class="spinner">
                    <div class="bounce1"></div>
                    <div class="bounce2"></div>
                    <div class="bounce3"></div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <button type="button" class="btn-load-more" onclick="load_more_comment('<?php echo $product->id; ?>');">
                <?php echo trans("load_more"); ?>
            </button>
        </div>
    <?php endif; ?>
</div>

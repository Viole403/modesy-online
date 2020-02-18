<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $subcomments = get_subcomments($parent_comment->id); ?>
<?php if (!empty($subcomments)): ?>
    <div class="row">
        <div class="col-12">
            <div class="comments">
                <ul class="comment-list">
                    <?php foreach ($subcomments as $subcomment): ?>
                        <li>
                            <div class="left">
                                <img src="<?php echo get_user_avatar_by_id($subcomment->user_id); ?>" alt="<?php echo html_escape($subcomment->name); ?>">
                            </div>
                            <div class="right">
                                <div class="row-custom">
                                    <span class="username">
                                        <?php if (!empty($subcomment->user_id)) {
                                            echo get_shop_name_by_user_id($subcomment->user_id);
                                        } else {
                                            echo html_escape($subcomment->name);
                                        } ?>
                                    </span>
                                </div>
                                <div class="row-custom comment">
                                    <?php echo html_escape($subcomment->comment); ?>
                                </div>
                                <div class="row-custom">
                                    <span class="date"><?php echo time_ago($subcomment->created_at); ?></span>
                                    <?php if (auth_check()):
                                        if ($subcomment->user_id == user()->id || user()->role == "admin"): ?>
                                            <a href="javascript:void(0)" class="btn-delete-comment" onclick="delete_comment('<?php echo $subcomment->id; ?>','<?php echo $subcomment->product_id; ?>','<?php echo trans("confirm_comment"); ?>');">&nbsp;<i class="icon-trash"></i>&nbsp;<?php echo trans("delete"); ?></a>
                                        <?php endif;
                                    endif; ?>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
<?php endif; ?>

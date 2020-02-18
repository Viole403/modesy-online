<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (auth_check()): ?>
    <div class="row">
        <div class="col-12">
            <form id="make_blog_comment_registered">
                <input type="hidden" name="user_id" value="<?php echo user()->id; ?>">
                <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                <input type="hidden" name="name" id="comment_name" value="">
                <input type="hidden" name="email" id="comment_email" value="">
                <div class="form-group">
                    <textarea name="comment" id="comment_text" class="form-control form-input form-textarea" placeholder="<?php echo trans("comment"); ?>"></textarea>
                </div>
                <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
            </form>
        </div>

        <div class="col-12">
            <div id="comment-result">
                <!--include comments-->
                <?php $this->load->view("blog/_blog_comments", ["comment_post_id" => $post->id]); ?>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-12">
            <form id="make_blog_comment">
                <input type="hidden" name="user_id" value="0">
                <input type="hidden" name="post_id" value="<?php echo $post->id; ?>">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label><?php echo trans("name"); ?></label>
                        <input type="text" name="name" id="comment_name" class="form-control form-input" placeholder="<?php echo trans("name"); ?>">
                    </div>
                    <div class="form-group col-md-6">
                        <label><?php echo trans("email_address"); ?></label>
                        <input type="email" name="email" id="comment_email" class="form-control form-input" placeholder="<?php echo trans("email_address"); ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label><?php echo trans("comment"); ?></label>
                    <textarea name="comment" id="comment_text" class="form-control form-input form-textarea" placeholder="<?php echo trans("comment"); ?>"></textarea>
                </div>
                <?php generate_recaptcha(); ?>
                <button type="submit" class="btn btn-md btn-custom"><?php echo trans("submit"); ?></button>
            </form>
        </div>

        <div class="col-12">
            <div id="comment-result">
                <!--include comments-->
                <?php $this->load->view("blog/_blog_comments", ["comment_post_id" => $post->id]); ?>
            </div>
        </div>
    </div>
<?php endif; ?>

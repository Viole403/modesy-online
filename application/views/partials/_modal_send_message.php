<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Send Message Modal -->
<?php if (auth_check()): ?>
    <div class="modal fade" id="messageModal" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-dialog-centered modal-send-message" role="document">

            <div class="modal-content">
                <!-- form start -->
                <form id="form_send_message" novalidate="novalidate">
                    <input type="hidden" name="sender_id" id="message_sender_id" value="<?php echo $this->auth_user->id; ?>">
                    <input type="hidden" name="receiver_id" id="message_receiver_id" value="<?php echo $user->id; ?>">
                    <input type="hidden" id="message_send_em" value="<?php echo $user->send_email_new_message; ?>">

                    <div class="modal-header">
                        <h4 class="title"><?php echo trans("send_message"); ?></h4>
                        <button type="button" class="close" data-dismiss="modal"><i class="icon-close"></i></button>
                    </div>

                    <!-- Modal body -->
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-12">
                                <div id="send-message-result"></div>
                                <div class="form-group m-b-sm-0">
                                    <div class="row justify-content-center m-0">
                                        <div class="user-contact-modal text-center">
                                            <img src="<?php echo get_user_avatar($user); ?>" alt="<?php echo get_shop_name($user); ?>">
                                            <p><?php echo get_shop_name($user); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label"><?php echo trans("subject"); ?></label>
                                    <input type="text" name="subject" id="message_subject" value="<?php echo (!empty($subject)) ? html_escape($subject) : ''; ?>" class="form-control form-input" placeholder="<?php echo trans("subject"); ?>" required>
                                </div>
                                <div class="form-group m-b-sm-0">
                                    <label class="control-label"><?php echo trans("message"); ?></label>
                                    <textarea name="message" id="message_text" class="form-control form-textarea" placeholder="<?php echo trans("write_a_message"); ?>" required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-md btn-red" data-dismiss="modal"><i class="icon-times"></i>&nbsp;<?php echo trans("close"); ?></button>
                        <button type="submit" class="btn btn-md btn-custom"><i class="icon-send"></i>&nbsp;<?php echo trans("send"); ?></button>
                    </div>
                </form>
                <!-- form end -->
            </div>

        </div>
    </div>
<?php endif; ?>

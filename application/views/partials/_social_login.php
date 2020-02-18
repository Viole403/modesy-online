<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php if (!empty($general_settings->facebook_app_id)): ?>
    <a href="<?php echo base_url(); ?>connect-with-facebook" class="btn btn-social btn-social-facebook">
        <i class="icon-facebook"></i>&nbsp;<?php echo trans("connect_with_facebook"); ?>
    </a>
<?php endif; ?>
<?php if (!empty($general_settings->google_client_id)): ?>
    <a href="<?php echo base_url(); ?>connect-with-google" class="btn btn-social btn-social-google">
        <i class="icon-google"></i>&nbsp;<?php echo trans("connect_with_google"); ?>
    </a>
<?php endif; ?>
<?php if (!empty($general_settings->facebook_app_id) || !empty($general_settings->google_client_id) || !empty($general_settings->twitter_api_key)): ?>
    <div class="form-group">
        <p class="p-social-media"><?php echo $or_text; ?></p>
    </div>
<?php endif; ?>
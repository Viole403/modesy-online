<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans("edit_product_condition"); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('settings_controller/edit_product_condition_post'); ?>
            <input type="hidden" name="common_id" value="<?php echo $main_option->common_id; ?>">
            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages_form'); ?>
                <?php foreach ($languages as $language):
                    $option = $this->settings_model->get_shipping_option_by_lang($main_option->common_id, $language->id); ?>
                    <div class="form-group">
                        <label><?php echo trans("option_label"); ?> (<?php echo $language->name; ?>)</label>
                        <input type="text" class="form-control" name="option_label_<?php echo $language->id; ?>" placeholder="<?php echo trans("option_label"); ?>" value="<?php echo html_escape(@$option->option_label); ?>" maxlength="255" required>
                    </div>
                <?php endforeach; ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-sm-4 col-xs-12">
                            <label><?php echo trans('visible'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="is_visible" value="1" id="visible_1" class="square-purple" <?php echo ($main_option->is_visible == 1) ? 'checked' : ''; ?>>
                            <label for="visible_1" class="option-label"><?php echo trans('yes'); ?></label>
                        </div>
                        <div class="col-sm-4 col-xs-12 col-option">
                            <input type="radio" name="is_visible" value="0" id="visible_2" class="square-purple" <?php echo ($main_option->is_visible != 1) ? 'checked' : ''; ?>>
                            <label for="visible_2" class="option-label"><?php echo trans('no'); ?></label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <a href="<?php echo admin_url(); ?>form-settings/product-conditions" class="btn btn-danger"><?php echo trans("back"); ?></a>
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>

        </div>
        <!-- /.box-footer -->
        <?php echo form_close(); ?><!-- form end -->
    </div>
    <!-- /.box -->
</div>

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-5 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('location_settings'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open('admin_controller/location_settings_post'); ?>

            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>

                <div class="form-group">
                    <div class="row">
                        <div class="col-xs-12 m-b-10">
                            <label><?php echo trans("product_location"); ?></label>
                        </div>
                        <div class="col-xs-12 col-option m-b-10">
                            <input type="radio" name="default_product_location" value="0" id="location_1"
                                   class="square-purple" <?php echo ($general_settings->default_product_location == 0) ? 'checked' : ''; ?>>
                            <label for="location_1" class="option-label"><?php echo trans("allow_from_all_countries"); ?></label>
                        </div>
                        <div class="col-xs-12 col-option m-b-10">
                            <input type="radio" name="default_product_location" value="1" id="location_2"
                                   class="square-purple" <?php echo ($general_settings->default_product_location != 0) ? 'checked' : ''; ?>>
                            <label for="location_2" class="option-label"><?php echo trans("allow_from_one_country"); ?></label>
                        </div>
                    </div>
                </div>

                <div id="location_countries" class="form-group">
                    <label><?php echo trans('country'); ?></label>
                    <select name="country_id" class="form-control">
                        <option value=""><?php echo trans("select"); ?></option>
                        <?php
                        foreach ($countries as $item): ?>
                            <option value="<?php echo $item->id; ?>" <?php echo ($general_settings->default_product_location == $item->id) ? 'selected' : ''; ?>>
                                <?php echo html_escape($item->name); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <!-- /.box -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
    </div>
</div>

<?php if ($general_settings->default_product_location != 0): ?>
    <style>
        #location_countries {
            display: block !important;
        }
    </style>
<?php endif; ?>

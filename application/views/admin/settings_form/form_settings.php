<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row">
    <div class="col-lg-4 col-md-12">
        <?php echo form_open('settings_controller/form_settings_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('form_settings'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("msg_form_settings"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('product_conditions'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="product_conditions" id="product_conditions" value="1" class="square-purple" <?php echo ($form_settings->product_conditions == 1) ? 'checked' : ''; ?>>
                            <label for="product_conditions" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="product_conditions_required" id="product_conditions_required" value="1" class="square-purple" <?php echo ($form_settings->product_conditions_required == 1) ? 'checked' : ''; ?>>
                            <label for="product_conditions_required" class="control-label cursor-pointer"><?php echo trans('required'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('quantity'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="quantity" id="quantity" value="1" class="square-purple" <?php echo ($form_settings->quantity == 1) ? 'checked' : ''; ?>>
                            <label for="quantity" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="quantity_required" id="quantity_required" value="1" class="square-purple" <?php echo ($form_settings->quantity_required == 1) ? 'checked' : ''; ?>>
                            <label for="quantity_required" class="control-label cursor-pointer"><?php echo trans('required'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('price') . ' (' . trans("only_for_ordinary_listing") . ') '; ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="price" id="price" value="1" class="square-purple" <?php echo ($form_settings->price == 1) ? 'checked' : ''; ?>>
                            <label for="price" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="price_required" id="price_required" value="1" class="square-purple" <?php echo ($form_settings->price_required == 1) ? 'checked' : ''; ?>>
                            <label for="price_required" class="control-label cursor-pointer"><?php echo trans('required'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('variations'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="variations" id="variations" value="1" class="square-purple" <?php echo ($form_settings->variations == 1) ? 'checked' : ''; ?>>
                            <label for="variations" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('shipping'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="shipping" id="shipping" value="1" class="square-purple" <?php echo ($form_settings->shipping == 1) ? 'checked' : ''; ?>>
                            <label for="shipping" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="shipping_required" id="shipping_required" value="1" class="square-purple" <?php echo ($form_settings->shipping_required == 1) ? 'checked' : ''; ?>>
                            <label for="shipping_required" class="control-label cursor-pointer"><?php echo trans('required'); ?></label>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('product_location'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="product_location" id="product_location" value="1" class="square-purple" <?php echo ($form_settings->product_location == 1) ? 'checked' : ''; ?>>
                            <label for="product_location" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="product_location_required" id="product_location_required" value="1" class="square-purple" <?php echo ($form_settings->product_location_required == 1) ? 'checked' : ''; ?>>
                            <label for="product_location_required" class="control-label cursor-pointer"><?php echo trans('required'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('external_link') . ' (' . trans("only_for_ordinary_listing") . ') '; ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="external_link" id="external_link" value="1" class="square-purple" <?php echo ($form_settings->external_link == 1) ? 'checked' : ''; ?>>
                            <label for="external_link" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?><!-- form end -->
    </div>
    <div class="col-lg-4 col-md-12">
        <?php echo form_open('settings_controller/physical_products_form_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('physical_products'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("msg_physical"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('demo_url'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="physical_demo_url" id="physical_demo_url" value="1" class="square-purple" <?php echo ($form_settings->physical_demo_url == 1) ? 'checked' : ''; ?>>
                            <label for="physical_demo_url" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('video_preview'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="physical_video_preview" id="physical_video_preview" value="1" class="square-purple" <?php echo ($form_settings->physical_video_preview == 1) ? 'checked' : ''; ?>>
                            <label for="physical_video_preview" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('audio_preview'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="physical_audio_preview" id="physical_audio_preview" value="1" class="square-purple" <?php echo ($form_settings->physical_audio_preview == 1) ? 'checked' : ''; ?>>
                            <label for="physical_audio_preview" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?><!-- form end -->

        <?php echo form_open('settings_controller/file_upload_form_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('file_upload'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("msg_file_upload"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('max_file_size') . ' (' . trans("image") . ' )'; ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="number" name="max_file_size_image" value="<?php echo round(($general_settings->max_file_size_image / 1048576), 2); ?>" min="1" style="padding: 2px 4px;border:1px solid #ced4da;">&nbsp;<span>MB</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('max_file_size') . ' (' . trans("video") . ' )'; ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="number" name="max_file_size_video" value="<?php echo round(($general_settings->max_file_size_video / 1048576), 2); ?>" min="1" style="padding: 2px 4px;border:1px solid #ced4da;">&nbsp;<span>MB</span>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('max_file_size') . ' (' . trans("audio") . ' )'; ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="number" name="max_file_size_audio" value="<?php echo round(($general_settings->max_file_size_audio / 1048576), 2); ?>" min="1" style="padding: 2px 4px;border:1px solid #ced4da;">&nbsp;<span>MB</span>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?><!-- form end -->
    </div>
    <div class="col-lg-4 col-md-12">
        <?php echo form_open('settings_controller/digital_products_form_post'); ?>
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('digital_products'); ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <!-- include message block -->
                <?php if (!empty($this->session->flashdata("msg_digital"))):
                    $this->load->view('admin/includes/_messages');
                endif; ?>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('demo_url'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="digital_demo_url" id="digital_demo_url" value="1" class="square-purple" <?php echo ($form_settings->digital_demo_url == 1) ? 'checked' : ''; ?>>
                            <label for="digital_demo_url" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('video_preview'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="digital_video_preview" id="digital_video_preview" value="1" class="square-purple" <?php echo ($form_settings->digital_video_preview == 1) ? 'checked' : ''; ?>>
                            <label for="digital_video_preview" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('audio_preview'); ?></label>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="checkbox" name="digital_audio_preview" id="digital_audio_preview" value="1" class="square-purple" <?php echo ($form_settings->digital_audio_preview == 1) ? 'checked' : ''; ?>>
                            <label for="digital_audio_preview" class="control-label cursor-pointer"><?php echo trans('enable'); ?></label>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" name="submit" value="verification" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
        </div>
        <?php echo form_close(); ?><!-- form end -->
    </div>
</div>

<style>
    .col-sm-12 label {
        margin-left: 10px;
		font-weight: 400 !important;
		-webkit-touch-callout: none;
		-webkit-user-select: none;
		-khtml-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
    }
</style>

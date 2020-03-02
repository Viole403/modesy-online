<div class="row">
    <div class="col-lg-6 col-md-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo trans('update_slider_item'); ?></h3>
            </div>
            <!-- /.box-header -->

            <!-- form start -->
            <?php echo form_open_multipart('admin_controller/update_slider_item_post'); ?>
            <input type="hidden" name="id" value="<?php echo $item->id; ?>">
            <div class="box-body">
                <!-- include message block -->
                <?php $this->load->view('admin/includes/_messages'); ?>
                <div class="form-group">
                    <label><?php echo trans("language"); ?></label>
                    <select name="lang_id" class="form-control">
                        <?php foreach ($languages as $language): ?>
                            <option value="<?php echo $language->id; ?>" <?php echo ($language->id == $item->lang_id) ? 'selected' : ''; ?>><?php echo $language->name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="control-label"><?php echo trans('link'); ?></label>
                    <input type="text" class="form-control" name="link" placeholder="<?php echo trans('link'); ?>"
                           value="<?php echo html_escape($item->link); ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('order'); ?></label>
                    <input type="number" class="form-control" name="item_order" placeholder="<?php echo trans('order'); ?>"
                           value="<?php echo $item->item_order; ?>" <?php echo ($rtl == true) ? 'dir="rtl"' : ''; ?>>
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('image'); ?> (1170x356px)</label>
                    <div class="display-block m-b-15">
                        <img src="<?php echo base_url() . $item->image; ?>" alt="" class="img-responsive" style="max-width: 300px; max-height: 300px;">
                    </div>
                    <div class="display-block">
                        <a class='btn btn-success btn-sm btn-file-upload'>
                            <?php echo trans('select_image'); ?>
                            <input type="file" name="file" accept=".png, .jpg, .jpeg, .gif" onchange="show_preview_image(this);">
                        </a>
                    </div>
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" id="img_preview_file" class="img-file-upload-preview">
                </div>

                <div class="form-group">
                    <label class="control-label"><?php echo trans('image'); ?> (768x380px)</label>
                    <div class="display-block m-b-15">
                        <img src="<?php echo base_url() . $item->image_small; ?>" alt="" class="img-responsive" style="max-width: 300px; max-height: 300px;">
                    </div>
                    <div class="display-block">
                        <a class='btn btn-success btn-sm btn-file-upload'>
                            <?php echo trans('select_image'); ?>
                            <input type="file" name="file_small" accept=".png, .jpg, .jpeg, .gif" onchange="show_preview_image(this);">
                        </a>
                    </div>
                    <img src="data:image/gif;base64,R0lGODlhAQABAIAAAP///wAAACH5BAEAAAAALAAAAAABAAEAAAICRAEAOw==" id="img_preview_file_small" class="img-file-upload-preview">
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <button type="submit" class="btn btn-primary pull-right"><?php echo trans('save_changes'); ?></button>
            </div>
            <!-- /.box-footer -->
            <?php echo form_close(); ?><!-- form end -->
        </div>
        <!-- /.box -->
    </div>
</div>


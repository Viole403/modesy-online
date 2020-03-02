<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if (!empty($product)):
    $digital_file = $this->file_model->get_product_digital_file($product->id);
    if (!empty($digital_file)):?>
        <?php echo form_open('file_controller/download_digital_file', ['id' => 'form_download_digital_file']); ?>
        <input type="hidden" name="file_id" value="<?php echo $digital_file->id; ?>">
        <div class="dm-uploaded-digital-file">
            <a href="javascript:void(0)" class="float-left button-link-style" onclick="$('#form_download_digital_file').submit();">
                <i class="icon-file-archive file-icon"></i>&nbsp;&nbsp;<strong><?php echo $digital_file->file_name; ?></strong>
            </a>
            <a href="javascript:void(0)" class="btn btn-sm btn-secondary color-white float-right" onclick="delete_product_digital_file('<?php echo $product->id; ?>','<?php echo trans("confirm_delete") ?>');">
                <i class="icon-trash"></i><?php echo trans("delete"); ?>
            </a>
            <button type="submit" class="btn btn-sm btn-info color-white float-right m-r-5">
                <i class="icon-download-solid"></i><?php echo trans("download"); ?>
            </button>
        </div>
        <?php echo form_close(); ?>
    <?php else: ?>
        <div class="dm-uploader-container">
            <div id="drag-and-drop-zone-digital-files" class="dm-uploader dm-uploader-media text-center">
                <p class="dm-upload-icon">
                    <i class="icon-upload"></i>
                </p>
                <p class="dm-upload-text"><?php echo trans("drag_drop_file_here"); ?>&nbsp;<span style="text-decoration: underline"><?php echo trans('browse_files'); ?></span></p>
                <a class='btn btn-md dm-btn-select-files'>
                    <input type="file" name="file">
                </a>
                <ul class="dm-uploaded-files dm-uploaded-media-file" id="files-digital-files"></ul>
                <div class="error-message error-message-img-upload">
                    <p class="m-b-5 text-center"></p>
                </div>
            </div>
        </div>
    <?php endif; ?>
<?php endif; ?>

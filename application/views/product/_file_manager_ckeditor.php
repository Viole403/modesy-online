<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="modal fade" id="ckFileManagerModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-file-manager" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?php echo trans("images"); ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true"><i class="icon-close"></i></span>
                </button>
            </div>
            <div class="modal-body">
                <div class="file-manager">
                    <div class="file-manager-left">
                        <?php echo form_open_multipart('file_controller/upload_file_manager_images', ['id' => 'form_image_ck_file_manager']); ?>
                        <div class="row">
                            <div class="col-sm-12">
                                <a id="btn_ckimg_upload" class='btn btn-file-upload btn-secondary btn-file-manager'>
                                    <i class="icon-image"></i>
                                    <?php echo trans('add_image'); ?>
                                    <input type="file" id="ckMultifileupload" name="files[]" class="upload-file-input img_ck_file_manager_input" accept=".png, .jpg, .jpeg, .gif" multiple="multiple">
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div id="ckMultidvPreview" class="multi-preview-container"></div>
                            </div>
                            <div class="col-sm-12">
                                <button type="button" id="btn_ck_file_manager_image_uplaod" class="btn btn-file-upload btn-secondary btn-file-manager btn-file-manager-image-upload">
                                    <i class="icon-upload-solid"></i>
                                    <?php echo trans('upload'); ?>
                                </button>
                            </div>
                        </div>
                        <?php echo form_close(); ?>
                        <div class="row">
                            <div class="col-sm-12 m-t-5">
                                <div class="loader-file-manager">
                                    <div class="sk-fading-circle">
                                        <div class="sk-circle1 sk-circle"></div>
                                        <div class="sk-circle2 sk-circle"></div>
                                        <div class="sk-circle3 sk-circle"></div>
                                        <div class="sk-circle4 sk-circle"></div>
                                        <div class="sk-circle5 sk-circle"></div>
                                        <div class="sk-circle6 sk-circle"></div>
                                        <div class="sk-circle7 sk-circle"></div>
                                        <div class="sk-circle8 sk-circle"></div>
                                        <div class="sk-circle9 sk-circle"></div>
                                        <div class="sk-circle10 sk-circle"></div>
                                        <div class="sk-circle11 sk-circle"></div>
                                        <div class="sk-circle12 sk-circle"></div>
                                    </div>
                                    <p class="file-manager-uploading-text"><?php echo trans("uploading"); ?></p>
                                </div>
                            </div>
                        </div>
                        <p class="file-manager-upload-error"></p>

                    </div>

                    <div class="file-manager-right">
                        <div class="file-manager-content">
                            <div id="ckimage_file_upload_response">
                                <?php foreach ($file_manager_images as $image): ?>
                                    <div class="col-file-manager" id="ckimg_col_id_<?php echo $image->id; ?>">
                                        <div class="file-box" data-file-id="<?php echo $image->id; ?>" data-file-path="<?php echo get_file_manager_image($image); ?>">
                                            <div class="image-container">
                                                <img src="<?php echo get_file_manager_image($image); ?>" alt="" class="img-responsive">
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <input type="hidden" id="selected_ckimg_file_id">
                    <input type="hidden" id="selected_ckimg_file_path">

                </div>
            </div>
            <div class="modal-footer">
                <div class="file-manager-footer">
                    <button type="button" id="btn_ckimg_delete" class="btn btn-sm btn-danger pull-left btn-file-delete color-white"><?php echo trans('delete'); ?></button>
                    <button type="button" id="btn_ckimg_select" class="btn btn-sm btn-success color-white btn-file-select"><?php echo trans('select_image'); ?></button>
                    <button type="button" class="btn btn-sm btn-secondary color-white" data-dismiss="modal"><?php echo trans('close'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>
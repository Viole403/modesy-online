<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="digital_files_upload_result">
    <?php $this->load->view('product/_digital_files_upload_response'); ?>
</div>

<!-- File item template -->
<script type="text/html" id="files-template-digital-files">
    <li class="media">
        <div class="media-body">
            <div class="progress">
                <div class="progress-bar" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
        </div>
    </li>
</script>

<script>
    $('#drag-and-drop-zone-digital-files').dmUploader({
        url: '<?php echo base_url(); ?>file_controller/upload_digital_files',
        queue: true,
        extFilter: ["zip"],
        multiple: false,
        extraData: {
            "product_id":<?php echo $product->id; ?>
        },
        onDragEnter: function () {
            this.addClass('active');
        },
        onDragLeave: function () {
            this.removeClass('active');
        },
        onInit: function () {
        },
        onComplete: function (id) {
        },
        onNewFile: function (id, file) {
            ui_multi_add_file(id, file, "digital-files");
        },
        onBeforeUpload: function (id) {
            ui_multi_update_file_progress(id, 0, '', true);
            ui_multi_update_file_status(id, 'uploading', 'Uploading...');
        },
        onUploadProgress: function (id, percent) {
            ui_multi_update_file_progress(id, percent);
        },
        onUploadSuccess: function (id, data) {
            document.getElementById("digital_files_upload_result").innerHTML = data;
        },
        onUploadError: function (id, xhr, status, message) {
        },
        onFallbackMode: function () {
        },
        onFileSizeError: function (file) {
        },
        onFileTypeError: function (file) {
        },
        onFileExtError: function (file) {
        },
    });
    $(document).ajaxStop(function () {
        $('#drag-and-drop-zone-digital-files').dmUploader({
            url: '<?php echo base_url(); ?>file_controller/upload_digital_files',
            queue: true,
            extFilter: ["zip"],
            multiple: false,
            extraData: {
                "product_id":<?php echo $product->id; ?>
            },
            onDragEnter: function () {
                this.addClass('active');
            },
            onDragLeave: function () {
                this.removeClass('active');
            },
            onInit: function () {
            },
            onComplete: function (id) {
            },
            onNewFile: function (id, file) {
                ui_multi_add_file(id, file, "digital-files");
            },
            onBeforeUpload: function (id) {
                ui_multi_update_file_progress(id, 0, '', true);
                ui_multi_update_file_status(id, 'uploading', 'Uploading...');
            },
            onUploadProgress: function (id, percent) {
                ui_multi_update_file_progress(id, percent);
            },
            onUploadSuccess: function (id, data) {
                document.getElementById("digital_files_upload_result").innerHTML = data;
            },
            onUploadError: function (id, xhr, status, message) {
            },
            onFallbackMode: function () {
            },
            onFileSizeError: function (file) {
            },
            onFileTypeError: function (file) {
            },
            onFileExtError: function (file) {
            },
        });
    });
</script>


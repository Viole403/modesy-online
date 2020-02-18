/**
 ******************************************************************************************************
 * CKEditor File Manager
 *******************************************************************************************************
 */
$(document).on('click', '#ckFileManagerModal .file-box', function () {
    $('.file-manager .file-box').removeClass('selected');
    $(this).addClass('selected');
    var val_id = $(this).attr('data-file-id');
    var val_path = $(this).attr('data-file-path');
    $('#selected_ckimg_file_id').val(val_id);
    $('#selected_ckimg_file_path').val(val_path);

    $('#btn_ckimg_delete').show();
    $('#btn_ckimg_select').show();
});

$(document).on('change', '.img_ck_file_manager_input', function () {
    setTimeout(
        function () {
            $('#btn_ck_file_manager_image_uplaod').show();
        }, 200);
});

$(document).on('click', '#btn_ck_file_manager_image_uplaod', function () {
    $("#form_image_ck_file_manager").ajaxSubmit({
        beforeSubmit: function (inputs, $form, options) {
            inputs.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            $("#btn_ck_file_manager_image_uplaod").hide();
            $('.loader-file-manager').show();
            $("#btn_ckimg_upload").attr("disabled", true);
            $(".img_ck_file_manager_input").attr("disabled", true);
        },
        complete: function () {
            $('.loader-file-manager').hide();
            $('#ckMultidvPreview').empty();
            $("#btn_ckimg_upload").attr("disabled", false);
            $(".img_ck_file_manager_input").attr("disabled", false);
            $(".img_ck_file_manager_input").val('');
            $('#btn_img_delete').hide();
            $('#btn_img_select').hide();
            refresh_ck_images();
        },
        error: function (response) {
            if (response.responseText.indexOf("Maximum execution time") >= 0) {
                $(".file-manager-upload-error").html("<br><b>Fatal error:</b> Maximum execution time of 10 seconds exceeded. Please increase max_execution_time value from PHP settings.");
            } else {
                $(".file-manager-upload-error").html(response.responseText);
            }
            $('#btn_ckimg_delete').hide();
            $('#btn_ckimg_select').hide();
        }
    });
});

//refresh ck images
function refresh_ck_images() {
    var data = {};
    data[csfr_token_name] = $.cookie(csfr_cookie_name);
    $.ajax({
        type: "POST",
        url: base_url + "file_controller/get_file_manager_images",
        data: data,
        success: function (response) {
            document.getElementById("ckimage_file_upload_response").innerHTML = response;
        }
    });
}

//select image file
$(document).on('click', '#ckFileManagerModal #btn_ckimg_select', function () {
    var imgUrl = $('#selected_ckimg_file_path').val();
    window.parent.CKEDITOR.tools.callFunction('1', imgUrl);
    $('#ckFileManagerModal').modal('toggle');
});

//select image file on double click
$(document).on('dblclick', '#ckFileManagerModal .file-box', function () {
    var imgUrl = $('#selected_ckimg_file_path').val();

    window.parent.CKEDITOR.tools.callFunction('1', imgUrl);
    $('#ckFileManagerModal').modal('toggle');
});

//delete image file
$(document).on('click', '#ckFileManagerModal #btn_ckimg_delete', function () {

    var file_id = $('#selected_ckimg_file_id').val();

    $('#ckimg_col_id_' + file_id).remove();

    var data = {
        "file_id": file_id
    };
    data[csfr_token_name] = $.cookie(csfr_cookie_name);

    $.ajax({
        type: "POST",
        url: base_url + "file_controller/delete_file_manager_image",
        data: data,
        success: function (response) {
            $('#btn_ckimg_delete').hide();
            $('#btn_ckimg_select').hide();
        }
    });

});


<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<script>
    var selected_lang_id = '<?php echo $selected_lang->id;?>';

    //add product variation post
    $("#form_add_product_variation").submit(function (event) {
        event.preventDefault();
        var input_variation_label = $.trim($('#input_variation_label').val());
        if (input_variation_label.length < 1) {
            $('#input_variation_label').addClass("is-invalid");
            return false;
        } else {
            $('#input_variation_label').removeClass("is-invalid");
        }
        var form = $(this);
        var serializedData = form.serializeArray();
        serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        $.ajax({
            url: base_url + "product_controller/add_product_variation",
            type: "post",
            data: serializedData,
            success: function (response) {
                $(".input-variation-label").val('');
                $("#addVariationModal").modal('hide');
                $(".variation-options-container").empty();
                document.getElementById("response_product_variations").innerHTML = response;
            }
        });
    });

    //edit product variation
    function edit_product_variation(common_id, product_id, lang_id) {
        $("#btn-variation-text-" + common_id).hide();
        $("#sp-" + common_id).css('display', 'inline-block');
        var data = {
            "common_id": common_id,
            "product_id": product_id,
            "lang_id": lang_id,
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            url: base_url + "product_controller/edit_product_variation",
            type: "post",
            data: data,
            success: function (response) {
                document.getElementById("response_product_variation_edit").innerHTML = response;
                setTimeout(
                    function () {
                        $("#editVariationModal").modal('show');
                        $("#sp-" + common_id).hide();
                        $("#btn-variation-text-" + common_id).show();
                    }, 500);
            }
        });
    }

    //edit product variation post
    $("#form_edit_product_variation").submit(function (event) {
        event.preventDefault();
        var input_variation_label = $.trim($('#input_variation_label_edit').val());
        if (input_variation_label.length < 1) {
            $('#input_variation_label_edit').addClass("is-invalid");
            return false;
        } else {
            $('#input_variation_label_edit').removeClass("is-invalid");
        }
        var form = $(this);
        var serializedData = form.serializeArray();
        serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        $.ajax({
            url: base_url + "product_controller/edit_product_variation_post",
            type: "post",
            data: serializedData,
            success: function (response) {
                $(".input-variation-label").val('');
                $("#editVariationModal").modal('hide');
                document.getElementById("response_product_variations").innerHTML = response;
            }
        });
    });

    //delete product variation
    function delete_product_variation(common_id, product_id, message) {
        swal({
            text: message,
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then(function (willDelete) {
                if (willDelete) {
                    var data = {
                        "common_id": common_id,
                        "product_id": product_id
                    };
                    data[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        url: base_url + "product_controller/delete_product_variation",
                        type: "post",
                        data: data,
                        success: function (response) {
                            document.getElementById("response_product_variations").innerHTML = response;
                        }
                    });
                }
            });
    }

    $(document).ajaxStop(function () {
        //add product variation option
        $("#form_add_product_variation_option").submit(function (event) {
            event.preventDefault();
            var input_variation_option = $.trim($('#input_variation_option').val());
            if (input_variation_option.length < 1) {
                $('#input_variation_option').addClass("is-invalid");
                return false;
            } else {
                $('#input_variation_option').removeClass("is-invalid");
            }
            var form = $(this);
            var serializedData = form.serializeArray();
            serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            $(".input-variation-label").val('');
            $.ajax({
                url: base_url + "product_controller/add_product_variation_option",
                type: "post",
                data: serializedData,
                success: function (response) {
                    document.getElementById("response_product_variation_options_edit").innerHTML = response;
                }
            });
        });

        //edit product variation options
        $("#form_edit_product_variation_options").submit(function (event) {
            event.preventDefault();
            var form = $(this);
            var serializedData = form.serializeArray();
            serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
            $(".input-variation-label").val('');
            $.ajax({
                url: base_url + "product_controller/edit_product_variation_options_post",
                type: "post",
                data: serializedData,
                success: function (response) {
                    document.getElementById("response_product_variation_options_edit").innerHTML = response;
                }
            });
        });
    });

    //delete product variation option
    function delete_product_variation_option(option_common_id, variation_common_id) {
        var data = {
            "option_common_id": option_common_id,
            "variation_common_id": variation_common_id,
            "lang_id": selected_lang_id
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            url: base_url + "product_controller/delete_product_variation_option",
            type: "post",
            data: data,
            success: function (response) {
                document.getElementById("response_product_variation_options_edit").innerHTML = response;
            }
        });
    }

    //select product variation
    $("#form_select_product_variation").submit(function (event) {
        event.preventDefault();
        var form = $(this);
        var serializedData = form.serializeArray();
        serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
        $.ajax({
            url: base_url + "product_controller/select_product_variation",
            type: "post",
            data: serializedData,
            success: function (response) {
                $("#variationModalSelect").modal('hide');
                document.getElementById("response_product_variations").innerHTML = response;
            }
        });
    });

    //edit product variation options
    function edit_product_variation_options(common_id, product_id, lang_id) {
        $("#btn-variation-text-options-" + common_id).hide();
        $("#sp-options-" + common_id).css('display', 'inline-block');
        var data = {
            "common_id": common_id,
            "product_id": product_id,
            "lang_id": lang_id,
        };
        data[csfr_token_name] = $.cookie(csfr_cookie_name);
        $.ajax({
            url: base_url + "product_controller/edit_product_variation_options",
            type: "post",
            data: data,
            success: function (response) {
                document.getElementById("response_product_variation_options_edit").innerHTML = response;
                setTimeout(
                    function () {
                        $("#editVariationOptionsModal").modal('show');
                        $("#sp-options-" + common_id).hide();
                        $("#btn-variation-text-options-" + common_id).show();
                    }, 500);
            }
        });
    }
</script>


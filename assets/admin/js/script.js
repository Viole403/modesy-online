//update token
$("form").submit(function () {
	$("input[name='" + csfr_token_name + "']").val($.cookie(csfr_cookie_name));
});

//datatable
$(document).ready(function () {
	$('#cs_datatable').DataTable({
		"order": [[0, "desc"]],
		"aLengthMenu": [[15, 30, 60, 100], [15, 30, 60, 100, "All"]]
	});
});

$('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
	checkboxClass: 'icheckbox_flat-red',
	radioClass: 'iradio_flat-red'
});
$('input[type="checkbox"].square-purple, input[type="radio"].square-purple').iCheck({
	checkboxClass: 'icheckbox_square-purple',
	radioClass: 'iradio_square-purple',
	increaseArea: '20%' // optional
});

$(function () {
	$('#tags_1').tagsInput({width: 'auto'});
});


//check all checkboxes
$("#checkAll").click(function () {
	$('input:checkbox').not(this).prop('checked', this.checked);
});

//show hide delete button
$('.checkbox-table').click(function () {
	if ($(".checkbox-table").is(':checked')) {
		$(".btn-table-delete").show();
	} else {
		$(".btn-table-delete").hide();
	}
});

//get blog categories
function get_blog_categories_by_lang(val) {
	var data = {
		"lang_id": val
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);

	$.ajax({
		type: "POST",
		url: base_url + "blog_controller/get_categories_by_lang",
		data: data,
		success: function (response) {
			$('#categories').children('option:not(:first)').remove();
			$("#categories").append(response);
		}
	});
}

//delete selected products
function delete_selected_products(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var product_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				product_ids.push(this.value);
			});
			var data = {
				'product_ids': product_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "product_admin_controller/delete_selected_products",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};

//delete selected products permanently
function delete_selected_products_permanently(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var product_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				product_ids.push(this.value);
			});
			var data = {
				'product_ids': product_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "product_admin_controller/delete_selected_products_permanently",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};

//remove from promoted
function remove_from_promoted(val) {
	var data = {
		"product_id": val,
		"is_ajax": 1
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_admin_controller/add_remove_promoted_products",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
}

//delete item
function delete_item(url, id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				'id': id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + url,
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};

//confirm user email
function confirm_user_email(id) {
	var data = {
		'id': id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "admin_controller/confirm_user_email",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

//ban remove user ban
function ban_remove_ban_user(id) {
	var data = {
		'id': id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "admin_controller/ban_remove_ban_user",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

//get states by country
function get_states_by_country(val) {
	var data = {
		"country_id": val
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);

	$.ajax({
		type: "POST",
		url: base_url + "admin_controller/get_states_by_country",
		data: data,
		success: function (response) {
			$('#select_states option').remove();
			$("#select_states").append(response);
		}
	});
}

//open or close user shop
function open_close_user_shop(id, message) {
	if (message.length > 1) {
		swal({
			text: message,
			icon: "warning",
			buttons: true,
			buttons: [sweetalert_cancel, sweetalert_ok],
			dangerMode: true,
		}).then(function (approve) {
			if (approve) {
				var data = {
					"id": id
				};
				data[csfr_token_name] = $.cookie(csfr_cookie_name);
				$.ajax({
					type: "POST",
					url: base_url + "admin_controller/open_close_user_shop",
					data: data,
					success: function (response) {
						location.reload();
					}
				});
			}
		});
	} else {
		var data = {
			"id": id
		};
		data[csfr_token_name] = $.cookie(csfr_cookie_name);
		$.ajax({
			type: "POST",
			url: base_url + "admin_controller/open_close_user_shop",
			data: data,
			success: function (response) {
				location.reload();
			}
		});
	}
};

//approve product
function approve_product(id) {
	var data = {
		'id': id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_admin_controller/approve_product",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

//restore product
function restore_product(id) {
	var data = {
		'id': id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_admin_controller/restore_product",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

//get filter subcategories
function get_filter_subcategories(val) {
	var data = {
		"parent_id": val
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);

	$.ajax({
		type: "POST",
		url: base_url + "category_controller/get_subcategories",
		data: data,
		success: function (response) {
			$('#subcategories').children('option:not(:first)').remove();
			$("#subcategories").append(response);
		}
	});
}

//upload product image update page
$(document).on('change', '#Multifileupload', function () {
	var MultifileUpload = document.getElementById("Multifileupload");
	if (typeof (FileReader) != "undefined") {
		var MultidvPreview = document.getElementById("MultidvPreview");
		MultidvPreview.innerHTML = "";
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
		for (var i = 0; i < MultifileUpload.files.length; i++) {
			var file = MultifileUpload.files[i];
			var reader = new FileReader();
			reader.onload = function (e) {
				var img = document.createElement("IMG");
				img.height = "100";
				img.width = "100";
				img.src = e.target.result;
				img.id = "Multifileupload_image";
				MultidvPreview.appendChild(img);
				$("#Multifileupload_button").show();
			}
			reader.readAsDataURL(file);
		}
	} else {
		alert("This browser does not support HTML5 FileReader.");
	}
});

function show_preview_image(input) {
	var name = $(input).attr('name');
	if (input.files && input.files[0]) {
		var reader = new FileReader();
		reader.onload = function (e) {
			$('#img_preview_' + name).attr('src', e.target.result);
		}
		reader.readAsDataURL(input.files[0]);
	}
}

//delete selected reviews
function delete_selected_reviews(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {

			var review_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				review_ids.push(this.value);
			});
			var data = {
				'review_ids': review_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "product_admin_controller/delete_selected_reviews",
				data: data,
				success: function (response) {
					location.reload();
				}
			});

		}
	});
};

//delete selected user reviews
function delete_selected_user_reviews(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {

			var review_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				review_ids.push(this.value);
			});
			var data = {
				'review_ids': review_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "admin_controller/delete_selected_user_reviews",
				data: data,
				success: function (response) {
					location.reload();
				}
			});

		}
	});
};

//delete selected comments
function delete_selected_comments(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {

			var comment_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				comment_ids.push(this.value);
			});
			var data = {
				'comment_ids': comment_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "product_admin_controller/delete_selected_comments",
				data: data,
				success: function (response) {
					location.reload();
				}
			});

		}
	});
};

//delete selected blog comments
function delete_selected_blog_comments(message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {

			var comment_ids = [];
			$("input[name='checkbox-table']:checked").each(function () {
				comment_ids.push(this.value);
			});
			var data = {
				'comment_ids': comment_ids,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "blog_controller/delete_selected_comments",
				data: data,
				success: function (response) {
					location.reload();
				}
			});

		}
	});
};

//delete custom field option
function delete_custom_field_option(message, common_id) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"common_id": common_id
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "category_controller/delete_custom_field_option",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};

//delete custom field category
function delete_custom_field_category(message, field_id, category_id) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"field_id": field_id,
				"category_id": category_id
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "category_controller/delete_custom_field_category",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};

//approve bank transfer
function approve_bank_transfer(id, order_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				'id': id,
				'order_id': order_id,
				'option': 'approved',
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "order_admin_controller/bank_transfer_options_post",
				data: data,
				success: function (response) {
					location.reload();
				}
			});

		}
	});
};

//email preview
$(document).on('click', '#btn_email_preview', function () {
	var title = $("input[name=subject]").val();
	var content = CKEDITOR.instances['ckEditor'].getData();
	var data = {
		"title": title,
		"content": content
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "admin_controller/email_preview",
		data: data,
		success: function (response) {
			var w = window.open();
			$(w.document.body).html(response);
		}
	});
});

$('.increase-count').each(function () {
	$(this).prop('Counter', 0).animate({
		Counter: $(this).text()
	}, {
		duration: 1000,
		easing: 'swing',
		step: function (now) {
			$(this).text(Math.ceil(now));
		}
	});
});

$('#selected_system_marketplace').on('ifChecked', function () {
	$('.system-currency-select').show();
});
$('#selected_system_classified_ads').on('ifChecked', function () {
	$('.system-currency-select').hide();
});

$(document).ready(function () {
	$('.magnific-image-popup').magnificPopup({type: 'image'});
});

$(document).on("input keyup paste change", ".validate_price .price-input", function () {
	var val = $(this).val();
	val = val.replace(',', '.');
	if ($.isNumeric(val) && val != 0) {
		$(this).removeClass('is-invalid');
	} else {
		$(this).addClass('is-invalid');
	}
});

$(document).ready(function () {
	$('.validate_price').submit(function (e) {
		$('.validate_price .validate-price-input').each(function () {
			var val = $(this).val();
			val = val.replace(',', '.');
			if ($.isNumeric(val) && val != 0) {
				$(this).removeClass('is-invalid');
			} else {
				e.preventDefault();
				$(this).addClass('is-invalid');
				$(this).focus();
			}
		});
	});
});

$('.price-input').keypress(function (event) {
	if (typeof thousands_separator == 'undefined') {
		thousands_separator = '.';
	}
	if (thousands_separator == '.') {
		var $this = $(this);
		if ((event.which != 46 || $this.val().indexOf('.') != -1) &&
			((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
			event.preventDefault();
		}
		var text = $(this).val();
		if ((text.indexOf('.') != -1) &&
			(text.substring(text.indexOf('.')).length > 2) &&
			(event.which != 0 && event.which != 8) &&
			($(this)[0].selectionStart >= text.length - 2)) {
			event.preventDefault();
		}
	} else {
		var $this = $(this);
		if ((event.which != 44 || $this.val().indexOf(',') != -1) &&
			((event.which < 48 || event.which > 57) &&
				(event.which != 0 && event.which != 8))) {
			event.preventDefault();
		}
		var text = $(this).val();
		if ((text.indexOf(',') != -1) &&
			(text.substring(text.indexOf(',')).length > 2) &&
			(event.which != 0 && event.which != 8) &&
			($(this)[0].selectionStart >= text.length - 2)) {
			event.preventDefault();
		}
	}
});


//delete category watermark
function delete_category_watermark(category_id) {
	var data = {
		"category_id": category_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "admin_controller/delete_category_watermark_post",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

$(document).ajaxStop(function () {

	$('input[type="checkbox"].square-purple, input[type="radio"].square-purple').iCheck({
		checkboxClass: 'icheckbox_square-purple',
		radioClass: 'iradio_square-purple',
		increaseArea: '20%' // optional
	});

});




//update token
$("form").submit(function () {
	$("input[name='" + csfr_token_name + "']").val($.cookie(csfr_cookie_name));
});

$(document).ready(function () {
	//main slider
	$("#main-slider").owlCarousel({
		autoplay: true,
		loop: $(".owl-carousel > .item").length <= 2 ? false : true,
		lazyLoad: true,
		lazyLoadEager: true,
		slideSpeed: 3000,
		paginationSpeed: 1000,
		items: 1,
		dots: true,
		nav: true,
		navText: ["<i class='icon-arrow-slider-left random-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-slider-right random-arrow-next' aria-hidden='true'></i>"],
		itemsDesktop: false,
		itemsDesktopSmall: false,
		itemsTablet: false,
		itemsMobile: false,
	});
	$("#product-slider").owlCarousel({
		items: 1,
		autoplay: false,
		nav: true,
		loop: $(".owl-carousel > .item").length <= 2 ? false : true,
		navText: ["<i class='icon-arrow-slider-left random-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-slider-right random-arrow-next' aria-hidden='true'></i>"],
		dotsContainer: '.dots-container',
	});
	//blog slider
	$("#blog-slider").owlCarousel({
		autoplay: true,
		loop: $(".owl-carousel > .item").length <= 2 ? false : true,
		margin: 20,
		nav: true,
		lazyLoad: true,
		navText: ["<i class='icon-arrow-slider-left random-arrow-prev' aria-hidden='true'></i>", "<i class='icon-arrow-slider-right random-arrow-next' aria-hidden='true'></i>"],
		responsive: {
			0: {
				items: 1
			},
			600: {
				items: 2
			},
			1000: {
				items: 3
			}
		}
	});

	//rate product
	$(document).on('click', '.rating-stars .label-star', function () {
		$('#user_rating').val($(this).attr('data-star'));
	});

	//mobile memu
	$(document).on('click', '.btn-open-mobile-nav', function () {
		document.getElementById("navMobile").style.width = "100%";
		$('html').addClass('disable-body-scroll');
		$('body').addClass('disable-body-scroll');
	});
	$(document).on('click', '.btn-close-mobile-nav', function () {
		document.getElementById("navMobile").style.width = "0";
		$('html').removeClass('disable-body-scroll');
		$('body').removeClass('disable-body-scroll');
	});
	$(document).on('click', '.close-mobile-nav', function () {
		document.getElementById("navMobile").style.width = "0";
	});

	$("#loginModal").on("hidden.bs.modal", function () {
		if ($('body').hasClass('disable-body-scroll')) {
			$('html').removeClass('disable-body-scroll');
			$('body').removeClass('disable-body-scroll');
		}
	});

});

//custom scrollbar
$(function () {
	$('.filter-custom-scrollbar').overlayScrollbars({});
	$('.search-results-location').overlayScrollbars({});
	$('.slider-custom-scrollbar').overlayScrollbars({scrollbars: {visibility: "hidden"}});
	$('.messages-sidebar').overlayScrollbars({});
	if ($('#message-custom-scrollbar').length > 0) {
		var instance_message_scrollbar = OverlayScrollbars(document.getElementById('message-custom-scrollbar'), {});
		instance_message_scrollbar.scroll({y: "100%"}, 0);
	}
});

//magnific popup
$(document).ready(function (b) {
	b(".image-popup").magnificPopup({
		type: "image", titleSrc: function (a) {
			return a.el.attr("title") + "<small></small>"
		}, image: {verticalFit: true,}, gallery: {enabled: true, navigateByImgClick: true, preload: [0, 1]}, removalDelay: 100, fixedContentPos: true,
	})
});

/*mega menu*/
$(".mega-menu .nav-item").hover(function () {
	var menu_id = $(this).attr('data-category-id');
	$("#mega_menu_content_" + menu_id).show();
	$(".large-menu-item").removeClass('active');
	$(".large-menu-item-first").addClass('active');
	$(".large-menu-content-first").addClass('active');
}, function () {
	var menu_id = $(this).attr('data-category-id');
	$("#mega_menu_content_" + menu_id).hide();
});

$(".mega-menu .dropdown-menu").hover(function () {
	$(this).show();
}, function () {
});

$(".large-menu-item").hover(function () {
	var menu_id = $(this).attr('data-subcategory-id');
	$(".large-menu-item").removeClass('active');
	$(this).addClass('active');
	$(".large-menu-content").removeClass('active');
	$("#large_menu_content_" + menu_id).addClass('active');
}, function () {
});


//scrollup
$(window).scroll(function () {
	if ($(this).scrollTop() > 100) {
		$(".scrollup").fadeIn()
	} else {
		$(".scrollup").fadeOut()
	}
});
$(".scrollup").click(function () {
	$("html, body").animate({scrollTop: 0}, 700);
	return false
});

$(function () {
	$(".search-select a").click(function () {
		$(".search-select .btn").text($(this).text());
		$(".search-select .btn").val($(this).text());
		$(".search_type_input").val($(this).attr("data-value"));
	});
});

$(document).on('click', '.quantity-select-product .dropdown-menu .dropdown-item', function () {
	$(".quantity-select-product .btn span").text($(this).text());
	$("input[name='product_quantity']").val($(this).text());
});

//set default location
function set_default_location(location_id) {
	var data = {
		location_id: location_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "home_controller/set_default_location",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
}

//show phone number
$(document).on('click', '#show_phone_number', function () {
	$(this).hide();
	$("#phone_number").show();
});


/*
 *------------------------------------------------------------------------------------------
 * AUTH FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//login
$(document).ready(function () {
	$("#form_login").submit(function (event) {
		var form = $(this);
		if (form[0].checkValidity() === false) {
			event.preventDefault();
			event.stopPropagation();
		} else {
			event.preventDefault();
			var inputs = form.find("input, select, button, textarea");
			var serializedData = form.serializeArray();
			serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
			$.ajax({
				url: base_url + "auth_controller/login_post",
				type: "post",
				data: serializedData,
				success: function (response) {
					var obj = JSON.parse(response);
					if (obj.result == 1) {
						location.reload();
					} else if (obj.result == 0) {
						document.getElementById("result-login").innerHTML = obj.error_message;
					}
				}
			});
		}
		form[0].classList.add('was-validated');
	});
});

//send activation email
function send_activation_email(id, token) {
	$('#result-login').empty();
	$('.spinner-activation-login').show();
	var data = {
		'id': id,
		'token': token,
		'type': 'login'
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$('#submit_review').prop("disabled", true);
	$.ajax({
		type: "POST",
		url: base_url + "auth_controller/send_activation_email_post",
		data: data,
		success: function (response) {
			var obj = JSON.parse(response);
			if (obj.result == 1) {
				$('.spinner-activation-login').hide();
				document.getElementById("result-login").innerHTML = obj.success_message;
			} else {
				location.reload();
			}
		}
	});
}

//send activation email register
function send_activation_email_register(id, token) {
	$('#result-register').empty();
	$('.spinner-activation-register').show();
	var data = {
		'id': id,
		'token': token,
		'type': 'register'
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$('#submit_review').prop("disabled", true);
	$.ajax({
		type: "POST",
		url: base_url + "auth_controller/send_activation_email_post",
		data: data,
		success: function (response) {
			var obj = JSON.parse(response);
			if (obj.result == 1) {
				$('.spinner-activation-register').hide();
				document.getElementById("result-register").innerHTML = obj.success_message;
			} else {
				location.reload();
			}
		}
	});
}

/*
 *------------------------------------------------------------------------------------------
 * REVIEW FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//make review
$(document).on('click', '#submit_review', function () {
	var user_rating = $.trim($('#user_rating').val());
	var user_review = $.trim($('#user_review').val());
	var product_id = $.trim($('#review_product_id').val());
	var limit = parseInt($("#product_review_limit").val());
	if (!user_rating) {
		$('.rating-stars').addClass('invalid-rating');
		return false;
	} else {
		$('.rating-stars').removeClass('invalid-rating');
	}
	var data = {
		"review": user_review,
		"rating": user_rating,
		"product_id": product_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$('#submit_review').prop("disabled", true);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/make_review",
		data: data,
		success: function (response) {
			$('#submit_review').prop("disabled", false);
			if (response == "voted_error") {
				$('.error-reviewed').show();
			} else if (response == "error_own_product") {
				$('.error-own-product').show();
			} else {
				document.getElementById("review-result").innerHTML = response;
			}
		}
	});
});

//load more review
function load_more_review(product_id) {
	var limit = parseInt($("#product_review_limit").val());
	var data = {
		"product_id": product_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#load_review_spinner").show();
	$.ajax({
		method: "POST",
		url: base_url + "product_controller/load_more_review",
		data: data
	})
		.done(function (response) {
			setTimeout(function () {
				$("#load_review_spinner").hide();
				document.getElementById("review-result").innerHTML = response
			}, 1000);
		})
}

//delete review
function delete_review(review_id, product_id, user_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var limit = parseInt($("#product_review_limit").val());
			var data = {
				"id": review_id,
				"product_id": product_id,
				"user_id": user_id,
				"limit": limit,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "product_controller/delete_review",
				data: data
			})
				.done(function (response) {
					document.getElementById("review-result").innerHTML = response;
				})
		}
	});
}


/*
*------------------------------------------------------------------------------------------
* USER REVIEW FUNCTIONS
*------------------------------------------------------------------------------------------
*/

//add user review
$(document).on('click', '#submit_user_review', function () {
	var user_rating = $.trim($('#user_rating').val());
	var user_review = $.trim($('#user_review').val());
	var seller_id = $.trim($('#review_seller_id').val());
	var limit = parseInt($("#user_review_limit").val());

	if (!user_rating) {
		$('.rating-stars').addClass('invalid-rating');
		return false;
	} else {
		$('.rating-stars').removeClass('invalid-rating');
	}
	var data = {
		"review": user_review,
		"rating": user_rating,
		"seller_id": seller_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$('#submit_user_review').prop("disabled", true);
	$.ajax({
		type: "POST",
		url: base_url + "ajax_controller/add_user_review",
		data: data,
		success: function (response) {
			$('#submit_user_review').prop("disabled", false);
			if (response == "voted_error") {
				$('.error-reviewed').show();
			} else {
				location.reload();
			}
		}
	});
});

//load more user review
function load_more_user_review(seller_id) {
	var limit = parseInt($("#user_review_limit").val());
	var data = {
		"seller_id": seller_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#load_review_spinner").show();
	$.ajax({
		method: "POST",
		url: base_url + "ajax_controller/load_more_user_review",
		data: data
	})
		.done(function (response) {
			setTimeout(function () {
				$("#load_review_spinner").hide();
				document.getElementById("user-review-result").innerHTML = response
			}, 1000);
		})
}

//delete user review
function delete_user_review(review_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"review_id": review_id,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "ajax_controller/delete_user_review",
				data: data
			})
				.done(function (response) {
					location.reload();
				})
		}
	});
}


/*
 *------------------------------------------------------------------------------------------
 * BLOG COMMENTS FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//make blog comment
$(document).ready(function () {
	var request;
	//make registered comment
	$("#make_blog_comment_registered").submit(function (event) {
		event.preventDefault();
		var comment_text = $.trim($('#comment_text').val());
		if (comment_text.length < 1) {
			$('#comment_text').addClass("is-invalid");
			return false;
		} else {
			$('#comment_text').removeClass("is-invalid");
		}
		if (request) {
			request.abort();
		}
		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var limit = parseInt($("#blog_comment_limit").val());

		var serializedData = $form.serializeArray();
		serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
		serializedData.push({name: "lang_folder", value: lang_folder});
		serializedData.push({name: "limit", value: limit});
		$inputs.prop("disabled", true);
		request = $.ajax({
			url: base_url + "home_controller/add_comment_post",
			type: "post",
			data: serializedData,
		});
		request.done(function (response) {
			$inputs.prop("disabled", false);
			document.getElementById("comment-result").innerHTML = response;
			$("#make_blog_comment_registered")[0].reset();
		});

	});

	//make comment
	$("#make_blog_comment").submit(function (event) {
		event.preventDefault();
		var comment_name = $.trim($('#comment_name').val());
		var comment_email = $.trim($('#comment_email').val());
		var comment_text = $.trim($('#comment_text').val());

		if (comment_name.length < 1) {
			$('#comment_name').addClass("is-invalid");
			return false;
		} else {
			$('#comment_name').removeClass("is-invalid");
		}
		if (comment_email.length < 1) {
			$('#comment_email').addClass("is-invalid");
			return false;
		} else {
			$('#comment_email').removeClass("is-invalid");
		}
		if (comment_text.length < 1) {
			$('#comment_text').addClass("is-invalid");
			return false;
		} else {
			$('#comment_text').removeClass("is-invalid");
		}

		if (request) {
			request.abort();
		}
		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var limit = parseInt($("#blog_comment_limit").val());
		var serializedData = $form.serializeArray();
		serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
		serializedData.push({name: "limit", value: limit});
		serializedData.push({name: "lang_folder", value: lang_folder});

		var recaptcha_status = true;
		if (is_recaptcha_enabled == true) {
			$(serializedData).each(function (i, field) {
				if (field.name == "g-recaptcha-response") {
					if (field.value == "") {
						$('.g-recaptcha').addClass("is-recaptcha-invalid");
						recaptcha_status = false;
					}
				}
			});
		}
		if (recaptcha_status == true) {
			$('.g-recaptcha').removeClass("is-recaptcha-invalid");
			$inputs.prop("disabled", true);
			request = $.ajax({
				url: base_url + "home_controller/add_comment_post",
				type: "post",
				data: serializedData,
			});
			request.done(function (response) {
				$inputs.prop("disabled", false);
				if (is_recaptcha_enabled == true) {
					grecaptcha.reset();
				}
				document.getElementById("comment-result").innerHTML = response;
				$("#make_blog_comment")[0].reset();
			});
		}
	});
});

//load more blog comment
function load_more_blog_comment(post_id) {
	var limit = parseInt($("#blog_comment_limit").val());
	var data = {
		"post_id": post_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#load_comment_spinner").show();
	$.ajax({
		method: "POST",
		url: base_url + "home_controller/load_more_comment",
		data: data
	})
		.done(function (response) {
			setTimeout(function () {
				$("#load_comment_spinner").hide();
				document.getElementById("comment-result").innerHTML = response
			}, 1000)
		})
}

//delete blog comment
function delete_blog_comment(comment_id, post_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var limit = parseInt($("#blog_comment_limit").val());
			var data = {
				"comment_id": comment_id,
				"post_id": post_id,
				"limit": limit,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "home_controller/delete_comment_post",
				data: data
			})
				.done(function (response) {
					document.getElementById("comment-result").innerHTML = response

				})
		}
	});
}


/*
 *------------------------------------------------------------------------------------------
 * PRODUCT COMMENT FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

$(document).ready(function () {
	var request;
	//make registered comment
	$("#make_comment_registered").submit(function (event) {
		event.preventDefault();
		var comment_text = $.trim($('#comment_text').val());
		if (comment_text.length < 1) {
			$('#comment_text').addClass("is-invalid");
			return false;
		} else {
			$('#comment_text').removeClass("is-invalid");
		}
		if (request) {
			request.abort();
		}
		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var limit = parseInt($("#product_comment_limit").val());

		var serializedData = $form.serializeArray();
		serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
		serializedData.push({name: "lang_folder", value: lang_folder});
		serializedData.push({name: "limit", value: limit});

		$inputs.prop("disabled", true);
		request = $.ajax({
			url: base_url + "product_controller/make_comment",
			type: "post",
			data: serializedData,
		});
		request.done(function (response) {
			$inputs.prop("disabled", false);
			document.getElementById("comment-result").innerHTML = response;
			$("#make_comment_registered")[0].reset();
		});

	});

	//make comment
	$("#make_comment").submit(function (event) {
		event.preventDefault();
		var comment_name = $.trim($('#comment_name').val());
		var comment_email = $.trim($('#comment_email').val());
		var comment_text = $.trim($('#comment_text').val());

		if (comment_name.length < 1) {
			$('#comment_name').addClass("is-invalid");
			return false;
		} else {
			$('#comment_name').removeClass("is-invalid");
		}
		if (comment_email.length < 1) {
			$('#comment_email').addClass("is-invalid");
			return false;
		} else {
			$('#comment_email').removeClass("is-invalid");
		}
		if (comment_text.length < 1) {
			$('#comment_text').addClass("is-invalid");
			return false;
		} else {
			$('#comment_text').removeClass("is-invalid");
		}

		if (request) {
			request.abort();
		}
		var $form = $(this);
		var $inputs = $form.find("input, select, button, textarea");
		var limit = parseInt($("#product_comment_limit").val());

		var serializedData = $form.serializeArray();
		serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
		serializedData.push({name: "lang_folder", value: lang_folder});
		serializedData.push({name: "limit", value: limit});

		var recaptcha_status = true;
		if (is_recaptcha_enabled == true) {
			$(serializedData).each(function (i, field) {
				if (field.name == "g-recaptcha-response") {
					if (field.value == "") {
						$('.g-recaptcha').addClass("is-recaptcha-invalid");
						recaptcha_status = false;
					}
				}
			});
		}
		if (recaptcha_status == true) {
			$('.g-recaptcha').removeClass("is-recaptcha-invalid");
			$inputs.prop("disabled", true);
			request = $.ajax({
				url: base_url + "product_controller/make_comment",
				type: "post",
				data: serializedData,
			});
			request.done(function (response) {
				$inputs.prop("disabled", false);
				if (is_recaptcha_enabled == true) {
					grecaptcha.reset();
				}
				document.getElementById("comment-result").innerHTML = response;
				$("#make_comment")[0].reset();
			});
		}
	});

});

//make registered subcomment
$(document).on('click', '.btn-subcomment-registered', function () {
	var comment_id = $(this).attr("data-comment-id");
	var data = {
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#make_subcomment_registered_" + comment_id).ajaxSubmit({
		beforeSubmit: function () {
			var form = $("#make_subcomment_registered_" + comment_id).serializeArray();
			var comment = $.trim(form[0].value);
			if (comment.length < 1) {
				$(".form-comment-text").addClass("is-invalid");
				return false;
			} else {
				$(".form-comment-text").removeClass("is-invalid");
			}
		},
		type: "POST",
		url: base_url + "product_controller/make_comment",
		data: data,
		success: function (response) {
			document.getElementById("comment-result").innerHTML = response;
		}
	})
});

//make subcomment
$(document).on('click', '.btn-subcomment', function () {
	var comment_id = $(this).attr("data-comment-id");
	var data = {
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#make_subcomment_" + comment_id).ajaxSubmit({
		beforeSubmit: function () {
			var form = $("#make_subcomment_" + comment_id).serializeArray();
			var name = $.trim(form[0].value);
			var email = $.trim(form[1].value);
			var comment = $.trim(form[2].value);
			if (is_recaptcha_enabled == true) {
				var recaptcha = $.trim(form[3].value);
			}

			if (name.length < 1) {
				$(".form-comment-name").addClass("is-invalid");
				return false;
			} else {
				$(".form-comment-name").removeClass("is-invalid");
			}
			if (email.length < 1) {
				$(".form-comment-email").addClass("is-invalid");
				return false;
			} else {
				$(".form-comment-email").removeClass("is-invalid");
			}
			if (comment.length < 1) {
				$(".form-comment-text").addClass("is-invalid");
				return false;
			} else {
				$(".form-comment-text").removeClass("is-invalid");
			}
			if (is_recaptcha_enabled == true) {
				if (recaptcha == "") {
					$("#make_subcomment_" + comment_id + ' .g-recaptcha').addClass("is-recaptcha-invalid");
					return false;
				} else {
					$("#make_subcomment_" + comment_id + ' .g-recaptcha').removeClass("is-recaptcha-invalid");
				}
			}
		},
		type: "POST",
		url: base_url + "product_controller/make_comment",
		data: data,
		success: function (response) {
			if (is_recaptcha_enabled == true) {
				grecaptcha.reset();
			}
			document.getElementById("comment-result").innerHTML = response;
		}
	})
});

//load more comment
function load_more_comment(product_id) {
	var limit = parseInt($("#product_comment_limit").val());
	var data = {
		"product_id": product_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$("#load_comment_spinner").show();
	$.ajax({
		method: "POST",
		url: base_url + "product_controller/load_more_comment",
		data: data
	})
		.done(function (response) {
			setTimeout(function () {
				$("#load_comment_spinner").hide();
				document.getElementById("comment-result").innerHTML = response;
			}, 1000)
		})
}

//delete comment
function delete_comment(comment_id, product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var limit = parseInt($("#product_comment_limit").val());
			var data = {
				"id": comment_id,
				"product_id": product_id,
				"limit": limit,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "product_controller/delete_comment",
				data: data
			})
				.done(function (response) {
					document.getElementById("comment-result").innerHTML = response;
				})

		}
	});
}

//show comment box
function show_comment_box(comment_id) {
	$('.visible-sub-comment').empty();
	var limit = parseInt($("#product_comment_limit").val());
	var data = {
		"comment_id": comment_id,
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/load_subcomment_box",
		data: data,
		success: function (response) {
			$('#sub_comment_form_' + comment_id).append(response);
		}
	});
}


/*
 *------------------------------------------------------------------------------------------
 * MESSAGE FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//delete conversation
function delete_conversation(conversation_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: true,
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"conversation_id": conversation_id,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "message_controller/delete_conversation",
				data: data
			})
				.done(function (response) {
					window.location.href = base_url + "messages";
				})

		}
	});
}


/*
 *------------------------------------------------------------------------------------------
 * CART FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//remove from cart
function remove_from_cart(cart_item_id) {
	var data = {
		"cart_item_id": cart_item_id,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "cart_controller/remove_from_cart",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
};

//update cart product quantity
$(document).on('click', '.btn-cart-product-quantity-item', function () {
	var quantity = $(this).val();
	var product_id = $(this).attr("data-product-id");
	var data = {
		"product_id": product_id,
		"quantity": quantity,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "cart_controller/update_cart_product_quantity",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
});

$(document).ready(function () {
	$('#use_same_address_for_billing').change(function () {
		if ($(this).is(":checked")) {
			$('.cart-form-billing-address').hide();
		} else {
			$('.cart-form-billing-address').show();
		}
	});
});

//approve order product
function approve_order_product(id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (approve) {
		if (approve) {
			var data = {
				"order_product_id": id,
				"lang_folder": lang_folder
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "order_controller/approve_order_product_post",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
};


/*
 *------------------------------------------------------------------------------------------
 * OTHER FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//AJAX search
$(document).on("input paste focus", "#input_search", function () {
	var search_type = $('.search_type_input').val();
	var input_value = $(this).val();
	if (input_value.length < 2) {
		$('#response_search_results').hide();
		return false;
	}
	var data = {
		"search_type": search_type,
		"input_value": input_value,
		"lang_base_url": lang_base_url
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "ajax_controller/ajax_search",
		data: data,
		success: function (response) {
			var obj = JSON.parse(response);
			if (obj.result == 1) {
				document.getElementById("response_search_results").innerHTML = obj.response;
				$('#response_search_results').show();
			}
			//search text
			$('#response_search_results ul li a').wrapInTag({
				words: [input_value]
			});
		}
	});
});

$(document).on('click', function (e) {
	if ($(e.target).closest(".top-search-bar").length === 0) {
		$("#response_search_results").hide();
	}
});

//search location
$(document).on("input paste focus", "#input_location", function () {
	var input_value = $(this).val();
	if (input_value.length < 2) {
		$('#response_search_location').hide();
		return false;
	}
	var data = {
		"input_value": input_value
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "ajax_controller/search_location",
		data: data,
		success: function (response) {
			var obj = JSON.parse(response);
			if (obj.result == 1) {
				document.getElementById("response_search_location").innerHTML = obj.response;
				$('#response_search_location').show();
			}
			//search text
			$('#response_search_location ul li a').wrapInTag({
				words: [input_value]
			});
		}
	});
});

$.fn.wrapInTag = function (opts) {
	function getText(obj) {
		return obj.textContent ? obj.textContent : obj.innerText;
	}

	var tag = opts.tag || 'strong',
		words = opts.words || [],
		regex = RegExp(words.join('|'), 'gi'),
		replacement = '<' + tag + '>$&</' + tag + '>';
	$(this).contents().each(function () {
		if (this.nodeType === 3) {
			$(this).replaceWith(getText(this).replace(regex, replacement));
		} else if (!opts.ignoreChildNodes) {
			$(this).wrapInTag(opts);
		}
	});
};

//set location
$(document).on("click", "#response_search_location ul li a", function () {
	var country_id = $(this).attr('data-country');
	var state_id = $(this).attr('data-state');
	var city_id = $(this).attr('data-city');
	$('.input-location-filter').remove();
	if (country_id != null && country_id != 0) {
		$('.filter-location').append("<input type='hidden' value='" + country_id + "' name='country' class='input-location-filter'>");
	}
	if (state_id != null && state_id != 0) {
		$('.filter-location').append("<input type='hidden' value='" + state_id + "' name='state' class='input-location-filter'>");
	}
	if (city_id != null && city_id != 0) {
		$('.filter-location').append("<input type='hidden' value='" + city_id + "' name='city' class='input-location-filter'>");
	}
	$('#form-product-filters').submit();
});

$(document).on('click', function (e) {
	if ($(e.target).closest(".filter-location").length === 0) {
		$("#response_search_location").hide();
	}
});

//set site language
function set_site_language(lang_id) {
	var data = {
		lang_id: lang_id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		method: "POST",
		url: base_url + "home_controller/set_site_language",
		data: data
	})
		.done(function (response) {
			location.reload();
		})
}


$(document).on('click', '#btn_load_more_promoted', function () {
	$("#load_promoted_spinner").show();
	var limit = $("#input_promoted_products_limit").val();
	var per_page = $("#input_promoted_products_per_page").val();
	var promoted_products_count = $("#input_promoted_products_count").val();
	var new_limit = parseInt(limit) + parseInt(per_page);
	var data = {
		"limit": limit,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "home_controller/load_more_promoted_products",
		data: data,
		success: function (response) {
			$("#input_promoted_products_limit").val(new_limit);
			setTimeout(function () {
				$("#load_promoted_spinner").hide();
				$("#row_promoted_products").append(response);
				if (new_limit >= promoted_products_count) {
					$("#btn_load_more_promoted").hide();
				}
			}, 700)
		}
	});

});

//delete product
function delete_product(product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"id": product_id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "product_controller/delete_product",
				data: data
			})
				.done(function (response) {
					location.reload();
				})

		}
	});
}

//delete draft
function delete_draft(product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"id": product_id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				method: "POST",
				url: base_url + "product_controller/delete_draft",
				data: data
			})
				.done(function (response) {
					location.reload();
				})
		}
	});
}

//set product as sold
function set_product_as_sold(product_id) {
	var data = {
		"product_id": product_id,
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		method: "POST",
		url: base_url + "product_controller/set_product_as_sold",
		data: data
	})
		.done(function (response) {
			location.reload();
		})
}

//delete product digital file
function delete_product_digital_file(product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"product_id": product_id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				url: base_url + "file_controller/delete_digital_file",
				type: "post",
				data: data,
				success: function (response) {
					document.getElementById("digital_files_upload_result").innerHTML = response;
				}
			});
		}
	});
}

//delete product video preview
function delete_product_video_preview(product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"product_id": product_id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				url: base_url + "file_controller/delete_video",
				type: "post",
				data: data,
				success: function (response) {
					document.getElementById("video_upload_result").innerHTML = response;
				}
			});
		}
	});
}

//delete product audio preview
function delete_product_audio_preview(product_id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				"product_id": product_id,
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				url: base_url + "file_controller/delete_audio",
				type: "post",
				data: data,
				success: function (response) {
					document.getElementById("audio_upload_result").innerHTML = response;
				}
			});
		}
	});
}

//send message
$("#form_send_message").submit(function (event) {
	event.preventDefault();
	var message_subject = $('#message_subject').val();
	var message_text = $('#message_text').val();
	var message_sender_id = $('#message_sender_id').val();
	var message_receiver_id = $('#message_receiver_id').val();
	var message_send_em = $('#message_send_em').val();

	if (message_subject.length < 1) {
		$('#message_subject').addClass("is-invalid");
		return false;
	} else {
		$('#message_subject').removeClass("is-invalid");
	}
	if (message_text.length < 1) {
		$('#message_text').addClass("is-invalid");
		return false;
	} else {
		$('#message_text').removeClass("is-invalid");
	}
	var $form = $(this);
	var $inputs = $form.find("input, select, button, textarea");
	var serializedData = $form.serializeArray();
	serializedData.push({name: csfr_token_name, value: $.cookie(csfr_cookie_name)});
	serializedData.push({name: "lang_folder", value: lang_folder});
	$inputs.prop("disabled", true);
	$.ajax({
		url: base_url + "message_controller/add_conversation",
		type: "post",
		data: serializedData,
		success: function (response) {
			$inputs.prop("disabled", false);
			document.getElementById("send-message-result").innerHTML = response;
			$("#form_send_message")[0].reset();
			//send email
			if (message_send_em) {
				send_message_as_email(message_sender_id, message_receiver_id, message_subject, message_text);
			}
		}
	});
});

function send_message_as_email(message_sender_id, message_receiver_id, message_subject, message_text) {
	var data = {
		'email_type': 'new_message',
		'sender_id': message_sender_id,
		"receiver_id": message_receiver_id,
		"message_subject": message_subject,
		"message_text": message_text,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "ajax_controller/send_email",
		data: data,
		success: function (response) {
		}
	});
}

function get_states(val) {
	var data = {
		"country_id": val,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/get_states",
		data: data,
		success: function (response) {
			$('#states').children('option:not(:first)').remove();
			$('#cities').children('option:not(:first)').remove();
			$("#states").append(response);
			update_product_map();
		}
	});
}

function get_cities(val) {
	var data = {
		"state_id": val,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/get_cities",
		data: data,
		success: function (response) {
			$('#cities').children('option:not(:first)').remove();
			$("#cities").append(response);
			update_product_map();
		}
	});
}

function update_product_map() {
	var country_text = $("#countries").find('option:selected').text();
	var country_val = $("#countries").find('option:selected').val();
	var state_text = $("#states").find('option:selected').text();
	var state_val = $("#states").find('option:selected').val();
	var address = $("#address_input").val();
	var zip_code = $("#zip_code_input").val();
	var data = {
		"country_text": country_text,
		"country_val": country_val,
		"state_text": state_text,
		"state_val": state_val,
		"address": address,
		"zip_code": zip_code,
		"lang_folder": lang_folder
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/show_address_on_map",
		data: data,
		success: function (response) {
			document.getElementById("map-result").innerHTML = response;
		}
	});
}

$(document).on('change', '#address_input', function () {
	update_product_map();
});
$(document).on('change', '#zip_code_input', function () {
	update_product_map();
});

$(document).on('click', '.item-favorite-button', function () {
	var product_id = $(this).attr("data-product-id");
	if ($(this).hasClass("item-favorite-enable")) {
		if ($(this).hasClass('item-favorited')) {
			$(this).removeClass('item-favorited');
		} else {
			$(this).addClass('item-favorited');
		}
		var data = {
			"product_id": product_id
		};
		data[csfr_token_name] = $.cookie(csfr_cookie_name);
		$.ajax({
			type: "POST",
			url: base_url + "product_controller/add_remove_favorite_ajax",
			data: data,
			success: function (response) {
			}
		});
	}
});

//set main image session
$(document).on('click', '.btn-set-image-main-session', function () {
	var file_id = $(this).attr('data-file-id');
	var data = {
		"file_id": file_id
	};
	$('.badge-is-image-main').removeClass('badge-success');
	$('.badge-is-image-main').addClass('badge-secondary');
	$(this).removeClass('badge-secondary');
	$(this).addClass('badge-success');
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "file_controller/set_image_main_session",
		data: data,
		success: function (response) {
		}
	});
});

//set main image
$(document).on('click', '.btn-set-image-main', function () {
	var image_id = $(this).attr('data-image-id');
	var product_id = $(this).attr('data-product-id');
	var data = {
		"image_id": image_id,
		"product_id": product_id
	};
	$('.badge-is-image-main').removeClass('badge-success');
	$('.badge-is-image-main').addClass('badge-secondary');
	$(this).removeClass('badge-secondary');
	$(this).addClass('badge-success');
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "file_controller/set_image_main",
		data: data,
		success: function (response) {
		}
	});
});

//delete product image session
$(document).on('click', '.btn-delete-product-img-session', function () {
	var file_id = $(this).attr('data-file-id');
	var data = {
		"file_id": file_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "file_controller/delete_image_session",
		data: data,
		success: function () {
			$('#uploaderFile' + file_id).remove();
		}
	});
});

//delete product image
$(document).on('click', '.btn-delete-product-img', function () {
	var file_id = $(this).attr('data-file-id');
	var data = {
		"file_id": file_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "file_controller/delete_image",
		data: data,
		success: function (response) {
			location.reload();
		}
	});
});

$("#form_validate").submit(function () {
	$('.custom-control-validate-input').removeClass('custom-control-validate-error');
	setTimeout(function () {
		$('.custom-control-validate-input .error').each(function () {
			var name = $(this).attr('name');
			if ($(this).is(":visible")) {
				name = name.replace('[]', '');
				$('.label_validate_' + name).addClass('custom-control-validate-error');
			}
		});
	}, 100);
});

$('.custom-control-validate-input input').click(function () {
	var name = $(this).attr('name');
	name = name.replace('[]', '');
	$('.label_validate_' + name).removeClass('custom-control-validate-error');
});

//hide cookies warning
function hide_cookies_warning() {
	$(".cookies-warning").hide();
	var data = {};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "home_controller/cookies_warning",
		data: data,
		success: function (response) {
		}
	});
}

$("#form_validate").validate();
$("#form_validate_search").validate();
$("#form_validate_search_mobile").validate();
$("#form_validate_payout_1").validate();
$("#form_validate_payout_2").validate();
$("#form_validate_payout_3").validate();
$("#form_validate_newsletter").validate();
$("#form_add_cart").validate();
$("#form_add_cart_mobile").validate();
$("#form_validate_checkout").validate();
//validate product variations
$("#form_add_cart").submit(function () {
	$('#form_add_cart .custom-control-variation input').each(function () {
		if ($(this).hasClass('error')) {
			var id = $(this).attr('id');
			$('#form_add_cart .custom-control-variation label').each(function () {
				if ($(this).attr('for') == id) {
					$(this).addClass('is-invalid');
				}
			});
		} else {
			var id = $(this).attr('id');
			$('#form_add_cart .custom-control-variation label').each(function () {
				if ($(this).attr('for') == id) {
					$(this).removeClass('is-invalid');
				}
			});
		}
	});
});
$("#form_add_cart_mobile").submit(function () {
	$('#form_add_cart_mobile .custom-control-variation input').each(function () {
		if ($(this).hasClass('error')) {
			var id = $(this).attr('id');
			$('#form_add_cart_mobile .custom-control-variation label').each(function () {
				if ($(this).attr('for') == id) {
					$(this).addClass('is-invalid');
				}
			});
		} else {
			var id = $(this).attr('id');
			$('#form_add_cart_mobile .custom-control-variation label').each(function () {
				if ($(this).attr('for') == id) {
					$(this).removeClass('is-invalid');
				}
			});
		}
	});
});

$(document).on('click', '.custom-control-variation input', function () {
	var name = $(this).attr('name');
	$('.custom-control-variation label').each(function () {
		if ($(this).attr('data-input-name') == name) {
			$(this).removeClass('is-invalid');
		}
	});
});

$(document).ready(function () {
	$('.validate_terms').submit(function (e) {
		if (!$('.custom-control-validate-input input').is(":checked")) {
			e.preventDefault();
			$('.custom-control-validate-input').addClass('custom-control-validate-error');
		} else {
			$('.custom-control-validate-input').removeClass('custom-control-validate-error');
		}
	});
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

$('input[type=radio][name=product_type]').change(function () {
	if (this.value == 'digital') {
		$('.listing_ordinary_listing').hide();
		$('.listing_take_offers').hide();
		$('.listing_sell_on_site input').prop('checked', true);
	} else {
		$('.listing_ordinary_listing').show();
		$('.listing_take_offers').show();
	}
});

$(document).ready(function () {
	$('.validate_price').submit(function (e) {
		$('.validate_price .validate-price-input').each(function () {
			var val = $(this).val();
			if (val != '') {
				val = val.replace(',', '.');
				if ($.isNumeric(val) && val != 0) {
					$(this).removeClass('is-invalid');
				} else {
					e.preventDefault();
					$(this).addClass('is-invalid');
					$(this).focus();
				}
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

//full screen
$(document).ready(function () {
	$("iframe").attr("allowfullscreen", "")
});

//delete quote request
function delete_quote_request(id, message) {
	swal({
		text: message,
		icon: "warning",
		buttons: [sweetalert_cancel, sweetalert_ok],
		dangerMode: true,
	}).then(function (willDelete) {
		if (willDelete) {
			var data = {
				'id': id
			};
			data[csfr_token_name] = $.cookie(csfr_cookie_name);
			$.ajax({
				type: "POST",
				url: base_url + "bidding_controller/delete_quote_request",
				data: data,
				success: function (response) {
					location.reload();
				}
			});
		}
	});
}

/*
 *------------------------------------------------------------------------------------------
 * LICENSE KEY FUNCTIONS
 *------------------------------------------------------------------------------------------
 */

//add license key
function add_license_keys(product_id) {
	var data = {
		'product_id': product_id,
		'license_keys': $('#textarea_license_keys').val(),
		'allow_dublicate': $("input[name='allow_dublicate_license_keys']:checked").val()
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/add_license_keys",
		data: data,
		success: function (response) {
			var obj = JSON.parse(response);
			if (obj.result == 1) {
				document.getElementById("result-add-license-keys").innerHTML = obj.success_message;
				$('#textarea_license_keys').val('');
			}
		}
	});
}

//delete license key
function delete_license_key(id, product_id) {
	var data = {
		'id': id,
		'product_id': product_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/delete_license_key",
		data: data,
		success: function (response) {
			$('#tr_license_key_' + id).remove();
		}
	});
}

//update license code list on modal open
$("#viewLicenseKeysModal").on('show.bs.modal', function () {
	var product_id = $('#license_key_list_product_id').val();
	var data = {
		'product_id': product_id
	};
	data[csfr_token_name] = $.cookie(csfr_cookie_name);
	$.ajax({
		type: "POST",
		url: base_url + "product_controller/refresh_license_keys_list",
		data: data,
		success: function (response) {
			document.getElementById("response_license_key").innerHTML = response;
		}
	});
});


$(document).ready(function () {
	$('[data-toggle="tooltip"]').tooltip();
});

$(document).on('change', '#ckMultifileupload', function () {
	var MultifileUpload = document.getElementById("ckMultifileupload");
	if (typeof (FileReader) != "undefined") {
		var MultidvPreview = document.getElementById("ckMultidvPreview");
		MultidvPreview.innerHTML = "";
		var regex = /^([a-zA-Z0-9\s_\\.\-:])+(.jpg|.jpeg|.gif|.png|.bmp)$/;
		for (var i = 0; i < MultifileUpload.files.length; i++) {
			var file = MultifileUpload.files[i];
			var reader = new FileReader();
			reader.onload = function (e) {
				var img = document.createElement("IMG");
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

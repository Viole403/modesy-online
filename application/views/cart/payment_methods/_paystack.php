<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == "paystack"):
	$email = "test123987@gmail.com";
	$order_shipping = $this->cart_model->get_sess_cart_shipping_address();
	if (!empty($this->auth_check)) {
		$email = $this->auth_user->email;
	} elseif (!empty($order_shipping)) {
		$email = $order_shipping->shipping_email;
	}
	?>
	<div class="row">
		<div class="col-12">
			<?php $this->load->view('product/_messages'); ?>
		</div>
	</div>

	<form>
		<script src="https://js.paystack.co/v1/inline.js"></script>

		<div id="payment-button-container" class=paypal-button-cnt">
			<p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
			<button type="button" class="btn btn-lg custom-stripe-button" onclick="payWithPaystack()"><?php echo trans("pay_now") ?></button>
		</div>
	</form>

	<!-- place below the html form -->
	<script>
        function payWithPaystack() {
            var handler = PaystackPop.setup({
                key: '<?php echo $this->payment_settings->paystack_public_key;?>',
                email: '<?php echo $email; ?>',
                amount: '<?php echo $total_amount; ?>',
                ref: '<?php echo generate_token(); ?>',
                callback: function (response) {
                    var data = {
                        'payment_id': response.reference,
                        'currency': '<?php echo $currency; ?>',
                        'payment_amount': '<?php echo $total_amount; ?>',
                        'payment_status': response.status,
                        'mds_payment_type': '<?php echo $mds_payment_type; ?>',
                        'lang_folder': lang_folder,
                        'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                    };
                    data[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "cart_controller/paystack_payment_post",
                        data: data,
                        success: function (response) {
                            var obj = JSON.parse(response);
                            if (obj.result == 1) {
                                window.location.href = obj.redirect;
                            } else {
                                location.reload();
                            }
                        }
                    });
                },
            });
            handler.openIframe();
        }
	</script>
<?php endif; ?>

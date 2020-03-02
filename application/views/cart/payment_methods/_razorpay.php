<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($cart_payment_method->payment_option == 'razorpay'):
	$ci =& get_instance();
	$ci->load->library('razorpay');
	$array = array(
		'receipt' => '123',
		'amount' => $total_amount,
		'currency' => $currency
	);
	$razorpay_order_id = $ci->razorpay->create_order($array);
	if (!empty($razorpay_order_id)): ?>
		<div class="row">
			<div class="col-12">
				<?php $this->load->view('product/_messages'); ?>
			</div>
		</div>

		<div id="payment-button-container" class=paypal-button-cnt">
			<p class="p-complete-payment"><?php echo trans("msg_complete_payment"); ?></p>
			<button type="button" id="rzp-button1" class="btn btn-lg custom-stripe-button"><?php echo trans("pay_now") ?></button>
		</div>

		<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
		<script>
            var options = {
                "key": "<?php echo $payment_settings->razorpay_key_id; ?>",
                "amount": "<?php echo $total_amount; ?>",
                "currency": "<?php echo $currency; ?>",
                "name": "<?php echo $general_settings->application_name; ?>",
                "description": "<?php echo trans("pay"); ?>",
                "image": "<?php echo get_logo_email($settings); ?>",
                "order_id": "<?php echo $razorpay_order_id; ?>",
                "handler": function (response) {
                    var data_array = {
                        'payment_id': response.razorpay_payment_id,
                        'razorpay_order_id': response.razorpay_order_id,
                        'razorpay_signature': response.razorpay_signature,
                        'currency': '<?php echo $currency; ?>',
                        'payment_amount': '<?php echo $total_amount; ?>',
                        'payment_status': '',
                        'mds_payment_type': '<?php echo $mds_payment_type; ?>',
                        'lang_folder': lang_folder,
                        'form_lang_base_url': '<?php echo lang_base_url(); ?>'
                    };
                    data_array[csfr_token_name] = $.cookie(csfr_cookie_name);
                    $.ajax({
                        type: "POST",
                        url: base_url + "cart_controller/razorpay_payment_post",
                        data: data_array,
                        success: function (response) {
                            console.log(response);
                            var obj = JSON.parse(response);
                            if (obj.result == 1) {
                                window.location.href = obj.redirect;
                            } else {
                                location.reload();
                            }
                        }
                    });
                },
                "theme": {
                    "color": "#528FF0"
                }
            };
            var rzp1 = new Razorpay(options);
            document.getElementById('rzp-button1').onclick = function (e) {
                rzp1.open();
                e.preventDefault();
            }
		</script>
	<?php endif;
endif; ?>

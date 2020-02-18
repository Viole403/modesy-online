<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $active_tab = 'credit_card';
if (old('payment_type') == 'boleto') {
	$active_tab = 'boleto';
} ?>
<?php if ($cart_payment_method->payment_option == "pagseguro"): ?>
	<div class="row">
		<div class="col-12 text-center m-b-30">
			<img src="<?php echo base_url(); ?>assets/img/payment/pagseguro.png" alt="pagseguro" width="200">
		</div>
		<div class="col-12">
			<!-- Credit card form tabs -->
			<ul role="tablist" class="nav nav-pills nav-fill mb-3 nav-pills-pagseguro">
				<li class="nav-item">
					<a data-toggle="pill" href="#nav-tab-card" class="nav-link <?php echo ($active_tab == 'credit_card') ? 'active' : ''; ?>"><i class="icon-credit-card"></i>&nbsp;&nbsp;<?php echo trans("credit_card"); ?></a>
				</li>
				<li class="nav-item">
					<a data-toggle="pill" href="#nav-tab-boleto" class="nav-link <?php echo ($active_tab != 'credit_card') ? 'active' : ''; ?>"><i class="icon-bank"></i>&nbsp;&nbsp;<?php echo trans("boleto"); ?></a>
				</li>
			</ul>
			<!-- End -->

			<!-- Credit card form content -->
			<div class="tab-content">

				<div id="nav-tab-card" class="tab-pane fade show <?php echo ($active_tab == 'credit_card') ? 'active' : 'fade'; ?>">
					<?php echo form_open('cart_controller/pagseguro_payment_post', ['id' => 'form_validate', 'class' => 'form-checkout']); ?>
					<input type="hidden" name="mds_payment_type" value="<?php echo $mds_payment_type; ?>">
					<div class="row">
						<div class="col-12 text-center">
							<?php if ($this->session->flashdata('error_credit_card')): ?>
								<div class="alert alert-dark alert-dismissible fade show" role="alert">
									<?php echo $this->session->flashdata('error_credit_card'); ?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<input type="hidden" name="payment_type" value="credit_card">
					<input type="hidden" name="brand">
					<input type="hidden" name="token">
					<input type="hidden" name="senderHash">
					<input type="hidden" name="total_amount" value="<?php echo price_format_decimal($total_amount); ?>">

					<div class="row">
						<div class="form-group col-12">
							<input type="text" name="full_name" class="form-control form-input input-field-card" value="<?php echo old('full_name'); ?>" placeholder="<?php echo trans("name_on_the_card"); ?>" maxlength="200" required>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-12">
							<div class="input-group">
								<input type="text" name="cardNumber" class="form-control form-input input-field-card input-card-number" value="<?php echo old('cardNumber'); ?>" placeholder="<?php echo trans("card_number"); ?>" required>
								<div class="input-group-append">
									<span class="input-group-text text-muted card-input-logos">
										<img src="<?php echo base_url(); ?>assets/img/payment/visa.svg" alt="visa">
										<img src="<?php echo base_url(); ?>assets/img/payment/mastercard.svg" alt="mastercard">
										<img src="<?php echo base_url(); ?>assets/img/payment/amex.svg" alt="amex">
									</span>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-6 col-12">
							<input type="text" name="cardExpiry" class="form-control form-input input-field-card input-expiry-date" value="<?php echo old('cardExpiry'); ?>" placeholder="<?php echo trans("expiration_date"); ?>" required>
						</div>
						<div class="form-group col-sm-6 col-12">
							<input type="text" name="cardCVC" class="form-control form-input input-field-card input-cvv" value="<?php echo old('cardCVC'); ?>" placeholder="<?php echo trans("cvv"); ?>" required>
							<div class="icon-cvv">
								<i class="icon-question-circle"></i>
							</div>
							<div class="cvv-code-container">
								<img src="<?php echo base_url(); ?>assets/img/payment/code.png" alt="code">
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="cpf" class="form-control form-input input-field-card input-cpf" value="<?php echo old('cpf'); ?>" placeholder="<?php echo trans("cpf"); ?>" required>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="email" name="email" class="form-control form-input input-field-card" value="<?php echo old('email'); ?>" placeholder="<?php echo trans("email"); ?>" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<input type="text" name="date_of_birth" class="form-control form-input input-field-card input-date" value="<?php echo old('date_of_birth'); ?>" placeholder="<?php echo trans("date_of_birth"); ?>" required>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="text" name="phone" class="form-control form-input input-field-card input-cell-phone" value="<?php echo old('phone'); ?>" placeholder="<?php echo trans("cell_phone"); ?>" required>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="city" class="form-control form-input input-field-card" value="<?php echo old('city'); ?>" placeholder="<?php echo trans("city"); ?>" maxlength="100" required>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="text" name="postal_code" class="form-control form-input input-field-card input-postal-code" value="<?php echo old('postal_code'); ?>" placeholder="<?php echo trans("postal_code"); ?>" maxlength="100" required>
							</div>
						</div>
					</div>

					<button type="submit" name="creditCard" value="creditCard" class="btn btn-custom btn-block shadow-sm"><?php echo trans("confirm_payment"); ?></button>

					<?php echo form_close(); ?>

				</div>

				<div id="nav-tab-boleto" class="tab-pane <?php echo ($active_tab != 'credit_card') ? 'active' : 'fade'; ?>">
					<?php echo form_open('cart_controller/pagseguro_payment_post', ['id' => 'form_validate_checkout', 'class' => 'form-checkout']); ?>
					<input type="hidden" name="mds_payment_type" value="<?php echo $mds_payment_type; ?>">
					<div class="row">
						<div class="col-12 text-center">
							<?php if ($this->session->flashdata('error_boleto')): ?>
								<div class="alert alert-dark alert-dismissible fade show" role="alert">
									<?php echo $this->session->flashdata('error_boleto'); ?>
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
								</div>
							<?php endif; ?>
						</div>
					</div>

					<input type="hidden" name="payment_type" value="boleto">
					<input type="hidden" name="senderHash">
					<input type="hidden" name="total_amount" value="<?php echo price_format_decimal($total_amount); ?>">

					<div class="row">
						<div class="form-group col-12">
							<input type="text" name="full_name" class="form-control form-input input-field-card" value="<?php echo old('full_name'); ?>" placeholder="<?php echo trans("name_on_the_card"); ?>" maxlength="200" required>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-12">
							<input type="email" name="email" class="form-control form-input input-field-card" value="<?php echo old('email'); ?>" placeholder="<?php echo trans("email"); ?>" required>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<div class="input-group">
									<input type="text" name="cpf" class="form-control form-input input-field-card input-cpf-1" value="<?php echo old('cpf'); ?>" placeholder="<?php echo trans("cpf"); ?>" required>
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<input type="text" name="phone" class="form-control form-input input-field-card input-cell-phone-1" value="<?php echo old('phone'); ?>" placeholder="<?php echo trans("cell_phone"); ?>" required>
							</div>
						</div>
					</div>

					<button type="submit" name="boleto" value="creditCard" class="btn btn-custom btn-block shadow-sm"><?php echo trans("confirm_payment"); ?></button>
					<?php echo form_close(); ?>
				</div>

			</div>
		</div>
	</div>

	<?php if ($this->payment_settings->pagseguro_mode == 'production'): ?>
		<script type="text/javascript" src="https://stc.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
	<?php else: ?>
		<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>
	<?php endif; ?>

	<?php if (!empty($session_code)): ?>
		<script>
            $(document).on('keyup change', '.input-field-card', function (e) {
                var senderHash = PagSeguroDirectPayment.getSenderHash();
                $("input[name='senderHash']").val(senderHash);

                //Criar uma sessão
                PagSeguroDirectPayment.setSessionId('<?php echo $session_code;?>');
                //Obter meios de pagamentos
                PagSeguroDirectPayment.getPaymentMethods({
                    success: function (json) {
                    },
                    error: function (json) {
                        var erro = "";
                        for (i in json.errors) {
                            erro = erro + json.errors[i];
                        }
                    },
                    complete: function (json) {
                    }
                });

                //Consultar a bandeira do cartão.
                PagSeguroDirectPayment.getBrand({
                    cardBin: $("input[name='cardNumber']").val().replace(/ /g, ''),
                    success: function (json) {
                        var brand = json.brand.name;

                        $("input[name='brand']").val(brand);

                        var param = {
                            cardNumber: $("input[name='cardNumber']").val().replace(/ /g, ''),
                            brand: brand,
                            cvv: $("input[name='cardCVC']").val(),
                            expirationMonth: $("input[name='cardExpiry']").val().split('/')[0],
                            expirationYear: $("input[name='cardExpiry']").val().split('/')[1],
                            success: function (json) {
                                var token = json.card.token;
                                $("input[name='token']").val(token);
                            },
                            error: function (json) {
                            },
                            complete: function (json) {
                            }
                        }
                        //Obter Token do Cartão de Crédito
                        PagSeguroDirectPayment.createCardToken(param);
                    },
                    error: function (json) {
                    },
                    complete: function (json) {
                    }
                });
            });
		</script>
	<?php endif; ?>

	<script src="<?php echo base_url(); ?>assets/js/cleave.min.js"></script>
	<script>
        new Cleave('.input-card-number', {
            creditCard: true
        });

        new Cleave('.input-expiry-date', {
            date: true,
            datePattern: ['m', 'Y'],
            delimiter: '/'
        });

        new Cleave('.input-cvv', {
            numericOnly: true,
            blocks: [3]
        });

        new Cleave('.input-cpf', {
            numericOnly: true,
            blocks: [20]
        });
        new Cleave('.input-cpf-1', {
            numericOnly: true,
            blocks: [20]
        });
        new Cleave('.input-date', {
            date: true,
            datePattern: ['d', 'm', 'Y'],
            delimiter: '/'
        });

        new Cleave('.input-cell-phone', {
            numericOnly: true,
            blocks: [0, 2, 0, 9],
            delimiters: ["(", ")", " - "]
        });
        new Cleave('.input-cell-phone-1', {
            numericOnly: true,
            blocks: [0, 2, 0, 9],
            delimiters: ["(", ")", " - "]
        });
        new Cleave('.input-postal-code', {
            numericOnly: true,
            blocks: [5, 3],
            delimiters: ["-"]
        });

        $(".icon-cvv i").hover(function () {
            $(".cvv-code-container").show();
        }, function () {
            $(".cvv-code-container").hide();
        });
	</script>
<?php endif; ?>

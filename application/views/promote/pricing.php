<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div id="content" class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb">
					</ol>
				</nav>
				<div class="row justify-content-center">
					<div class="col-12 col-md-12 col-lg-10">
						<!-- include message block -->
						<?php $this->load->view('product/_messages'); ?>
					</div>
				</div>
				<h1 class="page-title page-title-product"><?php echo trans("promote_your_product"); ?></h1>
				<p class="payment-wait"><?php echo trans("please_wait"); ?></p>
				<div class="form-add-product">
					<div class="row justify-content-center">
						<div class="col-12 col-md-12 col-lg-10">
							<div class="row justify-content-center">
								<?php if ($type == "new"): ?>
									<div class="col-12 col-sm-6 col-md-4 m-b-30">
										<div id="pricing_card_1" class="card pricing-card selected-card">
											<div class="card-header">
												<h3 class="title">
													<span class="currency"><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?></span><span>0</span>
												</h3>
											</div>
											<div class="card-block">
												<h4 class="card-title">
													<?php echo trans("free_plan"); ?>
												</h4>
												<ul class="list-group">
													<li class="list-group-item"><?php echo trans("free_listing"); ?></li>
												</ul>
												<a href="javascript:void(0)" class="btn btn-pricing-button" data-pricing-card="pricing_card_1"><?php echo trans("choose_plan"); ?></a>
											</div>
										</div>
									</div>
								<?php endif; ?>
								<div class="col-12 col-sm-6 col-md-4 m-b-30">
									<div id="pricing_card_2" class="card pricing-card <?php echo ($type == "exist") ? 'selected-card' : ''; ?>">
										<div class="card-header">
											<h3 class="title">
												<?php if ($payment_settings->free_product_promotion == 1): ?>
													<span class="currency"><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?></span><span>0</span><span class="period">/<?php echo trans("day"); ?></span>
												<?php else: ?>
													<span class="currency"><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?></span><span><?php echo price_format($payment_settings->price_per_day); ?></span><span class="period">/<?php echo trans("day"); ?></span>
												<?php endif; ?>
											</h3>
										</div>
										<div class="card-block">
											<h4 class="card-title">
												<?php echo trans("daily_plan"); ?>
											</h4>
											<ul class="list-group">
												<li class="list-group-item"><?php echo trans("promoted_badge"); ?></li>
												<li class="list-group-item"><?php echo trans("appear_on_homepage"); ?></li>
												<li class="list-group-item"><?php echo trans("show_first_search_lists"); ?></li>
											</ul>
											<a href="javascript:void(0)" class="btn btn-pricing-button" data-pricing-card="pricing_card_2"><?php echo trans("choose_plan"); ?></a>
										</div>
									</div>
								</div>
								<div class="col-12 col-sm-6 col-md-4 m-b-30">
									<div id="pricing_card_3" class="card pricing-card">
										<div class="card-header">
											<h3 class="title">
												<?php if ($payment_settings->free_product_promotion == 1): ?>
													<span class="currency"><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?></span><span>0</span><span class="period">/<?php echo trans("month"); ?></span>
												<?php else: ?>
													<span class="currency"><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?></span><span><?php echo price_format($payment_settings->price_per_month); ?></span><span class="period">/<?php echo trans("month"); ?></span>
												<?php endif; ?>
											</h3>
										</div>
										<div class="card-block">
											<h4 class="card-title">
												<?php echo trans("monthly_plan"); ?>
											</h4>
											<ul class="list-group">
												<li class="list-group-item"><?php echo trans("promoted_badge"); ?></li>
												<li class="list-group-item"><?php echo trans("appear_on_homepage"); ?></li>
												<li class="list-group-item"><?php echo trans("show_first_search_lists"); ?></li>
											</ul>
											<a href="javascript:void(0)" class="btn btn-pricing-button" data-pricing-card="pricing_card_3"><?php echo trans("choose_plan"); ?></a>
										</div>
									</div>
								</div>
							</div>

							<?php
							$price_per_day = price_format($payment_settings->price_per_day);
							$price_per_month = price_format($payment_settings->price_per_month);
							?>
							<input type="hidden" id="price_per_day" value="<?php echo $price_per_day; ?>">
							<input type="hidden" id="price_per_month" value="<?php echo $price_per_month; ?>">

							<div class="container-pricing-card" id="container_pricing_card_1" <?php echo ($type == "exist") ? 'style="display:none;"' : ''; ?>>
								<div class="row">
									<div class="col-12">
										<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
										<input type="hidden" name="plan_type" value="free">
										<a href="<?php echo lang_base_url() . $product->slug; ?>" class="btn btn-lg btn-custom float-right m-r-10"><?php echo trans("submit"); ?></a>
									</div>
								</div>
							</div>

							<div class="container-pricing-card" id="container_pricing_card_2" <?php echo ($type == "exist") ? 'style="display:block;"' : ''; ?>>
								<?php echo form_open('promote_controller/pricing_post', ['onkeypress' => "return event.keyCode != 13;"]); ?>
								<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
								<input type="hidden" name="plan_type" value="daily">
								<div class="row">
									<div class="col-12 col-sm-6 m-b-15"></div>
									<div class="col-12 col-sm-6 m-b-15">
										<div class="form-group">
											<label class="control-label"><?php echo trans("day_count"); ?></label>
											<input type="number" id="pricing_day_count" name="day_count" class="form-control form-input price-input" min="1" value="1" maxlength="5" required>
										</div>
									</div>
									<?php if ($payment_settings->free_product_promotion != 1): ?>
										<div class="col-12 text-right">
											<?php if ($payment_settings->currency_symbol_format == "left"): ?>
												<strong class="price-total"><?php echo trans("total_amount"); ?>&nbsp;<?php echo get_currency($payment_settings->promoted_products_payment_currency); ?><span class="span-price-total-daily"><?php echo $price_per_day; ?></span>&nbsp;<?php echo $payment_settings->promoted_products_payment_currency; ?></strong>
											<?php else: ?>
												<strong class="price-total"><?php echo trans("total_amount"); ?>&nbsp;<span class="span-price-total-daily"><?php echo $price_per_day; ?></span><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?>&nbsp;<?php echo $payment_settings->promoted_products_payment_currency; ?></strong>
											<?php endif; ?>
										</div>
										<div class="col-12 m-t-30">
											<button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("continue_to_checkout"); ?></button>
										</div>
									<?php else: ?>
										<div class="col-12">
											<button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("submit"); ?></button>
										</div>
									<?php endif; ?>
								</div>
								<?php echo form_close(); ?><!-- form end -->
							</div>

							<div class="container-pricing-card" id="container_pricing_card_3">
								<?php echo form_open('promote_controller/pricing_post', ['onkeypress' => "return event.keyCode != 13;"]); ?>
								<input type="hidden" name="product_id" value="<?php echo $product->id; ?>">
								<input type="hidden" name="plan_type" value="monthly">
								<div class="row">
									<div class="col-12 col-sm-6 m-b-15"></div>
									<div class="col-12 col-sm-6 m-b-15">
										<div class="form-group">
											<label class="control-label"><?php echo trans("month_count"); ?></label>
											<input type="number" id="pricing_month_count" name="month_count" class="form-control form-input price-input" min="1" value="1" required>
										</div>
									</div>
									<?php if ($payment_settings->free_product_promotion != 1): ?>
										<div class="col-12 text-right">
											<?php if ($payment_settings->currency_symbol_format == "left"): ?>
												<strong class="price-total"><?php echo trans("total_amount"); ?>&nbsp;<?php echo get_currency($payment_settings->promoted_products_payment_currency); ?><span class="span-price-total-monthly"><?php echo $price_per_month; ?></span>&nbsp;<?php echo $payment_settings->promoted_products_payment_currency; ?></strong>
											<?php else: ?>
												<strong class="price-total"><?php echo trans("total_amount"); ?>&nbsp;<span class="span-price-total-monthly"><?php echo $price_per_month; ?></span><?php echo get_currency($payment_settings->promoted_products_payment_currency); ?>&nbsp;<?php echo $payment_settings->promoted_products_payment_currency; ?></strong>
											<?php endif; ?>
										</div>
										<div class="col-12 m-t-30">
											<button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("continue_to_checkout"); ?></button>
										</div>
									<?php else: ?>
										<div class="col-12">
											<button type="submit" class="btn btn-lg btn-custom float-right"><?php echo trans("submit"); ?></button>
										</div>
									<?php endif; ?>

								</div>
								<?php echo form_close(); ?><!-- form end -->
							</div>

						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<!-- Wrapper End-->
<script src="<?php echo base_url(); ?>assets/vendor/jquery-number/jquery.number.min.js"></script>

<script>
    $(document).on('click', '.btn-pricing-button', function () {
        var pricing_card = $(this).attr("data-pricing-card");
        $('.pricing-card').removeClass('selected-card');
        $('#' + pricing_card).addClass('selected-card');
        $('.container-pricing-card').hide();
        $('#container_' + pricing_card).show();
    });

    $("#pricing_day_count").on("input keypress paste change", function () {
        var day_count = $("#pricing_day_count").val();
        if (day_count > 1440) {
            day_count = 1440;
            $("#pricing_day_count").val('1440');
        }
        var price_per_day = '<?php echo price_format_decimal($payment_settings->price_per_day); ?>';
        var calculated = day_count * price_per_day;
        if (!Number.isInteger(calculated)) {
            calculated = calculated.toFixed(2);
        }
		<?php if($this->thousands_separator == ','): ?>
        var calculated_formatted = $.number(calculated, 2, ',', '.');
		<?php else: ?>
        var calculated_formatted = $.number(calculated, 2, '.', ',');
		<?php endif; ?>
        $(".span-price-total-daily").text(calculated_formatted);
    });

    $("#pricing_month_count").on("input keypress paste change", function () {
        var month_count = $("#pricing_month_count").val();
        if (month_count > 48) {
            month_count = 48;
            $("#pricing_month_count").val('48');
        }
        var price_per_month = '<?php echo price_format_decimal($payment_settings->price_per_month); ?>';
        var calculated = month_count * price_per_month;
        if (!Number.isInteger(calculated)) {
            calculated = calculated.toFixed(2);
        }
		<?php if($this->thousands_separator == ','): ?>
        var calculated_formatted = $.number(calculated, 2, ',', '.');
		<?php else: ?>
        var calculated_formatted = $.number(calculated, 2, '.', ',');
		<?php endif; ?>
        $(".span-price-total-monthly").text(calculated_formatted);
    });
</script>

<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb breadcrumb-products">
						<li class="breadcrumb-item"><a
								href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
						<?php if (!empty($category)):
							$breadcrumb = get_parent_categories_array($category->id);
							if (!empty($breadcrumb)):
								foreach ($breadcrumb as $item_breadcrumb):
									$item_category = get_category_joined($item_breadcrumb->id);
									if (!empty($item_category)):?>
										<li class="breadcrumb-item"><a
												href="<?php echo generate_category_url($item_category); ?>"><?php echo html_escape($item_category->name); ?></a>
										</li>
									<?php endif;
								endforeach;
							endif;
						endif; ?>
					</ol>
				</nav>
			</div>
		</div>

		<div class="row">
			<div class="col-12">

				<div class="row row-product-details">
					<div class="col-12 col-sm-12 col-md-7 col-lg-8">
						<div class="product-content-left">
							<div class="row">
								<div class="col-12">
									<?php $this->load->view("product/details/_preview"); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<div class="product-content-details product-content-details-mobile"
										 style="display: none">
										<?php $this->load->view("product/details/_product_details_mobile"); ?>
									</div>
								</div>
							</div>

							<div class="product-description-container">
								<?php $this->load->view("product/details/_description"); ?>
							</div>

							<div class="row">
								<div class="col-12">
									<?php if ($general_settings->product_reviews == 1 || $general_settings->product_comments == 1 || $general_settings->facebook_comment_status == 1): ?>
										<div class="product-reviews">
											<!-- Nav tabs -->
											<ul class="nav nav-tabs">
												<?php if ($general_settings->product_reviews == 1): ?>
													<li class="nav-item">
														<a class="nav-link active" data-toggle="tab"
														   href="#reviews"><?php echo trans("reviews"); ?></a>
													</li>
												<?php endif; ?>
												<?php if ($general_settings->product_comments == 1): ?>
													<li class="nav-item">
														<a class="nav-link <?php echo ($general_settings->product_reviews != 1) ? 'active' : ''; ?>"
														   data-toggle="tab" href="#comments">
															<?php echo trans("comments"); ?>
														</a>
													</li>
												<?php endif; ?>
												<?php if ($general_settings->facebook_comment_status == 1): ?>
													<li class="nav-item">
														<a class="nav-link <?php echo ($general_settings->product_reviews != 1 && $general_settings->product_comments != 1) ? 'active' : ''; ?>"
														   data-toggle="tab" href="#facebook_comments">
															<?php echo trans("facebook_comments"); ?>
														</a>
													</li>
												<?php endif; ?>
											</ul>
											<!-- Tab panes -->
											<div class="tab-content">
												<?php if ($general_settings->product_reviews == 1): ?>
													<div class="tab-pane container active" id="reviews">
														<!-- include reviews -->
														<div id="review-result">
															<?php $this->load->view('product/details/_make_review'); ?>
														</div>
													</div>
												<?php endif; ?>
												<?php if ($general_settings->product_comments == 1): ?>
													<div
														class="tab-pane container <?php echo ($general_settings->product_reviews != 1) ? 'active' : 'fade'; ?>"
														id="comments">
														<!-- include comments -->
														<?php $this->load->view('product/details/_make_comment'); ?>
														<div id="comment-result">
															<?php $this->load->view('product/details/_comments'); ?>
														</div>
													</div>
												<?php endif; ?>
												<?php if ($general_settings->facebook_comment_status == 1): ?>
													<div
														class="tab-pane container <?php echo ($general_settings->product_reviews != 1 && $general_settings->product_comments != 1) ? 'active' : 'fade'; ?>"
														id="facebook_comments">
														<div class="fb-comments"
															 data-href="<?php echo current_url(); ?>" data-width="100%"
															 data-numposts="5"
															 data-colorscheme="light"></div>
													</div>
												<?php endif; ?>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 col-lg-4">
						<div class="product-content-right">
							<div class="row">
								<div class="col-12">
									<div class="product-content-details">
										<?php $this->load->view("product/details/_product_details"); ?>
									</div>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<?php $this->load->view("product/details/_seller"); ?>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<?php if (!empty($product->country_id)): ?>
										<div class="widget-location">
											<h4 class="sidebar-title"><?php echo trans("location"); ?></h4>
											<div class="sidebar-map">
												<!--load map-->
												<iframe
													src="https://maps.google.com/maps?width=100%&height=600&hl=en&q=<?php echo get_location($product); ?>&ie=UTF8&t=&z=8&iwloc=B&output=embed&disableDefaultUI=true"
													frameborder="0" scrolling="no" marginheight="0"
													marginwidth="0"></iframe>
											</div>
										</div>
									<?php endif; ?>
								</div>
							</div>

							<div class="row">
								<div class="col-12">
									<div class="row-custom">
										<!--Include banner-->
										<?php $this->load->view("partials/_ad_spaces_sidebar", ["ad_space" => "product_sidebar", "class" => "m-b-5"]); ?>
									</div>
								</div>
							</div>


						</div>

					</div>
				</div>
			</div>

			<div class="col-12">
				<div class="related-products">
					<h4 class="section-title"><?php echo trans("related_products"); ?></h4>
					<div class="row row-product">
						<!--print related posts-->
						<?php
						$count = 0;
						foreach ($related_products as $item):
							if ($count < 4):
								$item = @(object)$item;
								if (!empty($item)):?>
									<div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
										<?php $this->load->view('product/_product_item', ['product' => $item]); ?>
									</div>
								<?php endif;
							endif;
							$count++;
						endforeach; ?>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>
<!-- Wrapper End-->

<!-- include send message modal -->
<?php $this->load->view("partials/_modal_send_message", ["subject" => $product->title]); ?>
<script>
    $(".fb-comments").attr("data-href", window.location.href);
</script>
<?php
if ($general_settings->facebook_comment_status == 1) {
	echo $general_settings->facebook_comment;
} ?>

<script src="<?php echo base_url(); ?>assets/vendor/touchspin/jquery.bootstrap-touchspin.min.js"></script>
<script>
    $("#quantity_touchspin").TouchSpin({
        min: 1,
        max: <?php echo $product->quantity; ?>,
        verticalbuttons: true,
        verticalupclass: 'icon-arrow-up',
        verticaldownclass: 'icon-arrow-down'
    });
    $("#quantity_touchspin").change(function () {
        var count = $(this).val();
        $("#form_add_cart input[name='product_quantity']").val(count);
    });
    $("#quantity_touchspin_mobile").TouchSpin({
        min: 1,
        max: <?php echo $product->quantity; ?>,
        verticalbuttons: true,
        verticalupclass: 'icon-arrow-up',
        verticaldownclass: 'icon-arrow-down'
    });
    $("#quantity_touchspin_mobile").change(function () {
        var count = $(this).val();
        $("#form_add_cart_mobile input[name='product_quantity']").val(count);
    });
</script>

<!-- Plyr JS-->
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.min.js"></script>
<script src="<?php echo base_url(); ?>assets/vendor/plyr/plyr.polyfilled.min.js"></script>
<script>
    const player = new Plyr('#player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#player');
    });
    const audio_player = new Plyr('#audio_player');
    $(document).ajaxStop(function () {
        const player = new Plyr('#audio_player');
    });
</script>

<script>
    $(function () {
        $('.product-description iframe').wrap('<div class="embed-responsive embed-responsive-16by9"></div>');
        $('.product-description iframe').addClass('embed-responsive-item');
    });
</script>

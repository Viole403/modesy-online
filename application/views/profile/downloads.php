<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
						<li class="breadcrumb-item active" aria-current="page"><?php echo trans("profile"); ?></li>
					</ol>
				</nav>
			</div>
		</div>
		<div class="row">
			<div class="col-12">
				<div class="profile-page-top">
					<!-- load profile details -->
					<?php $this->load->view("profile/_profile_user_info"); ?>
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-sm-12 col-md-3">
				<!-- load profile nav -->
				<?php $this->load->view("profile/_profile_tabs"); ?>
			</div>

			<div class="col-sm-12 col-md-9">
				<div class="profile-tab-content">
					<?php
					if (!empty($items)):
						foreach ($items as $item):
							$product = get_available_product($item->product_id);
							if (!empty($product)):?>
								<div class="product-item product-item-horizontal">
									<div class="row">
										<div class="col-12 col-sm-4">
											<div class="item-image">
												<a href="<?php echo generate_product_url($product); ?>">
													<div class="img-product-container">
														<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_product_image($product->id, 'image_small'); ?>" alt="<?php echo html_escape($product->title); ?>" class="lazyload img-fluid img-product" onerror="this.src='<?php echo $img_bg_product_small; ?>'">
													</div>
												</a>
											</div>
										</div>
										<div class="col-12 col-sm-8">
											<div class="row-custom item-details">
												<h3 class="product-title m-0">
													<a href="<?php echo generate_product_url($product); ?>">
														<?php echo html_escape($product->title); ?>
													</a>
												</h3>
												<p class="product-user text-truncate m-t-0">
													<a href="<?php echo lang_base_url() . "profile" . '/' . html_escape($product->user_slug); ?>">
														<?php echo get_shop_name_product($product); ?>
													</a>
												</p>
												<!--stars-->
												<?php if ($general_settings->product_reviews == 1) {
													$this->load->view('partials/_review_stars', ['review' => $product->rating]);
												} ?>
												<div class="item-meta m-t-5">
                                                    <span class="price"><?php echo print_price($product->price, $product->currency); ?>
														<?php if ($product->is_sold == 1): ?>
															<span>(<?php echo trans("sold"); ?>)</span>
														<?php endif; ?>
                                                    </span>
													<?php if ($general_settings->product_reviews == 1): ?>
														<span class="item-comments"><i class="icon-comment"></i>&nbsp;<?php echo get_product_comment_count($product->id); ?></span>
													<?php endif; ?>
													<span class="item-favorites"><i class="icon-heart-o"></i>&nbsp;<?php echo get_product_favorited_count($product->id); ?></span>
												</div>
											</div>
											<div class="row-custom m-t-30">
												<?php echo form_open('file_controller/download_purchased_digital_file'); ?>
												<input type="hidden" name="sale_id" value="<?php echo $item->id; ?>">
												<div class="btn-group btn-group-download">
													<button type="button" class="btn btn-md btn-custom dropdown-toggle" data-toggle="dropdown">
														<i class="icon-download-solid"></i><?php echo trans("download"); ?>&nbsp;&nbsp;<i class="icon-arrow-down m-0"></i>
													</button>
													<div class="dropdown-menu">
														<button name="submit" value="main_files" class="dropdown-item"><?php echo trans("main_files"); ?></button>
														<button name="submit" value="license_certificate" class="dropdown-item"><?php echo trans("license_certificate"); ?></button>
													</div>
												</div>
												<?php echo form_close(); ?>
											</div>
										</div>
									</div>
								</div>
							<?php endif;
						endforeach;
					endif; ?>
				</div>

				<div class="product-list-pagination">
					<?php echo $this->pagination->create_links(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Wrapper End-->



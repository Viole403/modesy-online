<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Wrapper -->
<div id="wrapper" class="index-wrapper">
	<div class="container container-slider">
		<?php if (!empty($slider_items) && $general_settings->index_slider == 1): ?>
			<div class="section section-slider">
				<!-- main slider -->
				<?php $this->load->view("partials/_main_slider"); ?>
			</div>
		<?php endif; ?>
	</div>

	<div class="container">
		<div class="row">
			<h1 class="index-title"><?php echo html_escape($settings->site_title); ?></h1>
			<?php if ($featured_category_count > 0 && $general_settings->index_categories == 1): ?>
				<div class="col-12 section section-categories">
					<!-- featured categories -->
					<?php $this->load->view("partials/_featured_categories"); ?>
				</div>
			<?php endif; ?>
			<div class="col-12">
				<div class="row-custom row-bn">
					<!--Include banner-->
					<?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_1", "class" => ""]); ?>
				</div>
			</div>
			<?php if ($general_settings->index_promoted_products == 1 && $promoted_products_enabled == 1 && !empty($promoted_products)): ?>
				<div class="col-12 section section-promoted">
					<!-- promoted products -->
					<?php $this->load->view("product/_promoted_products"); ?>
				</div>
			<?php endif; ?>
			<?php if ($general_settings->index_latest_products == 1 && !empty($latest_products)): ?>
				<div class="col-12 section section-latest-products">
					<h3 class="title"><?php echo trans("latest_products"); ?></h3>
					<p class="title-exp"><?php echo trans("latest_products_exp"); ?></p>
					<div class="row row-product">
						<!--print products-->
						<?php foreach ($latest_products as $product): ?>
							<div class="col-6 col-sm-6 col-md-4 col-lg-3 col-product">
								<?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => false]); ?>
							</div>
						<?php endforeach; ?>
					</div>

					<div class="row-custom text-center">
						<a href="<?php echo lang_base_url() . "products"; ?>" class="link-see-more"><span><?php echo trans("see_more"); ?>&nbsp;</span><i class="icon-arrow-right"></i></a>
					</div>
				</div>
			<?php endif; ?>
			<div class="col-12">
				<div class="row-custom row-bn">
					<!--Include banner-->
					<?php $this->load->view("partials/_ad_spaces", ["ad_space" => "index_2", "class" => ""]); ?>
				</div>
			</div>
			<?php if ($general_settings->index_blog_slider == 1 && !empty($blog_slider_posts)): ?>
				<div class="col-12 section section-blog m-0">
					<h3 class="title"><?php echo trans("latest_blog_posts"); ?></h3>
					<p class="title-exp"><?php echo trans("latest_blog_posts_exp"); ?></p>
					<div class="row-custom">
						<!-- main slider -->
						<?php $this->load->view("blog/_blog_slider", ['blog_slider_posts' => $blog_slider_posts]); ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</div>
<!-- Wrapper End-->




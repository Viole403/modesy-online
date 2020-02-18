<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!-- Wrapper -->
<div id="wrapper">
	<div class="container">
		<div class="row">
			<div class="col-12">
				<nav class="nav-breadcrumb" aria-label="breadcrumb">
					<ol class="breadcrumb breadcrumb-products">
						<li class="breadcrumb-item"><a href="<?php echo lang_base_url(); ?>"><?php echo trans("home"); ?></a></li>
						<?php if (!empty($category)):
							$breadcrumb = get_parent_categories_array($category->id);
							if (!empty($breadcrumb)):
								foreach ($breadcrumb as $item_breadcrumb):
									$item_category = get_category_joined($item_breadcrumb->id);
									if (!empty($item_category)):?>
										<li class="breadcrumb-item"><a href="<?php echo generate_category_url($item_category); ?>"><?php echo html_escape($item_category->name); ?></a></li>
									<?php endif;
								endforeach;
							endif;
						else: ?>
							<li class="breadcrumb-item active" aria-current="page"><?php echo trans("products"); ?></li>
						<?php endif; ?>
					</ol>
				</nav>
			</div>
		</div>
		<!-- form start -->
		<?php echo form_open(current_url(), ['id' => 'form-product-filters', 'method' => 'get']);
		$search = trim($this->input->get('search', TRUE));
		if (!empty($search)):?>
			<input type="hidden" name="search" value="<?php echo html_escape($search); ?>">
		<?php endif; ?>
		<div class="row">
			<div class="col-12 product-list-header">
				<?php if (!empty($category)): ?>
					<h1 class="page-title product-list-title"><?php echo html_escape($category->name); ?></h1>
				<?php else: ?>
					<h1 class="page-title product-list-title"><?php echo trans("products"); ?></h1>
				<?php endif; ?>
				<div class="product-sort-by">
					<span class="span-sort-by"><?php echo trans("sort_by"); ?></span>
					<div class="dropdown sort-select">
						<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
							<?php $filter_sort = get_filter_query_string_key_value('sort');
							if ($filter_sort == 'most_recent' || $filter_sort == 'lowest_price' || $filter_sort == 'highest_price') {
								echo trans($filter_sort);
							} else {
								echo trans('most_recent');
							} ?>
						</button>
						<div class="dropdown-menu">
							<button type="submit" name="sort" value="most_recent" class="dropdown-item"><?php echo trans("most_recent"); ?></button>
							<button type="submit" name="sort" value="lowest_price" class="dropdown-item"><?php echo trans("lowest_price"); ?></button>
							<button type="submit" name="sort" value="highest_price" class="dropdown-item"><?php echo trans("highest_price"); ?></button>
						</div>
					</div>
				</div>
				<button class="btn btn-filter-products-mobile" type="button" data-toggle="collapse" data-target="#collapseFilters" aria-expanded="false" aria-controls="collapseFilters">
					<?php echo trans("filter_products"); ?>
				</button>
			</div>
		</div>

		<div class="row">
			<?php $this->load->view('product/_product_filters'); ?>

			<div class="col-12 col-md-9">
				<div class="filter-reset-tag-container">
					<?php $filters = get_filters_query_string_array();
					if (!empty($filters)):
						foreach ($filters as $key => $value):
							if (!empty($value) && $key != 'sort' && $key != 'condition' && $key != 'country' && $key != 'state' && $key != 'city' && $key != 'p_min' && $key != 'p_max' && $key != 'page'):?>
								<div class="filter-reset-tag">
									<div class="left">
										<a href="<?php echo remove_filter_from_query_string($key); ?>"><i class="icon-close"></i></a>
									</div>
									<div class="right">
										<span class="reset-tag-title"><?php echo get_filter_name_by_key($key); ?></span>
										<span><?php echo html_escape($value); ?></span>
									</div>
								</div>
							<?php endif;
						endforeach;
					endif;

					$filter_condition = get_filter_query_string_key_value('condition');
					if (!empty($filter_condition)):
						$product_condition = get_product_condition_by_key($filter_condition, $selected_lang->id);
						if (!empty($product_condition)):?>
							<div class="filter-reset-tag">
								<div class="left">
									<a href="<?php echo remove_filter_from_query_string('condition'); ?>"><i class="icon-close"></i></a>
								</div>
								<div class="right">
									<span class="reset-tag-title"><?php echo trans("condition"); ?></span>
									<span><?php echo html_escape($product_condition->option_label); ?></span>
								</div>
							</div>
						<?php endif;
					endif;

					$filter_p_max = @(float)get_filter_query_string_key_value('p_max');
					$filter_p_min = @(float)get_filter_query_string_key_value('p_min');
					if (!empty($filter_p_max) || !empty($filter_p_min)): ?>
						<div class="filter-reset-tag">
							<div class="left">
								<a href="<?php echo remove_filter_from_query_string('price'); ?>"><i class="icon-close"></i></a>
							</div>
							<div class="right">
								<span class="reset-tag-title"><?php echo trans('price') . '(' . get_currency($this->payment_settings->default_product_currency) . ')'; ?></span>
								<span>
                                <?php if ($filter_p_min != 0):
									echo trans('min') . ': ' . $filter_p_min;
								endif; ?>&nbsp;&nbsp;
                                <?php if ($filter_p_max != 0):
									echo trans('max') . ': ' . $filter_p_max;
								endif; ?>
                            </span>
							</div>
						</div>
					<?php endif;

					$country_id = $this->input->get('country', true);
					$state_id = $this->input->get('state', true);
					$city_id = $this->input->get('city', true);
					$filter_location = get_location_input($country_id, $state_id, $city_id);
					if (!empty($filter_location)): ?>
						<div class="filter-reset-tag">
							<div class="left">
								<a href="<?php echo remove_filter_from_query_string('location'); ?>"><i class="icon-close"></i></a>
							</div>
							<div class="right">
								<span class="reset-tag-title"><?php echo trans("location"); ?></span>
								<span><?php echo $filter_location; ?></span>
							</div>
						</div>
					<?php endif; ?>
				</div>
				<div class="product-list-content">
					<div class="row row-product">
						<!--print products-->
						<?php foreach ($products as $product): ?>
							<div class="col-6 col-sm-6 col-md-6 col-lg-4 col-product">
								<?php $this->load->view('product/_product_item', ['product' => $product, 'promoted_badge' => true]); ?>
							</div>
						<?php endforeach; ?>
						<?php if (empty($products)): ?>
							<div class="col-12">
								<p class="no-records-found"><?php echo trans("no_products_found"); ?></p>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="product-list-pagination">
					<div class="float-right">
						<?php echo $this->pagination->create_links(); ?>
					</div>
				</div>

				<div class="col-12">
					<!--Include banner-->
					<?php $this->load->view("partials/_ad_spaces", ["ad_space" => "products", "class" => "m-t-15"]); ?>
				</div>
			</div>
		</div>

		<?php echo form_close(); ?>
		<!-- form end -->
	</div>
</div>
<!-- Wrapper End-->

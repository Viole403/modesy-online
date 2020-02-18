<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php if ($general_settings->selected_navigation == 1): ?>
	<div class="container">
		<div class="navbar navbar-light navbar-expand">
			<ul class="nav navbar-nav mega-menu">
				<?php
				$limit = $general_settings->menu_limit;
				$count = 1;
				if (!empty($parent_categories)):
					foreach ($parent_categories as $category):
						if ($count <= $limit):?>
							<li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
								<a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
									<?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?>
								</a>
								<?php $subcategories = get_subcategories_by_parent_id($category->id);
								if (!empty($subcategories)): ?>
									<div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu">
										<div class="row">
											<div class="col-8 menu-subcategories col-category-links">
												<div class="card-columns">
													<?php foreach ($subcategories as $subcategory): ?>
														<div class="card">
															<div class="row">
																<div class="col-12">
																	<a href="<?php echo generate_category_url($subcategory); ?>" class="second-category"><?php echo html_escape($subcategory->name); ?></a>
																	<?php
																	$third_categories = get_subcategories_by_parent_id($subcategory->id);
																	if (!empty($third_categories)): ?>
																		<ul>
																			<?php foreach ($third_categories as $item): ?>
																				<li>
																					<a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
																				</li>
																			<?php endforeach; ?>
																		</ul>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													<?php endforeach; ?>
												</div>
											</div>
											<div class="col-4 col-category-images">
												<?php if (!empty($category->image_1) && $category->show_image_on_navigation == 1): ?>
													<div class="nav-category-image">
														<a href="<?php echo generate_category_url($category); ?>">
															<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($category, 'image_1'); ?>" alt="category" class="lazyload img-fluid">
															<span><?php echo html_escape(character_limiter(get_category_name_by_lang($category->id, $this->selected_lang->id), 20, '..')); ?></span>
														</a>
													</div>
												<?php endif; ?>
												<?php if (!empty($subcategories)):
													foreach ($subcategories as $subcategory):
														if (!empty($subcategory->image_1) && $subcategory->show_image_on_navigation == 1):?>
															<div class="nav-category-image">
																<a href="<?php echo generate_category_url($subcategory); ?>">
																	<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($subcategory, 'image_1'); ?>" alt="<?php echo html_escape($subcategory->name); ?>" class="lazyload img-fluid">
																	<span><?php echo html_escape(character_limiter($subcategory->name, 20, '..')); ?></span>
																</a>
															</div>
														<?php endif;
													endforeach;
												endif; ?>
												<?php if (!empty($subcategories)):
													foreach ($subcategories as $subcategory):
														$third_categories = get_subcategories_by_parent_id($subcategory->id);
														if (!empty($third_categories)):
															foreach ($third_categories as $third_category):
																if (!empty($third_category->image_1) && $third_category->show_image_on_navigation == 1):?>
																	<div class="nav-category-image">
																		<a href="<?php echo generate_category_url($third_category); ?>">
																			<img src="<?php echo $img_bg_product_small; ?>" data-src="<?php echo get_category_image_url($third_category, 'image_1'); ?>" alt="<?php echo html_escape($third_category->name); ?>" class="lazyload img-fluid">
																			<span><?php echo html_escape($third_category->name); ?></span>
																		</a>
																	</div>
																<?php endif;
															endforeach;
														endif;
													endforeach;
												endif; ?>

											</div>
										</div>
									</div>
								<?php endif; ?>
							</li>
							<?php $count++;
						endif;
					endforeach;

					if (count($parent_categories) > $limit): ?>
						<li class="nav-item dropdown menu-li-more">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo trans("more"); ?></a>
							<div class="dropdown-menu dropdown-menu-more-items">
								<?php
								$count = 1;
								if (!empty($parent_categories)):
									foreach ($parent_categories as $category):
										if ($count > $limit):?>
											<a href="<?php echo generate_category_url($category); ?>" class="dropdown-item">
												<?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?>
											</a>
										<?php
										endif;
										$count++;
									endforeach;
								endif; ?>
							</div>
						</li>
					<?php endif;
				endif; ?>
			</ul>
		</div>
	</div>
<?php else: ?>
	<div class="container">
		<div class="navbar navbar-light navbar-expand">
			<ul class="nav navbar-nav mega-menu">
				<?php
				$limit = $general_settings->menu_limit;
				$menu_item_count = 1;
				if (!empty($parent_categories)):
					foreach ($parent_categories as $category):
						if ($menu_item_count <= $limit):?>
							<li class="nav-item dropdown" data-category-id="<?php echo $category->id; ?>">
								<a href="<?php echo generate_category_url($category); ?>" class="nav-link dropdown-toggle">
									<?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?>
								</a>
								<?php $subcategories = get_subcategories_by_parent_id($category->id);
								if (!empty($subcategories)):?>
									<div id="mega_menu_content_<?php echo $category->id; ?>" class="dropdown-menu dropdown-menu-large">
										<div class="row">

											<div class="col-4 left">
												<?php
												$count = 0;
												foreach ($subcategories as $subcategory): ?>
													<div class="large-menu-item <?php echo ($count == 0) ? 'large-menu-item-first active' : ''; ?>" data-subcategory-id="<?php echo $subcategory->id; ?>">
														<a href="<?php echo generate_category_url($subcategory); ?>" class="second-category">
															<?php echo html_escape($subcategory->name); ?>
															<i class="icon-arrow-right"></i>
														</a>
													</div>
													<?php
													$count++;
												endforeach; ?>
											</div>

											<div class="col-8 right">
												<?php
												$count = 0;
												foreach ($subcategories as $subcategory): ?>
													<div id="large_menu_content_<?php echo $subcategory->id; ?>" class="large-menu-content <?php echo ($count == 0) ? 'large-menu-content-first active' : ''; ?>">
														<?php $third_categories = get_subcategories_by_parent_id($subcategory->id);
														if (!empty($third_categories)): ?>
															<div class="row">
																<?php foreach ($third_categories as $item): ?>
																	<div class="col-4 item-large-menu-content">
																		<a href="<?php echo generate_category_url($item); ?>"><?php echo html_escape($item->name); ?></a>
																	</div>
																<?php endforeach; ?>
															</div>
														<?php endif; ?>
													</div>
													<?php
													$count++;
												endforeach; ?>
											</div>

										</div>
									</div>
								<?php endif; ?>
							</li>
							<?php $menu_item_count++;
						endif;
					endforeach;

					if (count($parent_categories) > $limit): ?>
						<li class="nav-item dropdown menu-li-more">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false"><?php echo trans("more"); ?></a>
							<div class="dropdown-menu dropdown-menu-more-items">
								<?php
								$menu_item_count = 1;
								if (!empty($parent_categories)):
									foreach ($parent_categories as $category):
										if ($menu_item_count > $limit): ?>
											<a href="<?php echo generate_category_url($category); ?>" class="dropdown-item">
												<?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?>
											</a>
										<?php endif;
										$menu_item_count++;
									endforeach;
								endif; ?>
							</div>
						</li>
					<?php endif;
				endif; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>

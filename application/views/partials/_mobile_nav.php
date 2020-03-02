<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div id="navMobile" class="nav-mobile">
	<div class="mobile-nav-logo">
		<a href="<?php echo lang_base_url(); ?>"><img src="<?php echo get_logo($general_settings); ?>" alt="logo"></a>
	</div>
	<a href="javascript:void(0)" class="btn-close-mobile-nav"><i class="icon-close-thin"></i></a>
	<div class="nav-mobile-inner">
		<ul class="navbar-nav">
			<?php if (is_multi_vendor_active()):
				if ($this->auth_check): ?>
					<li class="nav-item item-sell-button m-b-15"><a href="<?php echo lang_base_url(); ?>sell-now" class="btn btn-md btn-custom btn-block"><?php echo trans("sell_now"); ?></a></li>
				<?php else: ?>
					<li class="nav-item item-sell-button m-b-15"><a href="javascript:void(0)" class="btn btn-md btn-custom btn-block close-mobile-nav" data-toggle="modal" data-target="#loginModal"><?php echo trans("sell_now"); ?></a></li>
				<?php endif;
			endif; ?>
			<li class="nav-item">
				<a href="<?php echo lang_base_url(); ?>" class="nav-link"><?php echo trans("home"); ?></a>
			</li>
			<?php if (!empty($parent_categories)):
				$count = 1;
				foreach ($parent_categories as $category):
					$subcategories = get_subcategories_by_parent_id($category->id);
					if (empty($subcategories)):?>
						<li class="nav-item">
							<a href="<?php echo generate_category_url($category); ?>" class="nav-link"><?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?></a>
						</li>
					<?php else: ?>
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<?php echo html_escape(get_category_name_by_lang($category->id, $this->selected_lang->id)); ?><i class="icon-arrow-down"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="navbarDropdown">
								<a class="dropdown-item" href="<?php echo generate_category_url($category); ?>"><?php echo trans("all"); ?></a>
								<?php foreach ($subcategories as $subcategory): ?>
									<a class="dropdown-item" href="<?php echo generate_category_url($subcategory); ?>"><?php echo html_escape($subcategory->name); ?></a>
								<?php endforeach; ?>
							</div>
						</li>
					<?php endif;
					$count++;
				endforeach;
			endif; ?>

			<li class="nav-item"><a href="<?php echo lang_base_url(); ?>blog" class="nav-link"><?php echo trans("blog"); ?></a></li>
			<?php if ($this->auth_check): ?>
				<li class="nav-item">
					<a href="<?php echo lang_base_url(); ?>favorites/<?php echo $this->auth_user->slug; ?>" class="nav-link">
						<?php echo trans("favorites"); ?>
					</a>
				</li>
			<?php else: ?>
				<li class="nav-item">
					<a href="<?php echo lang_base_url(); ?>favorites" class="nav-link">
						<?php echo trans("favorites"); ?>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->auth_check): ?>
				<li class="nav-item dropdown item-profile">
					<a class="nav-link dropdown-toggle" href="javascript:void(0)" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<img src="<?php echo get_user_avatar($this->auth_user); ?>" alt="<?php echo get_shop_name($this->auth_user); ?>"><span><?php echo get_shop_name($this->auth_user); ?></span>
						<?php if ($unread_message_count > 0): ?>
							<span class="notification"><?php echo $unread_message_count; ?></span>
						<?php endif; ?>
						<i class="icon-arrow-down"></i>
					</a>
					<div class="dropdown-menu" aria-labelledby="navbarDropdown">
						<?php if ($this->auth_user->role == "admin"): ?>
							<a class="dropdown-item" href="<?php echo admin_url(); ?>"><i class="icon-dashboard"></i><?php echo trans("admin_panel"); ?></a>
						<?php endif; ?>
						<a class="dropdown-item" href="<?php echo lang_base_url(); ?>profile/<?php echo $this->auth_user->slug; ?>"><i class="icon-user"></i><?php echo trans("view_profile"); ?></a>
						<?php if (is_sale_active()): ?>
							<a class="dropdown-item" href="<?php echo lang_base_url(); ?>orders"><i class="icon-shopping-basket"></i><?php echo trans("orders"); ?></a>
							<?php if (is_user_vendor()): ?>
								<a class="dropdown-item" href="<?php echo lang_base_url(); ?>sales"><i class="icon-shopping-bag"></i><?php echo trans("sales"); ?></a>
								<a class="dropdown-item" href="<?php echo lang_base_url(); ?>earnings"><i class="icon-wallet"></i><?php echo trans("earnings"); ?></a>
							<?php endif; ?>
							<?php if (is_bidding_system_active()): ?>
								<a class="dropdown-item" href="<?php echo lang_base_url(); ?>quote-requests"><i class="icon-price-tag-o"></i><?php echo trans("quote_requests"); ?></a>
							<?php endif; ?>
							<?php if ($general_settings->digital_products_system == 1): ?>
								<a class="dropdown-item" href="<?php echo lang_base_url(); ?>downloads"><i class="icon-download"></i><?php echo trans("downloads"); ?></a>
							<?php endif; ?>
						<?php endif; ?>
						<a class="dropdown-item" href="<?php echo lang_base_url(); ?>messages">
							<i class="icon-mail"></i><?php echo trans("messages"); ?>&nbsp;<?php if ($unread_message_count > 0): ?>
								<span class="span-message-count"><?php echo $unread_message_count; ?></span>
							<?php endif; ?>
						</a>
						<a class="dropdown-item" href="<?php echo lang_base_url(); ?>settings/update-profile"><i class="icon-settings"></i><?php echo trans("settings"); ?></a>
						<a class="dropdown-item" href="<?php echo base_url(); ?>logout"><i class="icon-logout"></i><?php echo trans("logout"); ?></a>
					</div>
				</li>
			<?php else: ?>
				<li class="nav-item"><a href="javascript:void(0)" data-toggle="modal" data-target="#loginModal" class="nav-link close-mobile-nav"><?php echo trans("login"); ?></a></li>
				<li class="nav-item"><a href="<?php echo lang_base_url(); ?>register" class="nav-link"><?php echo trans("register"); ?></a></li>
			<?php endif; ?>

		</ul>

		<div class="row row-region">
			<div class="col-12">
				<?php if ($general_settings->default_product_location == 0): ?>
					<div class="region-left">
						<div class="row-custom footer-location mobile-location">
							<div class="icon-text">
								<i class="icon-map-marker"></i>
							</div>
							<div class="dropdown">
								<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
									<?php echo $default_location; ?>&nbsp;<span class="icon-arrow-down"></span>
								</button>
								<div class="dropdown-menu">
									<a class="dropdown-item" href="javascript:void(0)" onclick="set_default_location('all');"><?php echo trans("all"); ?></a>
									<?php if (!empty($countries)): foreach ($countries as $item): ?>
										<a class="dropdown-item" href="javascript:void(0)" onclick="set_default_location('<?php echo $item->id; ?>');"><?php echo html_escape($item->name); ?></a>
									<?php endforeach;
									endif; ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>

				<div class="region-right">
					<?php if ($general_settings->multilingual_system == 1 && count($languages) > 1): ?>
						<div class="row-custom">
							<div class="dropdown language-dropdown mobile-language-dropdown">
								<button type="button" class="btn dropdown-toggle" data-toggle="dropdown">
									<i class="icon-language"></i>
									<?php echo html_escape($selected_lang->name); ?>&nbsp;<span class="icon-arrow-down"></span>
								</button>
								<div class="dropdown-menu">
									<?php foreach ($languages as $language):
										$lang_url = base_url() . $language->short_form . "/";
										if ($language->id == $this->general_settings->site_lang) {
											$lang_url = base_url();
										} ?>
										<a href="<?php echo $lang_url; ?>" class="<?php echo ($language->id == $selected_lang->id) ? 'selected' : ''; ?> " class="dropdown-item">
											<?php echo $language->name; ?>
										</a>
									<?php endforeach; ?>
								</div>
							</div>
						</div>
					<?php endif; ?>
				</div>
			</div>
		</div>

	</div>
</div>

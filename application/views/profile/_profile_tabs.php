<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!--profile page tabs-->
<div class="profile-tabs">
	<ul class="nav">
		<?php if (is_multi_vendor_active()): ?>
			<?php if ($user->role == 'admin' || $user->role == 'vendor'): ?>
				<li class="nav-item <?php echo ($active_tab == 'products') ? 'active' : ''; ?>">
					<a class="nav-link" href="<?php echo lang_base_url() . "profile/" . $user->slug; ?>">
						<span><?php echo trans("products"); ?></span>
						<span class="count">(<?php echo get_user_products_count($user->slug); ?>)</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
				<li class="nav-item <?php echo ($active_tab == 'pending_products') ? 'active' : ''; ?>">
					<a class="nav-link" href="<?php echo lang_base_url(); ?>pending-products">
						<span><?php echo trans("pending_products"); ?></span>
						<span class="count">(<?php echo get_user_pending_products_count($user->id); ?>)</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
				<li class="nav-item <?php echo ($active_tab == 'hidden_products') ? 'active' : ''; ?>">
					<a class="nav-link" href="<?php echo lang_base_url(); ?>hidden-products">
						<span><?php echo trans("hidden_products"); ?></span>
						<span class="count">(<?php echo get_user_hidden_products_count($user->id); ?>)</span>
					</a>
				</li>
			<?php endif; ?>
			<?php if ($this->auth_check && $this->auth_user->id == $user->id && ($user->role == 'admin' || $user->role == 'vendor')): ?>
				<li class="nav-item <?php echo ($active_tab == 'drafts') ? 'active' : ''; ?>">
					<a class="nav-link" href="<?php echo lang_base_url(); ?>drafts">
						<span><?php echo trans("drafts"); ?></span>
						<span class="count">(<?php echo get_user_drafts_count($user->id); ?>)</span>
					</a>
				</li>
			<?php endif; ?>
		<?php endif; ?>
		<li class="nav-item <?php echo ($active_tab == 'favorites') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo lang_base_url() . "favorites/" . $user->slug; ?>">
				<span><?php echo trans("favorites"); ?></span>
				<span class="count">(<?php echo get_user_favorited_products_count($user->id); ?>)</span>
			</a>
		</li>
		<?php if (is_multi_vendor_active()): ?>
			<?php if ($this->auth_check && $this->auth_user->id == $user->id && is_sale_active() && $general_settings->digital_products_system == 1): ?>
				<li class="nav-item <?php echo ($active_tab == 'downloads') ? 'active' : ''; ?>">
					<a class="nav-link" href="<?php echo lang_base_url(); ?>downloads">
						<span><?php echo trans("downloads"); ?></span>
						<span class="count">(<?php echo get_user_downloads_count($user->id); ?>)</span>
					</a>
				</li>
			<?php endif; ?>
		<?php endif; ?>
		<li class="nav-item <?php echo ($active_tab == 'followers') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo lang_base_url() . "followers/" . $user->slug; ?>">
				<span><?php echo trans("followers"); ?></span>
				<span class="count">(<?php echo get_followers_count($user->id); ?>)</span>
			</a>
		</li>
		<li class="nav-item <?php echo ($active_tab == 'following') ? 'active' : ''; ?>">
			<a class="nav-link" href="<?php echo lang_base_url() . "following/" . $user->slug; ?>">
				<span><?php echo trans("following"); ?></span>
				<span class="count">(<?php echo get_following_users_count($user->id); ?>)</span>
			</a>
		</li>
		<?php if (($general_settings->user_reviews == 1) && ($user->role == 'admin' || $user->role == 'vendor') && is_multi_vendor_active()): ?>
			<li class="nav-item <?php echo ($active_tab == 'reviews') ? 'active' : ''; ?>">
				<a class="nav-link" href="<?php echo lang_base_url() . "reviews/" . $user->slug; ?>">
					<span><?php echo trans("reviews"); ?></span>
					<span class="count">(<?php echo get_user_review_count($user->id); ?>)</span>
				</a>
			</li>
		<?php endif; ?>
	</ul>
</div>

<div class="row-custom">
	<!--Include banner-->
	<?php $this->load->view("partials/_ad_spaces_sidebar", ["ad_space" => "profile_sidebar", "class" => "m-t-30"]); ?>
</div>


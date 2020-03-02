<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_model extends CI_Model
{
	//update settings
	public function update_settings()
	{
		$data = array(
			'site_title' => $this->input->post('site_title', true),
			'homepage_title' => $this->input->post('homepage_title', true),
			'site_description' => $this->input->post('site_description', true),
			'keywords' => $this->input->post('keywords', true),
			'facebook_url' => $this->input->post('facebook_url', true),
			'twitter_url' => $this->input->post('twitter_url', true),
			'instagram_url' => $this->input->post('instagram_url', true),
			'pinterest_url' => $this->input->post('pinterest_url', true),
			'linkedin_url' => $this->input->post('linkedin_url', true),
			'vk_url' => $this->input->post('vk_url', true),
			'youtube_url' => $this->input->post('youtube_url', true),
			'about_footer' => $this->input->post('about_footer', true),
			'contact_text' => $this->input->post('contact_text', false),
			'contact_address' => $this->input->post('contact_address', true),
			'contact_email' => $this->input->post('contact_email', true),
			'contact_phone' => $this->input->post('contact_phone', true),
			'copyright' => $this->input->post('copyright', true),
			'cookies_warning' => $this->input->post('cookies_warning', false),
			'cookies_warning_text' => $this->input->post('cookies_warning_text', false)
		);
		$lang_id = $this->input->post('lang_id', true);

		$this->db->where('lang_id', $lang_id);
		return $this->db->update('settings', $data);
	}

	//update general settings
	public function update_general_settings()
	{
		$data = array(
			'application_name' => $this->input->post('application_name', true),
			'head_code' => $this->input->post('head_code', false),
			'facebook_comment_status' => $this->input->post('facebook_comment_status', false),
			'facebook_comment' => $this->input->post('facebook_comment', false)
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update recaptcha settings
	public function update_recaptcha_settings()
	{
		$data = array(
			'recaptcha_site_key' => $this->input->post('recaptcha_site_key', true),
			'recaptcha_secret_key' => $this->input->post('recaptcha_secret_key', true),
			'recaptcha_lang' => $this->input->post('recaptcha_lang', true),
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);

	}

	//update maintenance mode settings
	public function update_maintenance_mode_settings()
	{
		$data = array(
			'maintenance_mode_title' => $this->input->post('maintenance_mode_title', true),
			'maintenance_mode_description' => $this->input->post('maintenance_mode_description', true),
			'maintenance_mode_status' => $this->input->post('maintenance_mode_status', true),
		);

		if (empty($data["maintenance_mode_status"])) {
			$data["maintenance_mode_status"] = 0;
		}

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);

	}

	//update email settings
	public function update_email_settings()
	{
		$data = array(
			'mail_library' => $this->input->post('mail_library', true),
			'mail_protocol' => $this->input->post('mail_protocol', true),
			'mail_title' => $this->input->post('mail_title', true),
			'mail_host' => $this->input->post('mail_host', true),
			'mail_port' => $this->input->post('mail_port', true),
			'mail_username' => $this->input->post('mail_username', true),
			'mail_password' => $this->input->post('mail_password', true),
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update email verification
	public function update_email_verification()
	{
		$data = array(
			'email_verification' => $this->input->post('email_verification', true),
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update email options
	public function update_email_options()
	{
		$data = array(
			'send_email_new_product' => $this->input->post('send_email_new_product', true),
			'send_email_buyer_purchase' => $this->input->post('send_email_buyer_purchase', true),
			'send_email_order_shipped' => $this->input->post('send_email_order_shipped', true),
			'send_email_contact_messages' => $this->input->post('send_email_contact_messages', true),
			'send_email_shop_opening_request' => $this->input->post('send_email_shop_opening_request', true),
			'send_email_bidding_system' => $this->input->post('send_email_bidding_system', true),
			'mail_options_account' => $this->input->post('mail_options_account', true)
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update facebook login
	public function update_facebook_login()
	{
		$data = array(
			'facebook_app_id' => trim($this->input->post('facebook_app_id', true)),
			'facebook_app_secret' => trim($this->input->post('facebook_app_secret', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update google login
	public function update_google_login()
	{
		$data = array(
			'google_client_id' => trim($this->input->post('google_client_id', true)),
			'google_client_secret' => trim($this->input->post('google_client_secret', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update seo tools
	public function update_seo_tools()
	{
		$data_general = array(
			'google_analytics' => $this->input->post('google_analytics', false)
		);
		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data_general);
	}

	//update paypal settings
	public function update_paypal_settings()
	{
		$data = array(
			'paypal_enabled' => $this->input->post('paypal_enabled', true),
			'paypal_mode' => $this->input->post('paypal_mode', true),
			'paypal_client_id' => trim($this->input->post('paypal_client_id', true)),
			'paypal_secret_key' => trim($this->input->post('paypal_secret_key', true))
		);
		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update stripe settings
	public function update_stripe_settings()
	{
		$data = array(
			'stripe_enabled' => $this->input->post('stripe_enabled', true),
			'stripe_publishable_key' => trim($this->input->post('stripe_publishable_key', true)),
			'stripe_secret_key' => trim($this->input->post('stripe_secret_key', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update paystack settings
	public function update_paystack_settings()
	{
		$data = array(
			'paystack_enabled' => $this->input->post('paystack_enabled', true),
			'paystack_secret_key' => trim($this->input->post('paystack_secret_key', true)),
			'paystack_public_key' => trim($this->input->post('paystack_public_key', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update razorpay settings
	public function update_razorpay_settings()
	{
		$data = array(
			'razorpay_enabled' => $this->input->post('razorpay_enabled', true),
			'razorpay_key_id' => trim($this->input->post('razorpay_key_id', true)),
			'razorpay_key_secret' => trim($this->input->post('razorpay_key_secret', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update pagseguro settings
	public function update_pagseguro_settings()
	{
		$data = array(
			'pagseguro_enabled' => $this->input->post('pagseguro_enabled', true),
			'pagseguro_mode' => $this->input->post('pagseguro_mode', true),
			'pagseguro_email' => trim($this->input->post('pagseguro_email', true)),
			'pagseguro_token' => trim($this->input->post('pagseguro_token', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update iyzico settings
	public function update_iyzico_settings()
	{
		$data = array(
			'iyzico_enabled' => $this->input->post('iyzico_enabled', true),
			'iyzico_mode' => $this->input->post('iyzico_mode', true),
			'iyzico_api_key' => trim($this->input->post('iyzico_api_key', true)),
			'iyzico_secret_key' => trim($this->input->post('iyzico_secret_key', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update bank transfer settings
	public function update_bank_transfer_settings()
	{
		$data = array(
			'bank_transfer_enabled' => $this->input->post('bank_transfer_enabled', true),
			'bank_transfer_accounts' => $this->input->post('bank_transfer_accounts', false)
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update cash on delivery settings
	public function update_cash_on_delivery_settings()
	{
		$data = array(
			'cash_on_delivery_enabled' => $this->input->post('cash_on_delivery_enabled', true)
		);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update pricing settings
	public function update_pricing_settings()
	{
		$data = array(
			'price_per_day' => $this->input->post('price_per_day', true),
			'price_per_month' => $this->input->post('price_per_month', true),
			'free_product_promotion' => $this->input->post('free_product_promotion', true)
		);

		$data['price_per_day'] = price_database_format($data["price_per_day"]);
		$data['price_per_month'] = price_database_format($data["price_per_month"]);

		$this->db->where('id', 1);
		return $this->db->update('payment_settings', $data);
	}

	//update preferences
	public function update_preferences($form)
	{
		if ($form == 'homepage') {
			$data = array(
				'index_slider' => $this->input->post('index_slider', true),
				'index_categories' => $this->input->post('index_categories', true),
				'index_promoted_products' => $this->input->post('index_promoted_products', true),
				'index_latest_products' => $this->input->post('index_latest_products', true),
				'index_blog_slider' => $this->input->post('index_blog_slider', true),
				'index_promoted_products_count' => $this->input->post('index_promoted_products_count', true),
				'index_latest_products_count' => $this->input->post('index_latest_products_count', true)
			);
		} elseif ($form == 'general') {
			$data = array(
				'multilingual_system' => $this->input->post('multilingual_system', true),
				'rss_system' => $this->input->post('rss_system', true),
				'vendor_verification_system' => $this->input->post('vendor_verification_system', true),
				'guest_checkout' => $this->input->post('guest_checkout', true)
			);
		} elseif ($form == 'reviews_comments') {
			$data = array(
				'product_reviews' => $this->input->post('product_reviews', true),
				'user_reviews' => $this->input->post('user_reviews', true),
				'product_comments' => $this->input->post('product_comments', true),
				'blog_comments' => $this->input->post('blog_comments', true)
			);
		} elseif ($form == 'products') {
			$data = array(
				'approve_before_publishing' => $this->input->post('approve_before_publishing', true),
				'promoted_products' => $this->input->post('promoted_products', true),
				'product_link_structure' => $this->input->post('product_link_structure', true)
			);
		}

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update visual settings
	public function update_visual_settings()
	{
		$data = array(
			'site_color' => $this->input->post('site_color', true)
		);

		$this->load->model('upload_model');
		$file_path = $this->upload_model->logo_upload('logo');
		if (!empty($file_path)) {
			$data["logo"] = $file_path;
		}

		$file_path = $this->upload_model->logo_upload('logo_email');
		if (!empty($file_path)) {
			$data["logo_email"] = $file_path;
		}

		$file_path = $this->upload_model->favicon_upload('favicon');
		if (!empty($file_path)) {
			$data["favicon"] = $file_path;
		}

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update watermark settings
	public function update_watermark_settings()
	{
		$data = array(
			'watermark_product_images' => $this->input->post('watermark_product_images', true),
			'watermark_blog_images' => $this->input->post('watermark_blog_images', true),
			'watermark_thumbnail_images' => $this->input->post('watermark_thumbnail_images', true),
			'watermark_vrt_alignment' => $this->input->post('watermark_vrt_alignment', true),
			'watermark_hor_alignment' => $this->input->post('watermark_hor_alignment', true)
		);
		//update watermark image
		$this->load->model('upload_model');
		$file_path = $this->upload_model->watermark_upload('watermark_image');
		if (!empty($file_path)) {
			//delete old watermarks
			delete_file_from_server($this->general_settings->watermark_image_large);
			delete_file_from_server($this->general_settings->watermark_image_mid);
			delete_file_from_server($this->general_settings->watermark_image_small);
			//upload new files
			$data['watermark_image_large'] = $file_path;
			$data['watermark_image_mid'] = $this->upload_model->resize_watermark($file_path, 300, 300);
			$data['watermark_image_small'] = $this->upload_model->resize_watermark($file_path, 100, 100);
		}

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update admin panel link
	public function update_admin_panel_link($link)
	{
		$link = str_slug($link);
		if (empty($link)) {
			$link = "admin";
		}
		$start = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL;
		$keys = '$custom_slug_array["admin"] = "' . $link . '";';
		$end = '?>';

		$content = $start . $keys . $end;

		file_put_contents(FCPATH . "application/config/route_slugs.php", $content);
	}

	//update cache system
	public function update_cache_system()
	{
		$data = array(
			'cache_system' => $this->input->post('cache_system', true),
			'refresh_cache_database_changes' => $this->input->post('refresh_cache_database_changes', true),
			'cache_refresh_time' => $this->input->post('cache_refresh_time', true) * 60
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update storage settings
	public function update_storage_settings()
	{
		$data = array(
			'storage' => $this->input->post('storage', true)
		);

		$this->db->where('id', 1);
		return $this->db->update('storage_settings', $data);
	}

	//update system settings
	public function update_system_settings()
	{
		$data = array(
			'physical_products_system' => $this->input->post('physical_products_system', true),
			'digital_products_system' => $this->input->post('digital_products_system', true),
			'marketplace_system' => $this->input->post('marketplace_system', true),
			'classified_ads_system' => $this->input->post('classified_ads_system', true),
			'bidding_system' => $this->input->post('bidding_system', true),
			'multi_vendor_system' => $this->input->post('multi_vendor_system', true),
			'commission_rate' => $this->input->post('commission_rate', true),
			'timezone' => trim($this->input->post('timezone', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//update aws s3
	public function update_aws_s3()
	{
		$data = array(
			'aws_key' => trim($this->input->post('aws_key', true)),
			'aws_secret' => trim($this->input->post('aws_secret', true)),
			'aws_bucket' => trim($this->input->post('aws_bucket', true)),
			'aws_region' => trim($this->input->post('aws_region', true)),
			'aws_base_url' => trim($this->input->post('aws_base_url', true))
		);

		if (substr($data['aws_base_url'], -1) != '/') {
			$data['aws_base_url'] = $data['aws_base_url'] . '/';
		}

		$this->db->where('id', 1);
		return $this->db->update('storage_settings', $data);
	}

	//update navigation
	public function update_navigation()
	{
		$data = array(
			'menu_limit' => $this->input->post('menu_limit', true),
			'selected_navigation' => $this->input->post('navigation', true)
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//get general settings
	public function get_general_settings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('general_settings');
		return $query->row();
	}

	//get system settings
	public function get_system_settings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('general_settings');
		return $query->row();
	}

	//get payment settings
	public function get_payment_settings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('payment_settings');
		return $query->row();
	}

	//get storage settings
	public function get_storage_settings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('storage_settings');
		return $query->row();
	}

	//get settings
	public function get_settings($lang_id)
	{
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('settings');
		return $query->row();
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* FORM SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/

	//update form settings
	public function update_form_settings()
	{
		$data = array(
			'product_conditions' => get_checkbox_value($this->input->post('product_conditions', true)),
			'product_conditions_required' => get_checkbox_value($this->input->post('product_conditions_required', true)),
			'quantity' => get_checkbox_value($this->input->post('quantity', true)),
			'quantity_required' => get_checkbox_value($this->input->post('quantity_required', true)),
			'price' => get_checkbox_value($this->input->post('price', true)),
			'price_required' => get_checkbox_value($this->input->post('price_required', true)),
			'variations' => get_checkbox_value($this->input->post('variations', true)),
			'shipping' => get_checkbox_value($this->input->post('shipping', true)),
			'shipping_required' => get_checkbox_value($this->input->post('shipping_required', true)),
			'product_location' => get_checkbox_value($this->input->post('product_location', true)),
			'product_location_required ' => get_checkbox_value($this->input->post('product_location_required', true)),
			'external_link ' => get_checkbox_value($this->input->post('external_link', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('form_settings', $data);
	}

	//update physical products form
	public function update_physical_products_form()
	{
		$data = array(
			'physical_demo_url' => get_checkbox_value($this->input->post('physical_demo_url', true)),
			'physical_video_preview' => get_checkbox_value($this->input->post('physical_video_preview', true)),
			'physical_audio_preview' => get_checkbox_value($this->input->post('physical_audio_preview', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('form_settings', $data);
	}

	//update digital products form
	public function update_digital_products_form()
	{
		$data = array(
			'digital_demo_url' => get_checkbox_value($this->input->post('digital_demo_url', true)),
			'digital_video_preview' => get_checkbox_value($this->input->post('digital_video_preview', true)),
			'digital_audio_preview ' => get_checkbox_value($this->input->post('digital_audio_preview', true))
		);

		$this->db->where('id', 1);
		return $this->db->update('form_settings', $data);
	}

	//update file upload form
	public function update_file_upload_form()
	{
		$data = array(
			'max_file_size_image' => $this->input->post('max_file_size_image', true) * 1048576,
			'max_file_size_video' => $this->input->post('max_file_size_video', true) * 1048576,
			'max_file_size_audio' => $this->input->post('max_file_size_audio', true) * 1048576,
		);

		$this->db->where('id', 1);
		return $this->db->update('general_settings', $data);
	}

	//get form settings
	public function get_form_settings()
	{
		$this->db->where('id', 1);
		$query = $this->db->get('form_settings');
		return $query->row();
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* SHIPPING OPTIONS
	*-------------------------------------------------------------------------------------------------
	*/

	//add shipping option
	public function add_shipping_option()
	{
		$option_key = $this->input->post('option_label_' . $this->selected_lang->id, true);
		$common_id = generate_unique_id();
		foreach ($this->languages as $language) {
			$data = array(
				'common_id' => $common_id,
				'option_label' => $this->input->post('option_label_' . $language->id, true),
				'option_key' => $option_key,
				'lang_id' => $language->id,
				'option_type' => 'shipping',
				'shipping_cost' => $this->input->post('shipping_cost', true),
				'is_visible' => $this->input->post('is_visible', true)
			);
			if (empty($data['is_visible'])) {
				$data['is_visible'] = 0;
			}
			if (empty($data['shipping_cost'])) {
				$data['shipping_cost'] = 0;
			}
			$this->db->insert('product_options', $data);
		}
	}

	//edit shipping option
	public function edit_shipping_option()
	{
		$common_id = $this->input->post('common_id', true);
		foreach ($this->languages as $language) {
			$option_lang = $this->get_shipping_option_by_lang_not_completed($common_id, $language->id);
			if (!empty($option_lang)) {
				$data = array(
					'option_label' => $this->input->post('option_label_' . $language->id, true),
					'shipping_cost' => $this->input->post('shipping_cost', true),
					'is_visible' => $this->input->post('is_visible', true)
				);
				if (empty($data['is_visible'])) {
					$data['is_visible'] = 0;
				}
				if (empty($data['shipping_cost'])) {
					$data['shipping_cost'] = 0;
				}
				$this->db->where('id', $option_lang->id);
				$this->db->update('product_options', $data);
			} else {
				$main_option = $this->get_shipping_option_by_lang($common_id, $this->selected_lang->id);
				$data = array(
					'common_id' => $main_option->common_id,
					'option_label' => $this->input->post('option_label_' . $language->id, true),
					'option_key' => $main_option->option_key,
					'lang_id' => $language->id,
					'option_type' => 'shipping',
					'shipping_cost' => $this->input->post('shipping_cost', true),
					'is_visible' => $this->input->post('is_visible', true)
				);
				if (empty($data['is_visible'])) {
					$data['is_visible'] = 0;
				}
				if (empty($data['shipping_cost'])) {
					$data['shipping_cost'] = 0;
				}
				$this->db->insert('product_options', $data);
			}
		}
	}

	//get shipping option by common id
	public function get_shipping_option_by_common_id($common_id)
	{
		$this->db->where('common_id', $common_id);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get shipping option by lang
	public function get_shipping_option_by_lang($common_id, $lang_id)
	{
		$this->db->where('common_id', $common_id);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		$row = $query->row();
		if (empty($row)) {
			$this->db->where('common_id', $common_id);
			$query = $this->db->get('product_options');
			$row = $query->row();
		}
		return $row;
	}

	//get shipping option by lang not completed
	public function get_shipping_option_by_lang_not_completed($common_id, $lang_id)
	{
		$this->db->where('common_id', $common_id);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		return $query->row();
	}

	//get shipping option by key
	public function get_shipping_option_by_key($key, $lang_id)
	{
		$this->db->where('option_key', $key);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		return $query->row();
	}

	//is shipping option require cost
	public function is_shipping_option_require_cost($key)
	{
		$this->db->where('option_key', $key);
		$query = $this->db->get('product_options');
		$row = $query->row();
		if (!empty($row)) {
			return $row->shipping_cost;
		}
		return 0;
	}

	//get shipping option
	public function get_shipping_option($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('product_options');
		return $query->row();
	}

	//get shipping options
	public function get_shipping_options($lang_id)
	{
		$this->db->where('option_type', 'shipping');
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get active shipping options
	public function get_active_shipping_options($lang_id)
	{
		$this->db->where('option_type', 'shipping');
		$this->db->where('lang_id', $lang_id);
		$this->db->where('is_visible', 1);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get grouped shipping options
	public function get_grouped_shipping_options()
	{
		$this->db->where('option_type', 'shipping');
		$this->db->select('common_id');
		$this->db->group_by('common_id');
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//delete shipping option
	public function delete_shipping_option($common_id)
	{
		$options = $this->get_shipping_option_by_common_id($common_id);
		if (!empty($options)) {
			foreach ($options as $option) {
				$this->db->where('id', $option->id);
				$this->db->delete('product_options');
			}
			return true;
		}
		return false;
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* PRODUCT CONDITIONS
	*-------------------------------------------------------------------------------------------------
	*/

	//add product condition
	public function add_product_condition()
	{
		$option_key = $this->input->post('option_label_' . $this->selected_lang->id, true);
		$common_id = generate_unique_id();
		foreach ($this->languages as $language) {
			$data = array(
				'common_id' => $common_id,
				'option_label' => $this->input->post('option_label_' . $language->id, true),
				'option_key' => $option_key,
				'lang_id' => $language->id,
				'option_type' => 'product_condition',
				'is_visible' => $this->input->post('is_visible', true)
			);
			if (empty($data['is_visible'])) {
				$data['is_visible'] = 0;
			}
			$data['shipping_cost'] = 0;

			$this->db->insert('product_options', $data);
		}
	}

	//edit product condition
	public function edit_product_condition()
	{
		$common_id = $this->input->post('common_id', true);
		foreach ($this->languages as $language) {
			$option_lang = $this->get_product_condition_by_lang_not_completed($common_id, $language->id);
			if (!empty($option_lang)) {
				$data = array(
					'option_label' => $this->input->post('option_label_' . $language->id, true),
					'is_visible' => $this->input->post('is_visible', true)
				);
				if (empty($data['is_visible'])) {
					$data['is_visible'] = 0;
				}
				$this->db->where('id', $option_lang->id);
				$this->db->update('product_options', $data);
			} else {
				$main_option = $this->get_product_condition_by_lang($common_id, $this->selected_lang->id);
				$data = array(
					'common_id' => $main_option->common_id,
					'option_label' => $this->input->post('option_label_' . $language->id, true),
					'option_key' => $main_option->option_key,
					'lang_id' => $language->id,
					'option_type' => 'product_condition',
					'is_visible' => $this->input->post('is_visible', true)
				);
				if (empty($data['is_visible'])) {
					$data['is_visible'] = 0;
				}
				$data['shipping_cost'] = 0;

				$this->db->insert('product_options', $data);
			}
		}
	}

	//get product condition by common id
	public function get_product_condition_by_common_id($common_id)
	{
		$this->db->where('common_id', $common_id);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get product condition by lang
	public function get_product_condition_by_lang($common_id, $lang_id)
	{
		$this->db->where('common_id', $common_id);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		$row = $query->row();
		if (empty($row)) {
			$this->db->where('common_id', $common_id);
			$query = $this->db->get('product_options');
			$row = $query->row();
		}
		return $row;
	}

	//get product condition by lang not completed
	public function get_product_condition_by_lang_not_completed($common_id, $lang_id)
	{
		$this->db->where('common_id', $common_id);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		return $query->row();
	}

	//get product condition by key
	public function get_product_condition_by_key($key, $lang_id)
	{
		$this->db->where('option_key', $key);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		$row = $query->row();
		if (empty($row)) {
			$this->db->where('option_key', $key);
			$query = $this->db->get('product_options');
			$row = $query->row();
		}
		return $row;
	}

	//get product condition
	public function get_product_condition($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('product_options');
		return $query->row();
	}

	//get product conditions
	public function get_product_conditions($lang_id)
	{
		$this->db->where('option_type', 'product_condition');
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get active product conditions
	public function get_active_product_conditions($lang_id)
	{
		$this->db->where('option_type', 'product_condition');
		$this->db->where('lang_id', $lang_id);
		$this->db->where('is_visible', 1);
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//get grouped product conditions
	public function get_grouped_product_conditions()
	{
		$this->db->where('option_type', 'product_condition');
		$this->db->select('common_id');
		$this->db->group_by('common_id');
		$query = $this->db->get('product_options');
		return $query->result();
	}

	//delete product condition
	public function delete_product_condition($common_id)
	{
		$options = $this->get_product_condition_by_common_id($common_id);
		if (!empty($options)) {
			foreach ($options as $option) {
				$this->db->where('id', $option->id);
				return $this->db->delete('product_options');
			}
		}
	}
}

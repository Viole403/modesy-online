<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_controller extends Admin_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		//check user
		if (!is_admin()) {
			redirect(admin_url() . 'login');
		}
	}

	public function index()
	{
		$data['title'] = trans("admin_panel");

		$data['order_count'] = $this->order_admin_model->get_all_orders_count();
		$data['product_count'] = $this->product_admin_model->get_products_count();
		$data['pending_product_count'] = $this->product_admin_model->get_pending_products_count();
		$data['blog_posts_count'] = $this->blog_model->get_all_posts_count();
		$data['members_count'] = $this->auth_model->get_members_count();

		$data['latest_orders'] = $this->order_admin_model->get_orders_limited(15);

		$data['latest_pending_products'] = $this->product_admin_model->get_latest_pending_products(15);
		$data['latest_products'] = $this->product_admin_model->get_latest_products(15);
		

		$data['latest_reviews'] = $this->review_model->get_latest_reviews(15);
		$data['latest_comments'] = $this->comment_model->get_latest_comments(15);
		$data['latest_members'] = $this->auth_model->get_latest_members(6);

		$data['latest_transactions'] = $this->transaction_model->get_transactions_limited(15);
		$data['latest_promoted_transactions'] = $this->transaction_model->get_promoted_transactions_limited(15);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/index');
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Navigation
	*/
	public function navigation()
	{
		$data['title'] = trans("navigation");

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/navigation', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Navigation Post
	 */
	public function navigation_post()
	{
		if ($this->settings_model->update_navigation()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/*
	* Slider Items
	*/
	public function slider_items()
	{
		$data['title'] = trans("slider_items");
		$data['slider_items'] = $this->slider_model->get_slider_items_all();
		$data['lang_search_column'] = 3;

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/slider/slider_items', $data);
		$this->load->view('admin/includes/_footer');
	}


	/*
	* Add Slider Item
	*/
	public function add_slider_item()
	{
		$data['title'] = trans("add_slider_item");
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/slider/add_item', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add Slider Item Post
	 */
	public function add_slider_item_post()
	{
		if ($this->slider_model->add_item()) {
			$this->session->set_flashdata('success', trans("msg_slider_added"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Update Slider Item
	 */
	public function update_slider_item($id)
	{
		$data['title'] = trans("update_slider_item");

		//get item
		$data['item'] = $this->slider_model->get_slider_item($id);

		if (empty($data['item'])) {
			redirect($this->agent->referrer());
		}
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/slider/update_item', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Update Slider Item Post
	 */
	public function update_slider_item_post()
	{
		//item id
		$id = $this->input->post('id', true);
		if ($this->slider_model->update_item($id)) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect(admin_url() . 'slider-items');
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/**
	 * Delete Slider Item Post
	 */
	public function delete_slider_item_post()
	{
		$id = $this->input->post('id', true);
		if ($this->slider_model->delete_slider_item($id)) {
			$this->session->set_flashdata('success', trans("msg_slider_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* BIDDING SYSTEM
	*-------------------------------------------------------------------------------------------------
	*/

	/**
	 * Quote Requests
	 */
	public function quote_requests()
	{
		$this->load->model('bidding_model');
		$data['title'] = trans("quote_requests");
		$data['form_action'] = admin_url() . "quote-requests";
		
		//get paginated requests
		$pagination = $this->paginate(admin_url() . 'quote-requests', $this->bidding_model->get_admin_quote_requests_count());
		$data['quote_requests'] = $this->bidding_model->get_admin_paginated_quote_requests($pagination['per_page'], $pagination['offset']);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/bidding/quote_requests', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Delete Quote Request
	 */
	public function delete_quote_request_post()
	{
		$this->load->model('bidding_model');
		$id = $this->input->post('id', true);
		if ($this->bidding_model->delete_admin_quote_request($id)) {
			$this->session->set_flashdata('success', trans("msg_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* NEWSLETTER
	*-------------------------------------------------------------------------------------------------
	*/

	/**
	 * Send Email to Subscribers
	 */
	public function send_email_subscribers()
	{
		$data['title'] = trans("send_email_subscribers");

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/newsletter/send_email', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Newsletter Send Email Post
	 */
	public function send_email_subscribers_post()
	{
		$this->load->model("email_model");

		$subject = $this->input->post('subject', true);
		$message = $this->input->post('message', false);

		$data['subscribers'] = $this->newsletter_model->get_subscribers();
		$result = false;
		if (!empty($data['subscribers'])) {
			$result = true;
			foreach ($data['subscribers'] as $item) {
				//send email
				if (!$this->email_model->send_email_newsletter($item, $subject, $message)) {
					$result = false;
				}
			}
		}

		if ($result == true) {
			$this->session->set_flashdata('success', trans("msg_email_sent"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}


	/**
	 * Subscribers
	 */
	public function subscribers()
	{
		$data['title'] = trans("subscribers");
		$data['subscribers'] = $this->newsletter_model->get_subscribers();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/newsletter/subscribers', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Delete Subscriber Post
	 */
	public function delete_subscriber_post()
	{
		$id = $this->input->post('id', true);

		$data['subscriber'] = $this->newsletter_model->get_subscriber_by_id($id);

		if (empty($data['subscriber'])) {
			redirect($this->agent->referrer());
		}

		if ($this->newsletter_model->delete_from_subscribers($id)) {
			$this->session->set_flashdata('success', trans("msg_subscriber_deleted"));
			$this->session->set_flashdata("mes_subscriber_delete", 1);
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_subscriber_delete", 1);
		}
	}

	/**
	 * Email Preview
	 */
	public function email_preview()
	{
		$data["subject"] = $this->input->post('title', true);
		$data["message"] = $this->input->post('content', false);
		$data["type"] = "preview";
		$this->load->view('email/email_newsletter', $data);
	}


	/**
	 * Contact Messages
	 */
	public function contact_messages()
	{
		$data['title'] = trans("contact_messages");

		$data['messages'] = $this->contact_model->get_contact_messages();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/contact_messages', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Delete Contact Message Post
	 */
	public function delete_contact_message_post()
	{
		$id = $this->input->post('id', true);

		if ($this->contact_model->delete_contact_message($id)) {
			$this->session->set_flashdata('success', trans("msg_message_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/**
	 * Ads
	 */
	public function ad_spaces()
	{
		$data['title'] = trans("ad_spaces");

		$data['ad_space'] = $this->input->get('ad_space', true);

		if (empty($data['ad_space'])) {
			redirect(admin_url() . "ad-spaces?ad_space=index_1");
		}

		$data['ad_codes'] = $this->ad_model->get_ad_codes($data['ad_space']);

		if (empty($data['ad_codes'])) {
			redirect(admin_url() . "ad-spaces");
		}

		$data["array_ad_spaces"] = array(
			"index_1" => trans("index_ad_space_1"),
			"index_2" => trans("index_ad_space_2"),
			"products" => trans("products_ad_space"),
			"products_sidebar" => trans("products_sidebar_ad_space"),
			"product" => trans("product_ad_space"),
			"product_sidebar" => trans("product_sidebar_ad_space"),
			"blog_1" => trans("blog_ad_space_1"),
			"blog_2" => trans("blog_ad_space_2"),
			"blog_post_details" => trans("blog_post_details_ad_space"),
			"blog_post_details_sidebar" => trans("blog_post_details_sidebar_ad_space"),
			"profile" => trans("profile_ad_space"),
			"profile_sidebar" => trans("profile_sidebar_ad_space"),
		);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/ad_spaces', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Ads Post
	 */
	public function ad_spaces_post()
	{
		$ad_space = $this->input->post('ad_space', true);

		if ($this->ad_model->update_ad_spaces($ad_space)) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Google Adsense Code Post
	 */
	public function google_adsense_code_post()
	{
		if ($this->ad_model->update_google_adsense_code()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata('mes_adsense', 1);
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata('mes_adsense', 1);
		}
		redirect($this->agent->referrer());
	}

	/*
	* Seo Tools
	*/
	public function seo_tools()
	{
		$data['title'] = trans("seo_tools");
		
		$data["current_lang_id"] = $this->input->get("lang", true);

		if (empty($data["current_lang_id"])) {
			$data["current_lang_id"] = $this->general_settings->site_lang;
			redirect(admin_url() . "seo-tools?lang=" . $data["current_lang_id"]);
		}

		$data['settings'] = $this->settings_model->get_settings($data["current_lang_id"]);
		$data['languages'] = $this->language_model->get_languages();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/seo_tools', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Seo Tools Post
	 */
	public function seo_tools_post()
	{
		if ($this->settings_model->update_seo_tools()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* CURRENCY SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/


	/*
	* Currency Settings
	*/
	public function currency_settings()
	{
		$data['title'] = trans("currency_settings");
		$data['currencies'] = $this->currency_model->get_currencies();
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/currency/currency_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Currency Settings Post
	*/
	public function currency_settings_post()
	{
		if ($this->currency_model->update_currency_settings()) {
			$this->session->set_flashdata('msg_settings', 1);
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('msg_settings', 1);
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/*
	* Add Currency Post
	*/
	public function add_currency_post()
	{
		if ($this->currency_model->add_currency()) {
			$this->session->set_flashdata('msg_add', 1);
			$this->session->set_flashdata('success', trans("msg_added"));
		} else {
			$this->session->set_flashdata('msg_add', 1);
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}


	/**
	 * Update Currency
	 */
	public function update_currency($id)
	{
		$data['title'] = trans("update_currency");

		$data['currency'] = $this->currency_model->get_currency($id);

		//page not found
		if (empty($data['currency'])) {
			redirect($this->agent->referrer());
		}

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/currency/update_currency', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Update Currency Post
	 */
	public function update_currency_post()
	{
		$id = $this->input->post('id', true);

		if ($this->currency_model->update_currency($id)) {
			$this->session->set_flashdata('msg_table', 1);
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect(admin_url() . "currency-settings");
		} else {
			$this->session->set_flashdata('msg_table', 1);
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/*
	* Delete Currency Post
	*/
	public function delete_currency_post()
	{
		$id = $this->input->post('id', true);
		if ($this->currency_model->delete_currency($id)) {
			$this->session->set_flashdata('msg_table', 1);
			$this->session->set_flashdata('success', trans("msg_deleted"));
		} else {
			$this->session->set_flashdata('msg_table', 1);
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* EMAIL SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/

	/*
	* Email Settings
	*/
	public function email_settings()
	{
		$data['title'] = trans("email_settings");

		$data['general_settings'] = $this->settings_model->get_general_settings();
		
		$data["library"] = $this->input->get('library');
		if (empty($data["library"])) {
			$data["library"] = "swift";
			if (!empty($this->general_settings->mail_library)) {
				$data["library"] = $this->general_settings->mail_library;
			}
			redirect(admin_url() . "email-settings?library=" . $data["library"]);
		}


		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/email_settings', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Email Settings Post
	 */
	public function email_settings_post()
	{
		if ($this->settings_model->update_email_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata('submit', $this->input->post('submit', true));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata('submit', $this->input->post('submit', true));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Email Verification Post
	 */
	public function email_verification_post()
	{
		if ($this->settings_model->update_email_verification()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata('submit', "verification");
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata('submit', "verification");
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Email Options Post
	 */
	public function email_options_post()
	{
		if ($this->settings_model->update_email_options()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata('submit', "options");
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata('submit', "options");
			redirect($this->agent->referrer());
		}
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* FORM SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/

	/*
	* Visual Settings
	*/
	public function visual_settings()
	{
		$data['title'] = trans("visual_settings");

		$data['visual_settings'] = $this->settings_model->get_general_settings();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/visual_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Visual Settings Post
	 */
	public function visual_settings_post()
	{
		if ($this->settings_model->update_visual_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Update Watermak Category
	 */
	public function update_watermark_settings_post()
	{
		$this->settings_model->update_watermark_settings();
		redirect($this->agent->referrer());
	}

	/**
	 * Delete Category Watermak
	 */
	public function delete_category_watermark_post()
	{
		$this->settings_model->delete_category_watermark();
		redirect($this->agent->referrer());
	}


	/*
	* System Settings
	*/
	public function system_settings()
	{
		$data['title'] = trans("system_settings");
		
		$data['system_settings'] = $this->settings_model->get_system_settings();
		$data['currencies'] = $this->currency_model->get_currencies();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/system_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * System Settings Post
	 */
	public function system_settings_post()
	{
		//check product type
		$physical_products_system = $this->input->post('physical_products_system', true);
		$digital_products_system = $this->input->post('digital_products_system', true);
		if ($physical_products_system == 0 && $digital_products_system == 0) {
			$this->session->set_flashdata('error', trans("msg_error_product_type"));
			redirect($this->agent->referrer());
			exit();
		}

		$marketplace_system = $this->input->post('marketplace_system', true);
		$classified_ads_system = $this->input->post('classified_ads_system', true);
		$bidding_system = $this->input->post('bidding_system', true);
		if ($marketplace_system == 0 && $classified_ads_system == 0 && $bidding_system == 0) {
			$this->session->set_flashdata('error', trans("msg_error_selected_system"));
			redirect($this->agent->referrer());
			exit();
		}

		if ($this->settings_model->update_system_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/*
	* Social Login Settings
	*/
	public function social_login_settings()
	{
		$data['title'] = trans("social_login");

		$data['general_settings'] = $this->settings_model->get_general_settings();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/social_login', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Facebook Login Post
	 */
	public function facebook_login_post()
	{
		if ($this->settings_model->update_facebook_login()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_social_facebook", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_social_facebook", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Google Login Post
	 */
	public function google_login_post()
	{
		if ($this->settings_model->update_google_login()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_social_google", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_social_google", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Members
	 */
	public function members()
	{
		$data['title'] = trans("members");

		$data['users'] = $this->auth_model->get_members();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/members');
		$this->load->view('admin/includes/_footer');

	}

	/**
	 * Vendors
	 */
	public function vendors()
	{
		$data['title'] = trans("vendors");

		$data['users'] = $this->auth_model->get_vendors();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/vendors');
		$this->load->view('admin/includes/_footer');

	}

	/**
	 * Administrators
	 */
	public function administrators()
	{
		$data['title'] = trans("administrators");

		$data['users'] = $this->auth_model->get_administrators();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/administrators');
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Add Administrator
	 */
	public function add_administrator()
	{
		$data['title'] = trans("add_administrator");

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/add_administrator');
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Add Administrator
	 */
	public function add_administrator_post()
	{
		//validate inputs
		$this->form_validation->set_rules('username', trans("username"), 'required|xss_clean|min_length[4]|max_length[100]');
		$this->form_validation->set_rules('email', trans("email_address"), 'required|xss_clean|max_length[200]');
		$this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|min_length[4]|max_length[50]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->auth_model->input_values());
			redirect($this->agent->referrer());
		} else {
			$email = $this->input->post('email', true);
			$username = $this->input->post('username', true);
			//is username unique
			if (!$this->auth_model->is_unique_username($username)) {
				$this->session->set_flashdata('form_data', $this->auth_model->input_values());
				$this->session->set_flashdata('error', trans("msg_username_unique_error"));
				redirect($this->agent->referrer());
			}
			//is email unique
			if (!$this->auth_model->is_unique_email($email)) {
				$this->session->set_flashdata('form_data', $this->auth_model->input_values());
				$this->session->set_flashdata('error', trans("msg_email_unique_error"));
				redirect($this->agent->referrer());
			}

			//add user
			if ($this->auth_model->add_administrator()) {
				$this->session->set_flashdata('success', trans("msg_administrator_added"));
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
			}

			redirect($this->agent->referrer());
		}
	}

	/**
	 * Shop Opening Requests
	 */
	public function shop_opening_requests()
	{
		$data['title'] = trans("shop_opening_requests");

		$data['requests'] = $this->auth_model->get_shop_opening_requests();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/users/shop_opening_requests');
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Approve Shop Opening Request
	 */
	public function approve_shop_opening_request()
	{
		$user_id = $this->input->post('id', true);
		if ($this->auth_model->approve_shop_opening_request($user_id)) {
			$this->session->set_flashdata('success', trans("msg_updated"));

			$submit = $this->input->post('submit', true);
			$email_content = trans("your_shop_opening_request_approved");
			$email_button_text = trans("start_selling");
			if ($submit == 0) {
				$email_content = trans("msg_shop_request_declined");
				$email_button_text = trans("view_site");
			}

			//send email
			$user = get_user($user_id);
			if (!empty($user) && $this->general_settings->send_email_shop_opening_request == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $user->email,
					'subject' => trans("shop_opening_request"),
					'email_content' => $email_content,
					'email_link' => base_url(),
					'email_button_text' => $email_button_text
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Confirm User Email
	 */
	public function confirm_user_email()
	{
		$id = $this->input->post('id', true);
		$user = $this->auth_model->get_user($id);
		if ($this->auth_model->verify_email($user)) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}


	/**
	 * Ban or Remove User Ban
	 */
	public function ban_remove_ban_user()
	{
		$id = $this->input->post('id', true);
		if ($this->auth_model->ban_remove_ban_user($id)) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/**
	 * Open or Close User Shop
	 */
	public function open_close_user_shop()
	{
		$id = $this->input->post('id', true);
		if ($this->auth_model->open_close_user_shop($id)) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/**
	 * Delete User
	 */
	public function delete_user_post()
	{
		$id = $this->input->post('id', true);
		if ($this->auth_model->delete_user($id)) {
			$this->session->set_flashdata('success', trans("msg_user_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/**
	 * Storage
	 */
	public function storage()
	{
		
		$data['title'] = trans("storage");
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/storage', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Storage Post
	 */
	public function storage_post()
	{
		if ($this->settings_model->update_storage_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * AWS S3 Post
	 */
	public function aws_s3_post()
	{
		if ($this->settings_model->update_aws_s3()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata('mes_s3', 1);
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Cache System
	 */
	public function cache_system()
	{
		$data['title'] = trans("cache_system");
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/cache_system', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Cache System Post
	 */
	public function cache_system_post()
	{
		if ($this->input->post('action', true) == "reset") {
			reset_cache_data();
			$this->session->set_flashdata('success', trans("msg_reset_cache"));
		} else {
			if ($this->settings_model->update_cache_system()) {
				$this->session->set_flashdata('success', trans("msg_updated"));
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
			}
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Preferences
	 */
	public function preferences()
	{
		$data['title'] = trans("preferences");
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/preferences', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Preferences Post
	 */
	public function preferences_post()
	{
		$form = $this->input->post('submit', true);
		$this->session->set_flashdata('mes_' . $form, 1);
		if ($this->settings_model->update_preferences($form)) {

			if ($form == 'general') {
				$admin_panel_link = $this->input->post('admin_panel_link', true);
				$this->settings_model->update_admin_panel_link($admin_panel_link);
				sleep(1);
			}

			$this->session->set_flashdata('success', trans("msg_updated"));
			//reset cache
			redirect(admin_url() . "preferences");
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/*
	 * Settings
	 */
	public function settings()
	{
		$data['title'] = trans("settings");

		$data["settings_lang"] = $this->input->get("lang", true);

		if (empty($data["settings_lang"])) {
			$data["settings_lang"] = $this->selected_lang->id;
			redirect(admin_url() . "settings?lang=" . $data["settings_lang"]);
		}

		$data['settings'] = $this->settings_model->get_settings($data["settings_lang"]);
		$data['general_settings'] = $this->settings_model->get_general_settings();
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/settings', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Settings Post
	 */
	public function settings_post()
	{
		if ($this->settings_model->update_settings()) {
			$this->settings_model->update_general_settings();
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_settings", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_settings", 1);
			redirect($this->agent->referrer());
		}
	}


	/**
	 * Recaptcha Settings Post
	 */
	public function recaptcha_settings_post()
	{
		if ($this->settings_model->update_recaptcha_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_recaptcha", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_recaptcha", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Maintenance Mode Post
	 */
	public function maintenance_mode_post()
	{
		if ($this->settings_model->update_maintenance_mode_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_maintenance", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_maintenance", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * User Reviews
	 */
	public function user_reviews()
	{
		$data['title'] = trans("user_reviews");
		$data['reviews'] = $this->user_review_model->get_all_reviews();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/review/user_reviews', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Delete User Review
	 */
	public function delete_user_review()
	{
		$id = $this->input->post('id', true);
		if ($this->user_review_model->delete_review($id)) {
			$this->session->set_flashdata('success', trans("msg_review_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}


	/**
	 * Delete Selected User Reviews
	 */
	public function delete_selected_user_reviews()
	{
		$review_ids = $this->input->post('review_ids', true);
		$this->user_review_model->delete_multi_reviews($review_ids);
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* LOCATION
	*-------------------------------------------------------------------------------------------------
	*/


	/**
	 * Location Settings
	 */
	public function location_settings()
	{
		$data['title'] = trans("location_settings");
		$data['countries'] = $this->location_model->get_countries();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/location_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Location Settings Post
	 */
	public function location_settings_post()
	{
		if ($this->location_model->set_location_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/**
	 * Countries
	 */
	public function countries()
	{
		$data['title'] = trans("countries");
		
		//get paginated products
		$pagination = $this->paginate(admin_url() . 'countries', $this->location_model->get_paginated_countries_count());
		$data['countries'] = $this->location_model->get_paginated_countries($pagination['per_page'], $pagination['offset']);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/countries', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add Country
	 */
	public function add_country()
	{
		$data['title'] = trans("add_country");

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/add_country', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add Country Post
	 */
	public function add_country_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			if ($this->location_model->add_country()) {
				$this->session->set_flashdata('success', trans("msg_added"));
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}


	/**
	 * Update Country
	 */
	public function update_country($id)
	{
		$data['title'] = trans("update_country");

		//get country
		$data['country'] = $this->location_model->get_country($id);
		if (empty($data['country'])) {
			redirect($this->agent->referrer());
		}

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/update_country', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Update Country Post
	 */
	public function update_country_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			//country id
			$id = $this->input->post('id', true);
			if ($this->location_model->update_country($id)) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				redirect(admin_url() . 'countries');
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Delete Country Post
	 */
	public function delete_country_post()
	{
		$id = $this->input->post('id', true);
		if ($this->location_model->delete_country($id)) {
			$this->session->set_flashdata('success', trans("msg_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}


	/**
	 * States
	 */
	public function states()
	{
		$data['title'] = trans("states");
		$data['countries'] = $this->location_model->get_countries();
		
		//get paginated states
		$pagination = $this->paginate(admin_url() . 'states', $this->location_model->get_paginated_states_count());
		$data['states'] = $this->location_model->get_paginated_states($pagination['per_page'], $pagination['offset']);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/states', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add State
	 */
	public function add_state()
	{
		$data['title'] = trans("add_state");
		$data['countries'] = $this->location_model->get_countries();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/add_state', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add State Post
	 */
	public function add_state_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			if ($this->location_model->add_state()) {
				$this->session->set_flashdata('success', trans("msg_added"));
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}


	/**
	 * Update State
	 */
	public function update_state($id)
	{
		$data['title'] = trans("update_state");

		//get state
		$data['state'] = $this->location_model->get_state($id);
		if (empty($data['state'])) {
			redirect($this->agent->referrer());
		}
		$data['countries'] = $this->location_model->get_countries();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/update_state', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Update State Post
	 */
	public function update_state_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			//country id
			$id = $this->input->post('id', true);
			if ($this->location_model->update_state($id)) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				$redirect_url = $this->input->post('redirect_url', true);
				if (!empty($redirect_url)) {
					redirect($redirect_url);
				} else {
					redirect(admin_url() . 'states');
				}
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}


	/**
	 * Delete State Post
	 */
	public function delete_state_post()
	{
		$id = $this->input->post('id', true);
		if ($this->location_model->delete_state($id)) {
			$this->session->set_flashdata('success', trans("msg_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	/**
	 * Cities
	 */
	public function cities()
	{
		$data['title'] = trans("cities");
		$data['countries'] = $this->location_model->get_countries();
		$data['states'] = $this->location_model->get_states();
		
		//get paginated cities
		$pagination = $this->paginate(admin_url() . 'cities', $this->location_model->get_paginated_cities_count());
		$data['cities'] = $this->location_model->get_paginated_cities($pagination['per_page'], $pagination['offset']);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/cities', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add Cities
	 */
	public function add_city()
	{
		$data['title'] = trans("add_city");
		$data['countries'] = $this->location_model->get_countries();

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/add_city', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Add City Post
	 */
	public function add_city_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			if ($this->location_model->add_city()) {
				$this->session->set_flashdata('success', trans("msg_added"));
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}


	/**
	 * Update City
	 */
	public function update_city($id)
	{
		$data['title'] = trans("update_city");

		//get city
		$data['city'] = $this->location_model->get_city($id);
		if (empty($data['city'])) {
			redirect($this->agent->referrer());
		}
		$data['countries'] = $this->location_model->get_countries();
		$data['states'] = $this->location_model->get_states_by_country($data['city']->country_id);

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/location/update_city', $data);
		$this->load->view('admin/includes/_footer');
	}


	/**
	 * Update City Post
	 */
	public function update_city_post()
	{
		//validate inputs
		$this->form_validation->set_rules('name', trans("name"), 'required|xss_clean|max_length[200]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			//country id
			$id = $this->input->post('id', true);
			if ($this->location_model->update_city($id)) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				$redirect_url = $this->input->post('redirect_url', true);
				if (!empty($redirect_url)) {
					redirect($redirect_url);
				} else {
					redirect(admin_url() . 'cities');
				}
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}


	/**
	 * Delete City Post
	 */
	public function delete_city_post()
	{
		$id = $this->input->post('id', true);
		if ($this->location_model->delete_city($id)) {
			$this->session->set_flashdata('success', trans("msg_deleted"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
	}

	//get states by country
	public function get_states_by_country()
	{
		$country_id = $this->input->post('country_id', true);
		$states = $this->location_model->get_states_by_country($country_id);

		if (!empty($states)) {
			echo "<option value=''>" . trans('all') . "</option>";
			foreach ($states as $state) {
				echo "<option value='" . $state->id . "'>" . html_escape($state->name) . "</option>";
			}
		}
	}
}

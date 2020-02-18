<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->review_limit = 5;
		$this->comment_limit = 5;
		$this->product_per_page = 18;
	}

	/**
	 * Start Selling
	 */
	public function start_selling()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(lang_base_url() . "settings/update-profile");
		}

		$data['title'] = trans("start_selling");
		$data['description'] = trans("start_selling") . " - " . $this->app_name;
		$data['keywords'] = trans("start_selling") . "," . $this->app_name;

		$this->load->view('partials/_header', $data);
		$this->load->view('product/start_selling', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Start Selling Post
	 */
	public function start_selling_post()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		$user_id = $this->input->post('id', true);
		$data = array(
			'is_active_shop_request' => 1,
			'shop_name' => remove_special_characters($this->input->post('shop_name', true)),
			'country_id' => $this->input->post('country_id', true),
			'state_id' => $this->input->post('state_id', true),
			'phone_number' => $this->input->post('phone_number', true),
			'about_me' => $this->input->post('about_me', true)
		);

		//is shop name unique
		if (!$this->auth_model->is_unique_shop_name($data['shop_name'], $user_id)) {
			$this->session->set_flashdata('form_data', $data);
			$this->session->set_flashdata('error', trans("msg_shop_name_unique_error"));
			redirect($this->agent->referrer());
		}

		if ($this->auth_model->add_shop_opening_requests($data)) {
			//send email
			$user = get_user($user_id);
			if (!empty($user) && $this->general_settings->send_email_shop_opening_request == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $this->general_settings->mail_options_account,
					'subject' => trans("shop_opening_request"),
					'email_content' => trans("there_is_shop_opening_request") . "<br>" . trans("user") . ": " . "<strong>" . $user->username . "</strong>",
					'email_link' => admin_url() . "shop-opening-requests",
					'email_button_text' => trans("view_details")
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}

			$this->session->set_flashdata('success', trans("msg_start_selling"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Add Product
	 */
	public function add_product()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url() . 'start-selling');
		}
		if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(lang_base_url() . "settings/update-profile");
		}

		$data['title'] = trans("sell_now");
		$data['description'] = trans("sell_now") . " - " . $this->app_name;
		$data['keywords'] = trans("sell_now") . "," . $this->app_name;
		$data['modesy_images'] = $this->file_model->get_sess_product_images_array();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();

		$this->load->view('partials/_header', $data);
		$this->load->view('product/add_product', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Add Product Post
	 */
	public function add_product_post()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(lang_base_url() . "settings/update-profile");
		}
		//add product
		if ($this->product_model->add_product()) {
			//last id
			$last_id = $this->db->insert_id();
			//update slug
			$this->product_model->update_slug($last_id);
			//add product images
			$this->file_model->add_product_images($last_id);

			redirect(lang_base_url() . 'sell-now/product-details/' . $last_id);
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Edit Draft
	 */
	public function edit_draft($id)
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$data["product"] = $this->product_admin_model->get_product($id);
		if (empty($data["product"])) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->is_draft != 1) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->user_id != user()->id && user()->role != "admin") {
			redirect($this->agent->referrer());
		}

		$data['title'] = trans("sell_now");
		$data['description'] = trans("sell_now") . " - " . $this->app_name;
		$data['keywords'] = trans("sell_now") . "," . $this->app_name;

		$data['category'] = get_category($data["product"]->category_id);
		$data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
		$data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
		$data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();

		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product
	 */
	public function edit_product($id)
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$data["product"] = $this->product_admin_model->get_product($id);
		if (empty($data["product"])) {
			redirect($this->agent->referrer());
		}
		if ($data["product"]->is_deleted == 1) {
			if (user()->role != "admin") {
				redirect($this->agent->referrer());
			}
		}
		if ($data["product"]->user_id != user()->id && user()->role != "admin") {
			redirect($this->agent->referrer());
		}

		$data['title'] = trans("edit_product");
		$data['description'] = trans("edit_product") . " - " . $this->app_name;
		$data['keywords'] = trans("edit_product") . "," . $this->app_name;

		$data['category'] = get_category($data["product"]->category_id);
		$data['parent_categories_array'] = $this->category_model->get_parent_categories_array_by_category_id($data["product"]->category_id);
		$data['modesy_images'] = $this->file_model->get_product_images_uncached($data["product"]->id);
		$data['all_categories'] = $this->category_model->get_categories_ordered_by_name();
		$data["file_manager_images"] = $this->file_model->get_user_file_manager_images();
		$data["active_product_system_array"] = $this->get_activated_product_system();

		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product Post
	 */
	public function edit_product_post()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		//validate inputs
		$this->form_validation->set_rules('title', trans("title"), 'required|xss_clean|max_length[500]');

		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {
			//product id
			$product_id = $this->input->post('id', true);
			//user id
			$user_id = 0;
			$product = $this->product_admin_model->get_product($product_id);
			if (!empty($product)) {
				$user_id = $product->user_id;
			}
			if ($product->user_id != user()->id && user()->role != "admin") {
				redirect($this->agent->referrer());
			}

			if ($this->product_model->edit_product($product)) {
				//edit slug
				$this->product_model->update_slug($product_id);

				if ($product->is_draft == 1) {
					redirect(lang_base_url() . 'sell-now/product-details/' . $product_id);
				} else {
					//reset cache
					reset_cache_data_on_change();
					reset_user_cache_data($user_id);
					reset_product_img_cache_data($product_id);

					$this->session->set_flashdata('success', trans("msg_updated"));
					redirect($this->agent->referrer());
				}
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Edit Product Details
	 */
	public function edit_product_details($id)
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->email_verification == 1 && user()->email_status != 1) {
			$this->session->set_flashdata('error', trans("msg_confirmed_required"));
			redirect(lang_base_url() . "settings/update-profile");
		}

		$data['product'] = $this->product_admin_model->get_product($id);
		if (empty($data['product'])) {
			redirect($this->agent->referrer());
		}
		if (user()->role != 'admin' && user()->id != $data['product']->user_id) {
			redirect($this->agent->referrer());
			exit();
		}

		if ($data['product']->is_draft == 1) {
			$data['title'] = trans("sell_now");
			$data['description'] = trans("sell_now") . " - " . $this->app_name;
			$data['keywords'] = trans("sell_now") . "," . $this->app_name;
		} else {
			$data['title'] = trans("edit_product");
			$data['description'] = trans("edit_product") . " - " . $this->app_name;
			$data['keywords'] = trans("edit_product") . "," . $this->app_name;
		}


		if ($this->general_settings->default_product_location == 0) {
			if ($data["product"]->country_id == 0) {
				$data["states"] = $this->location_model->get_states_by_country($this->auth_user->country_id);
			} else {
				$data["states"] = $this->location_model->get_states_by_country($data["product"]->country_id);
			}
		} else {
			$data["states"] = $this->location_model->get_states_by_country($this->general_settings->default_product_location);
		}
		if ($data["product"]->country_id == 0) {
			$data["cities"] = $this->location_model->get_cities_by_state($this->auth_user->state_id);
		} else {
			$data["cities"] = $this->location_model->get_cities_by_state($data["product"]->state_id);
		}

		$data["custom_field_array"] = $this->field_model->generate_custom_fields_array($data["product"]->category_id, $data["product"]->id);
		$data["product_variations"] = $this->variation_model->get_product_variations($data["product"]->id);
		$data["user_variations"] = $this->variation_model->get_variation_by_user_id($data["product"]->user_id);
		$data['form_settings'] = $this->settings_model->get_form_settings();
		$data['license_keys'] = $this->product_model->get_license_keys($data["product"]->id);

		$this->load->view('partials/_header', $data);
		$this->load->view('product/edit_product_details');
		$this->load->view('partials/_footer');
	}

	/**
	 * Edit Product Details Post
	 */
	public function edit_product_details_post()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$product_id = $this->input->post('id', true);
		$product = $this->product_admin_model->get_product($product_id);
		if (empty($product)) {
			redirect($this->agent->referrer());
			exit();
		}
		if (user()->role != 'admin' && user()->id != $product->user_id) {
			redirect($this->agent->referrer());
			exit();
		}

		if ($this->product_model->edit_product_details($product_id)) {
			//edit custom fields
			$this->product_model->update_product_custom_fields($product_id);

			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data(user()->id);

			if ($product->is_draft != 1) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				redirect($this->agent->referrer());
			} else {
				//send email
				if ($this->general_settings->send_email_new_product == 1) {
					$email_data = array(
						'email_type' => 'new_product',
						'product_id' => $product->id
					);
					$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
				}

				//if draft
				if ($this->input->post('submit', true) == 'save_as_draft') {
					redirect(lang_base_url() . 'drafts');
					exit();
				}
				if ($this->promoted_products_enabled == 1) {
					redirect(lang_base_url() . "promote-product/pricing/" . $product_id . "?type=new");
				} else {
					redirect(lang_base_url() . $product->slug);
				}
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Delete Product
	 */
	public function delete_product()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$id = $this->input->post('id', true);

		//user id
		$user_id = 0;
		$product = $this->product_admin_model->get_product($id);
		if (!empty($product)) {
			$user_id = $product->user_id;
		}

		if (user()->role == "admin" || user()->id == $user_id) {
			if ($this->product_model->delete_product($id)) {
				$this->session->set_flashdata('success', trans("msg_product_deleted"));
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
			}

			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data($user_id);
		}
	}

	/**
	 * Delete Draft
	 */
	public function delete_draft()
	{
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$id = $this->input->post('id', true);

		//user id
		$user_id = 0;
		$product = $this->product_admin_model->get_product($id);
		if (!empty($product)) {
			$user_id = $product->user_id;
		}

		if (user()->role == "admin" || user()->id == $user_id) {
			$this->product_admin_model->delete_product_permanently($id);
			//reset cache
			reset_cache_data_on_change();
			reset_user_cache_data($user_id);
		}
	}

	/*
	*------------------------------------------------------------------------------------------
	* PRODUCT VARIATIONS
	*------------------------------------------------------------------------------------------
	*/

	//add product variation
	public function add_product_variation()
	{
		if ($this->auth_check) {
			$product_id = $this->input->post('product_id', true);
			$this->variation_model->add_variation();
			$data["product_variations"] = $this->variation_model->get_product_variations($product_id);
			$this->load->view('product/variation/_response_variations', $data);
		}
	}

	//edit product variation
	public function edit_product_variation()
	{
		if ($this->auth_check) {
			$common_id = $this->input->post('common_id', true);
			$product_id = $this->input->post('product_id', true);
			$lang_id = $this->input->post('lang_id', true);

			$data["product_id"] = $product_id;
			$data["variation"] = $this->variation_model->get_variation_by_common_id($common_id);
			$data["main_variation"] = $this->variation_model->get_variation($common_id, $lang_id);
			$this->load->view('product/variation/_response_variation_edit', $data);
		}
	}

	//edit product variation
	public function edit_product_variation_post()
	{
		if ($this->auth_check) {
			$common_id = $this->input->post('common_id', true);
			$product_id = $this->input->post('product_id', true);
			$this->variation_model->edit_variation($common_id);
			$data["product_variations"] = $this->variation_model->get_product_variations($product_id);
			$this->load->view('product/variation/_response_variations', $data);
		}
	}

	//delete product variation
	public function delete_product_variation()
	{
		if ($this->auth_check) {
			$common_id = $this->input->post('common_id', true);
			$product_id = $this->input->post('product_id', true);
			$this->variation_model->delete_variation($common_id);
			$data["product_variations"] = $this->variation_model->get_product_variations($product_id);
			$this->load->view('product/variation/_response_variations', $data);
		}
	}

	//add product variation option
	public function add_product_variation_option()
	{
		if ($this->auth_check) {
			$variation_common_id = $this->input->post('variation_common_id', true);
			$lang_id = $this->input->post('lang_id', true);
			$this->variation_model->add_variation_option($variation_common_id, $lang_id);
			$data["main_variation"] = $this->variation_model->get_variation($variation_common_id, $lang_id);
			$this->load->view('product/variation/_response_variation_options_edit', $data);
		}
	}

	//edit product variation options
	public function edit_product_variation_options()
	{
		if ($this->auth_check) {
			$common_id = $this->input->post('common_id', true);
			$product_id = $this->input->post('product_id', true);
			$lang_id = $this->input->post('lang_id', true);
			$data["product_id"] = $product_id;
			$data["main_variation"] = $this->variation_model->get_variation($common_id, $lang_id);
			$this->load->view('product/variation/_response_variation_options_edit', $data);
		}
	}

	//edit product variation options post
	public function edit_product_variation_options_post()
	{
		if ($this->auth_check) {
			$variation_common_id = $this->input->post('variation_common_id', true);
			$lang_id = $this->input->post('lang_id', true);
			$this->variation_model->edit_variation_options($variation_common_id);
			$data["main_variation"] = $this->variation_model->get_variation($variation_common_id, $lang_id);
			$this->load->view('product/variation/_response_variation_options_edit', $data);
		}
	}

	//delete product variation option
	public function delete_product_variation_option()
	{
		if ($this->auth_check) {
			$option_common_id = $this->input->post('option_common_id', true);
			$variation_common_id = $this->input->post('variation_common_id', true);
			$lang_id = $this->input->post('lang_id', true);
			$this->variation_model->delete_variation_option($option_common_id);
			$data["main_variation"] = $this->variation_model->get_variation($variation_common_id, $lang_id);
			$this->load->view('product/variation/_response_variation_options_edit', $data);
		}
	}

	//select product variation
	public function select_product_variation()
	{
		if ($this->auth_check) {
			$common_id = $this->input->post('common_id', true);
			$product_id = $this->input->post('product_id', true);
			$this->variation_model->select_variation($common_id, $product_id);
			$data["product_variations"] = $this->variation_model->get_product_variations($product_id);
			$this->load->view('product/variation/_response_variations', $data);
		}
	}

	//make review
	public function make_review()
	{
		if (!$this->auth_check) {
			exit();
		}
		if ($this->general_settings->product_reviews != 1) {
			exit();
		}
		$limit = $this->input->post('limit', true);
		$product_id = $this->input->post('product_id', true);
		$review = $this->review_model->get_review($product_id, user()->id);
		$data["product"] = $this->product_model->get_product_by_id($product_id);

		if (!empty($review)) {
			echo "voted_error";
		} elseif ($data["product"]->user_id == user()->id) {
			echo "error_own_product";
		} else {
			$this->review_model->add_review();
			$data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
			$data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
			$data['review_limit'] = $limit;
			$data["product"] = $this->product_model->get_product_by_id($product_id);
			$this->load->view('product/details/_make_review', $data);
		}
	}

	//load more review
	public function load_more_review()
	{
		$product_id = $this->input->post('product_id', true);
		$limit = $this->input->post('limit', true);
		$new_limit = $limit + $this->review_limit;
		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["reviews"] = $this->review_model->get_limited_reviews($product_id, $new_limit);
		$data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
		$data['review_limit'] = $new_limit;

		$this->load->view('product/details/_make_review', $data);
	}

	//delete review
	public function delete_review()
	{
		$id = $this->input->post('id', true);
		$product_id = $this->input->post('product_id', true);
		$user_id = $this->input->post('user_id', true);
		$limit = $this->input->post('limit', true);

		$review = $this->review_model->get_review($product_id, $user_id);
		if (auth_check() && !empty($review)) {
			if (user()->role == "admin" || user()->id == $review->user_id) {
				$this->review_model->delete_review($id, $product_id);
			}
		}

		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["reviews"] = $this->review_model->get_limited_reviews($product_id, $limit);
		$data['review_count'] = $this->review_model->get_review_count($data["product"]->id);
		$data['review_limit'] = $limit;

		$this->load->view('product/details/_make_review', $data);
	}

	//make comment
	public function make_comment()
	{
		if ($this->general_settings->product_comments != 1) {
			exit();
		}
		$limit = $this->input->post('limit', true);
		$product_id = $this->input->post('product_id', true);

		if (auth_check()) {
			$this->comment_model->add_comment();
		} else {
			if ($this->recaptcha_verify_request()) {
				$this->comment_model->add_comment();
			}
		}

		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data['comment_count'] = $this->comment_model->get_product_comment_count($product_id);
		$data['comments'] = $this->comment_model->get_comments($product_id, $limit);
		$data['comment_limit'] = $limit;

		$this->load->view('product/details/_comments', $data);
	}

	//load more comment
	public function load_more_comment()
	{
		$product_id = $this->input->post('product_id', true);
		$limit = $this->input->post('limit', true);
		$new_limit = $limit + $this->comment_limit;
		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["comments"] = $this->comment_model->get_comments($product_id, $new_limit);
		$data['comment_count'] = $this->comment_model->get_product_comment_count($data["product"]->id);
		$data['comment_limit'] = $new_limit;

		$this->load->view('product/details/_comments', $data);
	}

	//delete comment
	public function delete_comment()
	{
		$id = $this->input->post('id', true);
		$product_id = $this->input->post('product_id', true);
		$limit = $this->input->post('limit', true);

		$comment = $this->comment_model->get_comment($id);
		if (auth_check() && !empty($comment)) {
			if (user()->role == "admin" || user()->id == $comment->user_id) {
				$this->comment_model->delete_comment($id);
			}
		}

		$data["product"] = $this->product_model->get_product_by_id($product_id);
		$data["comments"] = $this->comment_model->get_comments($product_id, $limit);
		$data['comment_count'] = $this->comment_model->get_product_comment_count($data["product"]->id);
		$data['comment_limit'] = $limit;

		$this->load->view('product/details/_comments', $data);
	}

	//delete comment
	public function load_subcomment_box()
	{
		$comment_id = $this->input->post('comment_id', true);
		$limit = $this->input->post('limit', true);
		$data["parent_comment"] = $this->comment_model->get_comment($comment_id);
		$data["comment_limit"] = $limit;
		$this->load->view('product/details/_make_subcomment', $data);
	}

	//set product as sold
	public function set_product_as_sold()
	{
		$product_id = $this->input->post('product_id', true);
		if (auth_check()) {
			$this->product_model->set_product_as_sold($product_id);
		}
	}

	//add or remove favorites
	public function add_remove_favorites()
	{
		$product_id = $this->input->post('product_id', true);
		$this->product_model->add_remove_favorites($product_id);
		redirect($this->agent->referrer());
	}

	//add or remove favorites
	public function add_remove_favorite_ajax()
	{
		$product_id = $this->input->post('product_id', true);
		$this->product_model->add_remove_favorites($product_id);
	}

	//get states
	public function get_states()
	{
		$country_id = $this->input->post('country_id', true);
		$states = $this->location_model->get_states_by_country($country_id);
		foreach ($states as $item) {
			echo '<option value="' . $item->id . '">' . $item->name . '</option>';
		}
	}

	//get cities
	public function get_cities()
	{
		$state_id = $this->input->post('state_id', true);
		$cities = $this->location_model->get_cities_by_state($state_id);
		foreach ($cities as $item) {
			echo '<option value="' . $item->id . '">' . $item->name . '</option>';
		}
	}

	//show address on map
	public function show_address_on_map()
	{
		$country_text = $this->input->post('country_text', true);
		$country_val = $this->input->post('country_val', true);
		$state_text = $this->input->post('state_text', true);
		$state_val = $this->input->post('state_val', true);
		$address = $this->input->post('address', true);
		$zip_code = $this->input->post('zip_code', true);

		$adress_details = $address . " " . $zip_code;
		$data["map_address"] = "";
		if (!empty($adress_details)) {
			$data["map_address"] = $adress_details . " ";
		}
		if (!empty($state_val)) {
			$data["map_address"] = $data["map_address"] . $state_text . " ";
		}
		if (!empty($country_val)) {
			$data["map_address"] = $data["map_address"] . $country_text;
		}

		$this->load->view('product/_load_map', $data);
	}

	//get activated product system
	public function get_activated_product_system()
	{
		$array = array(
			'active_system_count' => 0,
			'active_system_value' => "",
		);
		if ($this->general_settings->marketplace_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "sell_on_site";
		}
		if ($this->general_settings->classified_ads_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "ordinary_listing";
		}
		if ($this->general_settings->bidding_system == 1) {
			$array['active_system_count'] = $array['active_system_count'] + 1;
			$array['active_system_value'] = "bidding";
		}
		return $array;
	}

	/*
	*------------------------------------------------------------------------------------------
	* LICENSE KEYS
	*------------------------------------------------------------------------------------------
	*/
	//add license keys
	public function add_license_keys()
	{
		post_method();
		$product_id = $this->input->post('product_id', true);
		$product = $this->product_model->get_product_by_id($product_id);

		if (!empty($product)) {
			if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
				$this->product_model->add_license_keys($product_id);
				$this->session->set_flashdata('success', trans("msg_add_license_keys"));
				$data = array(
					'result' => 1,
					'success_message' => $this->load->view('partials/_messages', '', true)
				);
				echo json_encode($data);
				reset_flash_data();
			}
		}
	}

	//delete license key
	public function delete_license_key()
	{
		post_method();
		$id = $this->input->post('id', true);
		$product_id = $this->input->post('product_id', true);
		$product = $this->product_model->get_product_by_id($product_id);
		if (!empty($product)) {
			if ($this->auth_user->id == $product->user_id || $this->auth_user->role == 'admin') {
				$this->product_model->delete_license_key($id);
			}
		}
	}

	//refresh license keys list
	public function refresh_license_keys_list()
	{
		post_method();
		$product_id = $this->input->post('product_id', true);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		if (!empty($data['product'])) {
			if ($this->auth_user->id == $data['product']->user_id || $this->auth_user->role == 'admin') {
				$data['license_keys'] = $this->product_model->get_license_keys($product_id);
				$this->load->view("product/license/_license_keys_list", $data);
			}
		}
	}
}

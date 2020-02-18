<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile_controller extends Home_Core_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->pagination_per_page = 15;
	}

	/**
	 * Profile
	 */
	public function profile($slug)
	{
		$slug = decode_slug($slug);
		$data["user"] = $this->auth_model->get_user_by_slug($slug);
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}

		$data['title'] = get_shop_name($data["user"]);
		$data['description'] = $data["user"]->username . " - " . $this->app_name;
		$data['keywords'] = $data["user"]->username . "," . $this->app_name;
		$data["active_tab"] = "products";
		
		if ($data["user"]->role == 'member') {
			redirect(lang_base_url() . 'favorites/' . $data["user"]->slug);
		}


		//set pagination
		$pagination = $this->paginate(generate_profile_url($data["user"]), $this->product_model->get_user_products_count($data["user"]->slug), $this->pagination_per_page);
		$data['products'] = $this->product_model->get_paginated_user_products($data["user"]->slug, $pagination['per_page'], $pagination['offset']);

		$this->load->view('partials/_header', $data);
		$this->load->view('profile/profile', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Pending Products
	 */
	public function pending_products()
	{
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_multi_vendor_active()) {
			redirect(lang_base_url());
		}
		$data["user"] = user();
		$data['title'] = trans("pending_products");
		$data['description'] = trans("pending_products") . " - " . $this->app_name;
		$data['keywords'] = trans("pending_products") . "," . $this->app_name;
		$data["active_tab"] = "pending_products";
		//set pagination
		$pagination = $this->paginate(lang_base_url() . "pending-products", $this->product_model->get_user_pending_products_count($data["user"]->id), $this->pagination_per_page);
		$data['products'] = $this->product_model->get_paginated_user_pending_products($data["user"]->id, $pagination['per_page'], $pagination['offset']);
		

		$this->load->view('partials/_header', $data);
		$this->load->view('profile/pending_products', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Drafts
	 */
	public function drafts()
	{
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_multi_vendor_active()) {
			redirect(lang_base_url());
		}
		$data["user"] = user();
		$data['title'] = trans("drafts");
		$data['description'] = trans("drafts") . " - " . $this->app_name;
		$data['keywords'] = trans("drafts") . "," . $this->app_name;
		$data["active_tab"] = "drafts";
		//set pagination
		$pagination = $this->paginate(lang_base_url() . "drafts", $this->product_model->get_user_drafts_count($data["user"]->id), $this->pagination_per_page);
		$data['products'] = $this->product_model->get_paginated_user_drafts($data["user"]->id, $pagination['per_page'], $pagination['offset']);
		
		$this->load->view('partials/_header', $data);
		$this->load->view('profile/drafts', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Downloads
	 */
	public function downloads()
	{
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_sale_active()) {
			redirect(lang_base_url());
		}
		if ($this->general_settings->digital_products_system == 0) {
			redirect(lang_base_url());
		}
		$data["user"] = user();
		$data['title'] = trans("downloads");
		$data['description'] = trans("downloads") . " - " . $this->app_name;
		$data['keywords'] = trans("downloads") . "," . $this->app_name;
		$data["active_tab"] = "downloads";
		//set pagination
		$pagination = $this->paginate(lang_base_url() . "downloads", $this->product_model->get_user_downloads_count($data["user"]->id), $this->pagination_per_page);
		$data['items'] = $this->product_model->get_paginated_user_downloads($data["user"]->id, $pagination['per_page'], $pagination['offset']);
		
		$this->load->view('partials/_header', $data);
		$this->load->view('profile/downloads', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Hidden Products
	 */
	public function hidden_products()
	{
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_multi_vendor_active()) {
			redirect(lang_base_url());
		}
		$data["user"] = user();

		$data['title'] = trans("hidden_products");
		$data['description'] = trans("hidden_products") . " - " . $this->app_name;
		$data['keywords'] = trans("hidden_products") . "," . $this->app_name;

		$data["active_tab"] = "hidden_products";
		//set pagination
		$pagination = $this->paginate(lang_base_url() . "hidden-products", $this->product_model->get_user_hidden_products_count($data["user"]->id), $this->pagination_per_page);
		$data['products'] = $this->product_model->get_paginated_user_hidden_products($data["user"]->id, $pagination['per_page'], $pagination['offset']);
		
		$this->load->view('partials/_header', $data);
		$this->load->view('profile/pending_products', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Favorites
	 */
	public function favorites($slug)
	{
		$slug = decode_slug($slug);
		$data["user"] = $this->auth_model->get_user_by_slug($slug);
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("favorites");
		$data['description'] = trans("favorites") . " - " . $this->app_name;
		$data['keywords'] = trans("favorites") . "," . $this->app_name;
		$data["active_tab"] = "favorites";
		$data["products"] = $this->product_model->get_user_favorited_products($data["user"]->id);
		

		$this->load->view('partials/_header', $data);
		$this->load->view('profile/favorites', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Followers
	 */
	public function followers($slug)
	{
		$slug = decode_slug($slug);
		$data["user"] = $this->auth_model->get_user_by_slug($slug);
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("followers");
		$data['description'] = trans("followers") . " - " . $this->app_name;
		$data['keywords'] = trans("followers") . "," . $this->app_name;
		$data["active_tab"] = "followers";
		$data["followers"] = $this->profile_model->get_followers($data["user"]->id);

		$this->load->view('partials/_header', $data);
		$this->load->view('profile/followers', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Following
	 */
	public function following($slug)
	{
		$slug = decode_slug($slug);
		$data["user"] = $this->auth_model->get_user_by_slug($slug);
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("following");
		$data['description'] = trans("following") . " - " . $this->app_name;
		$data['keywords'] = trans("following") . "," . $this->app_name;
		$data["active_tab"] = "following";
		$data["following_users"] = $this->profile_model->get_following_users($data["user"]->id);
		$this->load->view('partials/_header', $data);
		$this->load->view('profile/following', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Reviews
	 */
	public function reviews($slug)
	{
		$slug = decode_slug($slug);
		if ($this->general_settings->user_reviews != 1) {
			redirect(lang_base_url());
		}

		$data["user"] = $this->auth_model->get_user_by_slug($slug);
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		if ($data["user"]->role != 'admin' && $data["user"]->role != 'vendor') {
			redirect(lang_base_url());
		}

		$data['title'] = get_shop_name($data["user"]) . " " . trans("reviews");
		$data['description'] = $data["user"]->username . " " . trans("reviews") . " - " . $this->app_name;
		$data['keywords'] = $data["user"]->username . " " . trans("reviews") . "," . $this->app_name;
		$data["active_tab"] = "reviews";
		$data["reviews"] = $this->user_review_model->get_reviews($data["user"]->id);

		$data['review_count'] = $this->user_review_model->get_review_count($data["user"]->id);
		$data['reviews'] = $this->user_review_model->get_limited_reviews($data["user"]->id, 5);
		$data['review_limit'] = 5;

		$this->load->view('partials/_header', $data);
		$this->load->view('profile/reviews', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Update Profile
	 */
	public function update_profile()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("update_profile");
		$data['description'] = trans("update_profile") . " - " . $this->app_name;
		$data['keywords'] = trans("update_profile") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "update_profile";
		
		$this->load->view('partials/_header', $data);
		$this->load->view('settings/update_profile', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Update Profile Post
	 */
	public function update_profile_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$user_id = user()->id;
		$action = $this->input->post('submit', true);

		if ($action == "resend_activation_email") {
			//send activation email
			$this->load->model("email_model");
			$this->email_model->send_email_activation($user_id);
			$this->session->set_flashdata('success', trans("msg_send_confirmation_email"));
			redirect($this->agent->referrer());
		}

		//validate inputs
		$this->form_validation->set_rules('username', trans("username"), 'required|xss_clean|max_length[255]');
		$this->form_validation->set_rules('email', trans("email"), 'required|xss_clean');
		if ($this->form_validation->run() === false) {
			$this->session->set_flashdata('errors', validation_errors());
			redirect($this->agent->referrer());
		} else {

			$data = array(
				'username' => $this->input->post('username', true),
				'slug' => str_slug($this->input->post('slug', true)),
				'email' => $this->input->post('email', true),
				'send_email_new_message' => $this->input->post('send_email_new_message', true)
			);

			//is email unique
			if (!$this->auth_model->is_unique_email($data["email"], $user_id)) {
				$this->session->set_flashdata('error', trans("msg_email_unique_error"));
				redirect($this->agent->referrer());
				exit();
			}
			//is username unique
			if (!$this->auth_model->is_unique_username($data["username"], $user_id)) {
				$this->session->set_flashdata('error', trans("msg_username_unique_error"));
				redirect($this->agent->referrer());
				exit();
			}
			//is slug unique
			if ($this->auth_model->check_is_slug_unique($data["slug"], $user_id)) {
				$this->session->set_flashdata('error', trans("msg_slug_unique_error"));
				redirect($this->agent->referrer());
				exit();
			}

			if ($this->profile_model->update_profile($data, $user_id)) {
				$this->session->set_flashdata('success', trans("msg_updated"));
				//check email changed
				if ($this->profile_model->check_email_updated($user_id)) {
					$this->session->set_flashdata('success', trans("msg_send_confirmation_email"));
				}
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Shop Settings
	 */
	public function shop_settings()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("shop_settings");
		$data['description'] = trans("shop_settings") . " - " . $this->app_name;
		$data['keywords'] = trans("shop_settings") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "shop_settings";
		
		$this->load->view('partials/_header', $data);
		$this->load->view('settings/shop_settings', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Shop Settings Post
	 */
	public function shop_settings_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		if ($this->profile_model->update_shop_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
	}


	/**
	 * Contact Informations
	 */
	public function contact_informations()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("contact_informations");
		$data['description'] = trans("contact_informations") . " - " . $this->app_name;
		$data['keywords'] = trans("contact_informations") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "contact_informations";
		$data["countries"] = $this->location_model->get_countries();
		$data["states"] = $this->location_model->get_states_by_country($data["user"]->country_id);
		$data["cities"] = $this->location_model->get_cities_by_state($data["user"]->state_id);
		
		$this->load->view('partials/_header', $data);
		$this->load->view('settings/contact_informations', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Contact Informations Post
	 */
	public function contact_informations_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		if ($this->profile_model->update_contact_informations()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Shipping Address
	 */
	public function shipping_address()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		$data['title'] = trans("shipping_address");
		$data['description'] = trans("shipping_address") . " - " . $this->app_name;
		$data['keywords'] = trans("shipping_address") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "shipping_address";
		$data["countries"] = $this->location_model->get_countries();
		
		$this->load->view('partials/_header', $data);
		$this->load->view('settings/shipping_address', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Shipping Address Post
	 */
	public function shipping_address_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		if ($this->profile_model->update_shipping_address()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Social Media
	 */
	public function social_media()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("social_media");
		$data['description'] = trans("social_media") . " - " . $this->app_name;
		$data['keywords'] = trans("social_media") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "social_media";
		
		$this->load->view('partials/_header', $data);
		$this->load->view('settings/social_media', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Social Media Post
	 */
	public function social_media_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		if ($this->profile_model->update_social_media()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Change Password
	 */
	public function change_password()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("change_password");
		$data['description'] = trans("change_password") . " - " . $this->app_name;
		$data['keywords'] = trans("change_password") . "," . $this->app_name;
		$data["user"] = user();
		if (empty($data["user"])) {
			redirect(lang_base_url());
		}
		$data["active_tab"] = "change_password";

		$this->load->view('partials/_header', $data);
		$this->load->view('settings/change_password', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Change Password Post
	 */
	public function change_password_post()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$old_password_exists = $this->input->post('old_password_exists', true);

		if ($old_password_exists == 1) {
			$this->form_validation->set_rules('old_password', trans("old_password"), 'required|xss_clean');
		}
		$this->form_validation->set_rules('password', trans("password"), 'required|xss_clean|min_length[4]|max_length[50]');
		$this->form_validation->set_rules('password_confirm', trans("password_confirm"), 'required|xss_clean|matches[password]');

		if ($this->form_validation->run() == false) {
			$this->session->set_flashdata('errors', validation_errors());
			$this->session->set_flashdata('form_data', $this->profile_model->change_password_input_values());
			redirect($this->agent->referrer());
		} else {
			if ($this->profile_model->change_password($old_password_exists)) {
				$this->session->set_flashdata('success', trans("msg_change_password_success"));
				redirect($this->agent->referrer());
			} else {
				$this->session->set_flashdata('error', trans("msg_change_password_error"));
				redirect($this->agent->referrer());
			}
		}
	}

	/**
	 * Follow Unfollow User
	 */
	public function follow_unfollow_user()
	{
		//check user
		if (!auth_check()) {
			redirect(lang_base_url());
		}

		$this->profile_model->follow_unfollow_user();
		redirect($this->agent->referrer());
	}
}

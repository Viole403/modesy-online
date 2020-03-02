<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ajax_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->review_limit = 6;
	}

	/*
	*------------------------------------------------------------------------------------------
	* SEARCH LOCATION
	*------------------------------------------------------------------------------------------
	*/

	//search location
	public function search_location()
	{
		$input_value = $this->input->post('input_value', true);
		$input_value = remove_special_characters($input_value);
		$data = array(
			'result' => 0,
			'response' => ''
		);

		if (!empty($input_value)) {
			$response = '<ul>';
			$countries = $this->location_model->search_countries($input_value);
			if (!empty($countries)) {
				$data['result'] = 1;
				foreach ($countries as $country) {
					$response .= '<li><a href="javascript:void(0)" data-country="' . $country->id . '"><i class="icon-map-marker"></i>' . $country->name . '</a></li>';
				}
			}
			$states = $this->location_model->search_states($input_value);
			if (!empty($states)) {
				$data['result'] = 1;
				foreach ($states as $state) {
					$response .= '<li><a href="javascript:void(0)" data-country="' . $state->country_id . '" data-state="' . $state->id . '"><i class="icon-map-marker"></i>' . $state->name . ', ' . $state->country_name . '</a></li>';
				}
			}
			$cities = $this->location_model->search_cities($input_value);
			if (!empty($cities)) {
				$data['result'] = 1;
				foreach ($cities as $city) {
					$response .= '<li><a href="javascript:void(0)" data-country="' . $city->country_id . '" data-state="' . $city->state_id . '" data-city="' . $city->id . '"><i class="icon-map-marker"></i>' . $city->name . ', ' . $city->state_name . ', ' . $city->country_name . '</a></li>';
				}
			}
			$response .= '</ul>';
			$data['response'] = $response;
		}
		echo json_encode($data);
	}

	/*
	*------------------------------------------------------------------------------------------
	* AJAX SEARCH
	*------------------------------------------------------------------------------------------
	*/

	//ajax search
	public function ajax_search()
	{
		$lang_base_url = $this->input->post('lang_base_url', true);
		$search_type = $this->input->post('search_type', true);
		$input_value = $this->input->post('input_value', true);
		$input_value = remove_special_characters($input_value);

		$data = array(
			'result' => 0,
			'response' => ''
		);

		if (!empty($search_type) && !empty($input_value)) {
			if ($search_type == 'member') {
				$data['result'] = 1;
				$response = '<ul>';
				$members = $this->profile_model->search_members_limited($input_value);
				if (!empty($members)) {
					foreach ($members as $member) {
						$response .= '<li><a href="' . $lang_base_url . 'profile' . '/' . $member->slug . '">' . $member->username . '</a></li>';
					}
				} else {
					$response .= '<li><a href="' . $lang_base_url . 'members?search=' . $input_value . '">' . $input_value . '</a></li>';
				}
				$response .= '</ul>';
				$data['response'] = $response;
			} else {
				$data['result'] = 1;
				$response = '<ul>';
				$products = $this->product_model->search_products($input_value);
				if (!empty($products)) {
					foreach ($products as $product) {
						$response .= '<li><a href="' . $lang_base_url . $product->slug . '">' . $product->title . '</a></li>';
					}
				} else {
					$response .= '<li><a href="' . $lang_base_url . 'products?search=' . $input_value . '">' . $input_value . '</a></li>';
				}
				$response .= '</ul>';
				$data['response'] = $response;
			}
		}
		echo json_encode($data);
	}

	/*
	*------------------------------------------------------------------------------------------
	* USER REVIEW FUNCTIONS
	*------------------------------------------------------------------------------------------
	*/

	//add user review
	public function add_user_review()
	{
		if ($this->general_settings->user_reviews != 1) {
			exit();
		}
		$seller_id = $this->input->post('seller_id', true);
		$review = $this->user_review_model->get_review_by_user($seller_id, user()->id);
		if (!empty($review)) {
			echo "voted_error";
		} else {
			$this->user_review_model->add_review();
		}
	}

	//load more review
	public function load_more_user_review()
	{
		$seller_id = $this->input->post('seller_id', true);
		$limit = $this->input->post('limit', true);
		$new_limit = $limit + $this->review_limit;
		$data["user"] = $this->auth_model->get_user($seller_id);
		$data["reviews"] = $this->user_review_model->get_limited_reviews($seller_id, $new_limit);
		$data['review_count'] = $this->user_review_model->get_review_count($seller_id);
		$data['review_limit'] = $new_limit;

		$this->load->view('profile/_user_reviews', $data);
	}

	//delete user review
	public function delete_user_review()
	{
		$id = $this->input->post('review_id', true);
		$this->user_review_model->delete_review($id);
	}

	/*
	*------------------------------------------------------------------------------------------
	* EMAIL FUNCTIONS
	*------------------------------------------------------------------------------------------
	*/

	//send email
	public function send_email()
	{
		$this->load->model("email_model");
		$email_type = $this->input->post('email_type', true);
		if ($email_type == 'contact') {
			$this->send_email_contact_message();
		} elseif ($email_type == 'new_order') {
			$this->send_email_new_order();
		} elseif ($email_type == 'new_product') {
			$this->send_email_new_product();
		} elseif ($email_type == 'order_shipped') {
			$this->send_email_order_shipped();
		} elseif ($email_type == 'new_message') {
			$this->send_email_new_message();
		} elseif ($email_type == 'email_general') {
			$this->send_email_general();
		}
	}

	//send email contact message
	public function send_email_contact_message()
	{
		if ($this->general_settings->send_email_contact_messages == 1) {
			$data = array(
				'subject' => trans("contact_message"),
				'to' => $this->general_settings->mail_options_account,
				'template_path' => "email/email_contact_message",
				'message_name' => $this->input->post('message_name', true),
				'message_email' => $this->input->post('message_email', true),
				'message_text' => $this->input->post('message_text', true)
			);
			$this->email_model->send_email($data);
		}
	}

	//send email order summary to user
	public function send_email_new_order()
	{
		if ($this->general_settings->send_email_buyer_purchase == 1) {
			$order_id = $this->input->post('order_id', true);
			$order_id = clean_number($order_id);
			$order = get_order($order_id);
			$order_products = $this->order_model->get_order_products($order_id);
			$order_shipping = get_order_shipping($order_id);
			if (!empty($order)) {
				//send to buyer
				$to = "";
				if (!empty($order_shipping)) {
					$to = $order_shipping->shipping_email;
				}
				if ($order->buyer_type == "registered") {
					$user = get_user($order->buyer_id);
					if (!empty($user)) {
						$to = $user->email;
					}
				}
				$data = array(
					'subject' => trans("email_text_thank_for_order"),
					'order' => $order,
					'order_products' => $order_products,
					'to' => $to,
					'template_path' => "email/email_new_order"
				);
				$this->email_model->send_email($data);

				//send to seller
				if (!empty($order_products)) {
					$seller_ids = array();
					foreach ($order_products as $order_product) {
						$seller = get_user($order_product->seller_id);
						if (!empty($seller)) {
							if ($seller->send_email_when_item_sold == 1 && !in_array($seller->id, $seller_ids)) {
								array_push($seller_ids, $seller->id);
								$seller_order_products = $this->order_model->get_seller_order_products($order_id, $seller->id);
								$data = array(
									'subject' => trans("you_have_new_order"),
									'order' => $order,
									'order_products' => $seller_order_products,
									'to' => $seller->email,
									'template_path' => "email/email_new_order_seller"
								);
								$this->email_model->send_email($data);
							}
						}
					}
				}
			}
		}
	}

	//send email new product
	public function send_email_new_product()
	{
		if ($this->general_settings->send_email_new_product == 1) {
			$product_id = $this->input->post('product_id', true);
			$product = $this->product_model->get_product_by_id($product_id);
			if (!empty($product)) {
				$data = array(
					'subject' => trans("email_text_new_product"),
					'product_url' => lang_base_url() . $product->slug,
					'to' => $this->general_settings->mail_options_account,
					'template_path' => "email/email_new_product"
				);
				$this->email_model->send_email($data);
			}
		}
	}

	//send email new message
	public function send_email_new_message()
	{
		$sender_id = $this->input->post('sender_id', true);
		$receiver_id = $this->input->post('receiver_id', true);
		$receiver = get_user($receiver_id);
		if (!empty($receiver) && !empty($sender_id)) {
			$data = array(
				'subject' => trans("you_have_new_message"),
				'to' => $receiver->email,
				'template_path' => "email/email_new_message",
				'message_sender' => "",
				'message_subject' => $this->input->post('message_subject', true),
				'message_text' => $this->input->post('message_text', true)
			);
			$sender = get_user($sender_id);
			if (!empty($sender)) {
				$data['message_sender'] = $sender->username;
			}
			$this->email_model->send_email($data);
		}
	}

	//send email order shipped
	public function send_email_order_shipped()
	{
		if ($this->general_settings->send_email_order_shipped == 1) {
			$order_product_id = $this->input->post('order_product_id', true);
			$order_product = $this->order_model->get_order_product($order_product_id);
			if (!empty($order_product)) {
				$order = get_order($order_product->order_id);
				if (!empty($order)) {
					$to = $order->shipping_email;
					if ($order->buyer_type == "registered") {
						$user = get_user($order->buyer_id);
						$to = $user->email;
					}
					$data = array(
						'subject' => trans("your_order_shipped"),
						'to' => $to,
						'template_path' => "email/email_order_shipped",
						'order' => $order,
						'order_product' => $order_product
					);
					$this->email_model->send_email($data);
				}
			}
		}
	}

	//send email general
	public function send_email_general()
	{
		$data = array(
			'template_path' => "email/email_general",
			'to' => $this->input->post('to', true),
			'subject' => $this->input->post('subject', true),
			'email_content' => $this->input->post('email_content', true),
			'email_link' => $this->input->post('email_link', true),
			'email_button_text' => $this->input->post('email_button_text', true)
		);
		$this->email_model->send_email($data);
	}

}

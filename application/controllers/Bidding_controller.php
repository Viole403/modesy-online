<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bidding_controller extends Home_Core_Controller
{
	/**
	 * Bidding Status
	 *
	 * 1. new_quote_request
	 * 2. pending_quote
	 * 3. pending_payment
	 * 4. rejected_quote
	 * 5. closed
	 * 6. completed
	 */

	public function __construct()
	{
		parent::__construct();
		if (!$this->auth_check) {
			redirect(lang_base_url());
		}
		if (!is_bidding_system_active()) {
			redirect(lang_base_url());
		}
		$this->load->model('bidding_model');
		$this->rows_per_page = 15;
	}

	/**
	 * Request Quote
	 */
	public function request_quote()
	{
		$product_id = $this->input->post('product_id', true);
		$product = $this->product_model->get_product_by_id($product_id);
		if (!empty($product)) {
			if ($product->user_id == $this->auth_user->id) {
				$this->session->set_flashdata('product_details_error', trans("msg_quote_request_error"));
				redirect($this->agent->referrer());
			}
			$quote_id = $this->bidding_model->request_quote($product);
			if ($quote_id) {
				//send email
				$seller = get_user($product->user_id);
				if (!empty($seller) && $this->general_settings->send_email_bidding_system == 1) {
					$email_data = array(
						'email_type' => 'email_general',
						'to' => $seller->email,
						'subject' => trans("quote_request"),
						'email_content' => trans("you_have_new_quote_request") . "<br>" . trans("quote") . ": " . "<strong>#" . $quote_id . "</strong>",
						'email_link' => lang_base_url() . "quote-requests",
						'email_button_text' => trans("view_details")
					);
					$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
				}
			}
			$this->session->set_flashdata('product_details_success', trans("msg_quote_request_sent"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Submit Quote
	 */
	public function submit_quote()
	{
		$id = $this->input->post('id', true);
		$quote_request = $this->bidding_model->get_quote_request($id);
		if ($this->bidding_model->submit_quote($quote_request)) {
			//send email
			$buyer = get_user($quote_request->buyer_id);
			if (!empty($buyer) && $this->general_settings->send_email_bidding_system == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $buyer->email,
					'subject' => trans("quote_request"),
					'email_content' => trans("your_quote_request_replied") . "<br>" . trans("quote") . ": " . "<strong>#" . $quote_request->id . "</strong>",
					'email_link' => lang_base_url() . "sent-quote-requests",
					'email_button_text' => trans("view_details")
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Accept Quote
	 */
	public function accept_quote()
	{
		$id = $this->input->post('id', true);
		$quote_request = $this->bidding_model->get_quote_request($id);
		if ($this->bidding_model->accept_quote($quote_request)) {
			//send email
			$seller = get_user($quote_request->seller_id);
			if (!empty($seller) && $this->general_settings->send_email_bidding_system == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $seller->email,
					'subject' => trans("quote_request"),
					'email_content' => trans("your_quote_accepted") . "<br>" . trans("quote") . ": " . "<strong>#" . $quote_request->id . "</strong>",
					'email_link' => lang_base_url() . "quote-requests",
					'email_button_text' => trans("view_details")
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Reject Quote
	 */
	public function reject_quote()
	{
		$id = $this->input->post('id', true);
		$quote_request = $this->bidding_model->get_quote_request($id);
		if ($this->bidding_model->reject_quote($quote_request)) {
			//send email
			$seller = get_user($quote_request->seller_id);
			if (!empty($seller) && $this->general_settings->send_email_bidding_system == 1) {
				$email_data = array(
					'email_type' => 'email_general',
					'to' => $seller->email,
					'subject' => trans("quote_request"),
					'email_content' => trans("your_quote_rejected") . "<br>" . trans("quote") . ": " . "<strong>#" . $quote_request->id . "</strong>",
					'email_link' => lang_base_url() . "quote-requests",
					'email_button_text' => trans("view_details")
				);
				$this->session->set_userdata('mds_send_email_data', json_encode($email_data));
			}
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Quote Requests
	 */
	public function quote_requests()
	{
		$data["user"] = user();
		$data['title'] = trans("quote_requests");
		$data['description'] = trans("quote_requests") . " - " . $this->app_name;
		$data['keywords'] = trans("quote_requests") . "," . $this->app_name;

		if (is_user_vendor()) {
			$data['active_tab'] = "received_quote_requests";
			$data['received_request_count'] = $this->bidding_model->get_received_quote_requests_count($data["user"]->id);
			$data['sent_request_count'] = $this->bidding_model->get_sent_quote_requests_count($data["user"]->id);
			//set pagination
			$pagination = $this->paginate(lang_base_url() . 'quote-requests', $data['received_request_count'], $this->rows_per_page);
			$data['quote_requests'] = $this->bidding_model->get_received_quote_requests_paginated($data["user"]->id, $pagination['per_page'], $pagination['offset']);

			$this->load->view('partials/_header', $data);
			$this->load->view('bidding/received_quote_requests', $data);
			$this->load->view('partials/_footer');
		} else {
			$data['active_tab'] = "sent_request_count";
			$data['received_request_count'] = $this->bidding_model->get_received_quote_requests_count($data["user"]->id);
			$data['sent_request_count'] = $this->bidding_model->get_sent_quote_requests_count($data["user"]->id);
			//set pagination
			$pagination = $this->paginate(lang_base_url() . 'quote-requests', $data['sent_request_count'], $this->rows_per_page);
			$data['quote_requests'] = $this->bidding_model->get_sent_quote_requests_paginated($data["user"]->id, $pagination['per_page'], $pagination['offset']);

			$this->load->view('partials/_header', $data);
			$this->load->view('bidding/sent_quote_requests', $data);
			$this->load->view('partials/_footer');
		}
	}

	/**
	 * Sent Quote Requests
	 */
	public function sent_quote_requests()
	{
		$data["user"] = user();
		$data['title'] = trans("quote_requests");
		$data['description'] = trans("quote_requests") . " - " . $this->app_name;
		$data['keywords'] = trans("quote_requests") . "," . $this->app_name;

		if (!is_user_vendor()) {
			redirect(lang_base_url() . "quote_requests");
		}

		$data['active_tab'] = "sent_quote_requests";
		$data['received_request_count'] = $this->bidding_model->get_received_quote_requests_count($data["user"]->id);
		$data['sent_request_count'] = $this->bidding_model->get_sent_quote_requests_count($data["user"]->id);
		//set pagination
		$pagination = $this->paginate(lang_base_url() . 'sent-quote-requests', $data['sent_request_count'], $this->rows_per_page);
		$data['quote_requests'] = $this->bidding_model->get_sent_quote_requests_paginated($data["user"]->id, $pagination['per_page'], $pagination['offset']);

		$this->load->view('partials/_header', $data);
		$this->load->view('bidding/sent_quote_requests', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Delete Quote Request
	 */
	public function delete_quote_request()
	{
		$id = $this->input->post('id', true);
		$this->bidding_model->delete_quote_request($id);
		$this->bidding_model->delete_quote_request_if_both_deleted($id);
	}

}

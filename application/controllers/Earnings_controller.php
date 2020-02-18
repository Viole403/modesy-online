<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Earnings_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		if (!auth_check()) {
			redirect(lang_base_url());
		}
		if (!is_user_vendor()) {
			redirect(lang_base_url());
		}
		if (!is_sale_active()) {
			redirect(lang_base_url());
		}
		$this->earnings_per_page = 15;
		$this->user_id = user()->id;
	}

	/**
	 * Earnings
	 */
	public function earnings()
	{
		$data['title'] = trans("earnings");
		$data['description'] = trans("earnings") . " - " . $this->app_name;
		$data['keywords'] = trans("earnings") . "," . $this->app_name;
		$data["active_tab"] = "earnings";
		$data['user'] = user();
		$pagination = $this->paginate(lang_base_url() . 'earnings', $this->earnings_model->get_earnings_count($this->user_id), $this->earnings_per_page);
		$data['earnings'] = $this->earnings_model->get_paginated_earnings($this->user_id, $pagination['per_page'], $pagination['offset']);

		$this->load->view('partials/_header', $data);
		$this->load->view('earnings/earnings', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Payouts
	 */
	public function payouts()
	{
		$data['title'] = trans("payouts");
		$data['description'] = trans("payouts") . " - " . $this->app_name;
		$data['keywords'] = trans("payouts") . "," . $this->app_name;
		$data["active_tab"] = "payouts";
		$data['user'] = user();
		$pagination = $this->paginate(lang_base_url() . 'earnings', $this->earnings_model->get_payouts_count($this->user_id), $this->earnings_per_page);
		$data['payouts'] = $this->earnings_model->get_paginated_payouts($this->user_id, $pagination['per_page'], $pagination['offset']);

		$this->load->view('partials/_header', $data);
		$this->load->view('earnings/payouts', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Set Payout Account
	 */
	public function set_payout_account()
	{
		$data['title'] = trans("set_payout_account");
		$data['description'] = trans("set_payout_account") . " - " . $this->app_name;
		$data['keywords'] = trans("set_payout_account") . "," . $this->app_name;
		$data["active_tab"] = "set_payout_account";
		$data['user'] = user();
		$data['user_payout'] = $this->earnings_model->get_user_payout_account($data['user']->id);

		if (empty($this->session->flashdata('msg_payout'))) {
			if ($this->payment_settings->payout_paypal_enabled) {
				$this->session->set_flashdata('msg_payout', "paypal");
			} elseif ($this->payment_settings->payout_iban_enabled) {
				$this->session->set_flashdata('msg_payout', "iban");
			} elseif ($this->payment_settings->payout_swift_enabled) {
				$this->session->set_flashdata('msg_payout', "swift");
			}
		}

		$this->load->view('partials/_header', $data);
		$this->load->view('earnings/set_payout_account', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Set Paypal Payout Account Post
	 */
	public function set_paypal_payout_account_post()
	{
		if ($this->earnings_model->set_paypal_payout_account($this->user_id)) {
			$this->session->set_flashdata('msg_payout', "paypal");
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('msg_payout', "paypal");
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Set IBAN Payout Account Post
	 */
	public function set_iban_payout_account_post()
	{
		if ($this->earnings_model->set_iban_payout_account($this->user_id)) {
			$this->session->set_flashdata('msg_payout', "iban");
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('msg_payout', "iban");
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Set SWIFT Payout Account Post
	 */
	public function set_swift_payout_account_post()
	{
		if ($this->earnings_model->set_swift_payout_account($this->user_id)) {
			$this->session->set_flashdata('msg_payout', "swift");
			$this->session->set_flashdata('success', trans("msg_updated"));
		} else {
			$this->session->set_flashdata('msg_payout', "swift");
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}

	/**
	 * Withdraw Money Post
	 */
	public function withdraw_money_post()
	{
		$data = array(
			'user_id' => $this->user_id,
			'payout_method' => $this->input->post('payout_method', true),
			'amount' => $this->input->post('amount', true),
			'currency' => $this->input->post('currency', true),
			'status' => 0,
			'created_at' => date('Y-m-d H:i:s')
		);
		$data["amount"] = price_database_format($data["amount"]);

		//check active payouts
		$active_payouts = $this->earnings_model->get_active_payouts($this->user_id);
		if (!empty($active_payouts)) {
			$this->session->set_flashdata('error', trans("active_payment_request_error"));
			redirect($this->agent->referrer());
		}

		$min = 0;
		if ($data["payout_method"] == "paypal") {
			//check PayPal email
			$payout_paypal_email = $this->earnings_model->get_user_payout_account($this->auth_user->id);
			if (empty($payout_paypal_email) || empty($payout_paypal_email->payout_paypal_email)) {
				$this->session->set_flashdata('error', trans("msg_payout_paypal_error"));
				redirect($this->agent->referrer());
			}
			$min = $this->payment_settings->min_payout_paypal;
		}
		if ($data["payout_method"] == "iban") {
			$min = $this->payment_settings->min_payout_iban;
		}
		if ($data["payout_method"] == "swift") {
			$min = $this->payment_settings->min_payout_swift;
		}

		if ($data["amount"] <= 0) {
			$this->session->set_flashdata('error', trans("msg_error"));
			redirect($this->agent->referrer());
		}
		if ($data["amount"] < $min) {
			$this->session->set_flashdata('error', trans("invalid_withdrawal_amount"));
			redirect($this->agent->referrer());
		}
		if ($data["amount"] > user()->balance) {
			$this->session->set_flashdata('error', trans("invalid_withdrawal_amount"));
			redirect($this->agent->referrer());
		}
		if (!$this->earnings_model->withdraw_money($data)) {
			$this->session->set_flashdata('error', trans("msg_error"));
		}
		redirect($this->agent->referrer());
	}
}

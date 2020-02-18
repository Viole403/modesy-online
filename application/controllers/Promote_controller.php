<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Promote_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		//check auth
		if (!auth_check()) {
			redirect(lang_base_url());
		}
	}

	/**
	 * Pricing
	 */
	public function pricing($product_id)
	{
		if ($this->promoted_products_enabled != 1) {
			redirect(lang_base_url());
		}

		$data["product"] = $this->product_model->get_product_by_id($product_id);
		if (empty($data["product"])) {
			redirect(lang_base_url());
		}
		//check product user
		if ($data["product"]->user_id != user()->id) {
			redirect(lang_base_url());
		}

		$data['title'] = trans("promote_your_product");
		$data['description'] = trans("promote_your_product") . " - " . $this->app_name;
		$data['keywords'] = trans("promote_your_product") . "," . $this->app_name;
		$data["type"] = $this->input->get('type');
		if ($data["type"] != "new" && $data["type"] != "exist") {
			redirect($this->agent->referrer());
		} else {
			$this->session->set_userdata('mds_promote_product_type', $data["type"]);
		}

		$completed = $this->input->get('completed');
		if ($completed == 1) {
			$this->cart_model->unset_sess_cart_payment_method();
		}

		$this->load->view('partials/_header', $data);
		$this->load->view('promote/pricing', $data);
		$this->load->view('partials/_footer');
	}

	/**
	 * Pricing Post
	 */
	public function pricing_post()
	{
		$plan_type = $this->input->post('plan_type', true);
		$price_per_day = price_format_decimal($this->payment_settings->price_per_day);
		$price_per_month = price_format_decimal($this->payment_settings->price_per_month);

		$day_count = $this->input->post('day_count', true);
		$month_count = $this->input->post('month_count', true);
		$total_amount = 0;
		if ($plan_type == "daily") {
			$total_amount = number_format($day_count * $price_per_day, 2, ".", "") * 100;
			$purchased_plan = trans("daily_plan") . " (" . $day_count . " " . trans("days") . ")";
		}
		if ($plan_type == "monthly") {
			$day_count = $month_count * 30;
			$total_amount = number_format($month_count * $price_per_month, 2, ".", "") * 100;
			$purchased_plan = trans("monthly_plan") . " (" . $day_count . " " . trans("days") . ")";
		}
		$data = new stdClass();
		$data->plan_type = $this->input->post('plan_type', true);
		$data->product_id = $this->input->post('product_id', true);
		$data->day_count = $day_count;
		$data->month_count = $month_count;
		$data->total_amount = $total_amount;
		$data->purchased_plan = $purchased_plan;

		if ($this->payment_settings->free_product_promotion == 1) {
			$this->promote_model->add_to_promoted_products($data);
			$product = get_product($data->product_id);
			if (!empty($product)) {
				redirect(lang_base_url() . $product->slug);
			} else {
				redirect(lang_base_url());
			}
		} else {
			$this->session->set_userdata('modesy_selected_promoted_plan', $data);
			redirect(lang_base_url() . "cart/payment-method?payment_type=promote");
		}
	}

	/**
	 * Free Product Promote
	 */
	public function free_product_promote()
	{
		$data['title'] = trans("msg_payment_completed");
		$data['description'] = trans("msg_payment_completed") . " - " . $this->app_name;
		$data['keywords'] = trans("payment") . "," . $this->app_name;
		$data['promoted_plan'] = $this->session->userdata('modesy_selected_promoted_plan');
		if (empty($data['promoted_plan'])) {
			redirect(lang_base_url());
		}

		$data["method"] = $this->input->get('method');
		$data["transaction_number"] = $this->input->get('transaction_number');

		$this->load->view('partials/_header', $data);
		$this->load->view('cart/promote_payment_completed', $data);
		$this->load->view('partials/_footer');
	}

}

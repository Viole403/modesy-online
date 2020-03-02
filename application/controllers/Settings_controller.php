<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings_controller extends Admin_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
		//check user
		if (!is_admin()) {
			redirect(admin_url() . 'login');
		}
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* PAYMENT SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/


	/*
	* Payment Settings
	*/
	public function payment_settings()
	{
		$data['title'] = trans("payment_settings");
		$data['general_settings'] = $this->settings_model->get_general_settings();
		

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings/payment_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/**
	 * Payment Settings Post
	 */
	public function payment_settings_post()
	{
		if ($this->settings_model->update_payment_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_pay", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_paypal", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Paypal Settings Post
	 */
	public function paypal_settings_post()
	{
		if ($this->settings_model->update_paypal_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_paypal", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_paypal", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Stripe Settings Post
	 */
	public function stripe_settings_post()
	{
		if ($this->settings_model->update_stripe_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_stripe", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_stripe", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Paystack Settings Post
	 */
	public function paystack_settings_post()
	{
		if ($this->settings_model->update_paystack_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_paystack", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_paystack", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Razorpay Settings Post
	 */
	public function razorpay_settings_post()
	{
		if ($this->settings_model->update_razorpay_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_razorpay", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_razorpay", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Pagseguro Settings Post
	 */
	public function pagseguro_settings_post()
	{
		if ($this->settings_model->update_pagseguro_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_pagseguro", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_pagseguro", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Iyzico Settings Post
	 */
	public function iyzico_settings_post()
	{
		if ($this->settings_model->update_iyzico_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_iyzico", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_iyzico", 1);
			redirect($this->agent->referrer());
		}
	}


	/**
	 * Bank Transfer Settings Post
	 */
	public function bank_transfer_settings_post()
	{
		if ($this->settings_model->update_bank_transfer_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_bank_transfer", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_bank_transfer", 1);
			redirect($this->agent->referrer());
		}
	}

	/**
	 * Cash on Delivery Settings Post
	 */
	public function cash_on_delivery_settings_post()
	{
		if ($this->settings_model->update_cash_on_delivery_settings()) {
			$this->session->set_flashdata('success', trans("msg_updated"));
			$this->session->set_flashdata("mes_cash_on_delivery", 1);
			redirect($this->agent->referrer());
		} else {
			$this->session->set_flashdata('error', trans("msg_error"));
			$this->session->set_flashdata("mes_cash_on_delivery", 1);
			redirect($this->agent->referrer());
		}
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* FORM SETTINGS
	*-------------------------------------------------------------------------------------------------
	*/

	/*
	* Form Settings
	*/
	public function form_settings()
	{
		$data['title'] = trans("form_settings");

		$data['form_settings'] = $this->settings_model->get_form_settings();
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings_form/form_settings', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Form Settings Post
	*/
	public function form_settings_post()
	{
		$this->settings_model->update_form_settings();
		$this->session->set_flashdata('msg_form_settings', 1);
		$this->session->set_flashdata('success', trans("msg_updated"));
		redirect($this->agent->referrer());
	}

	/*
	* Physical Products Form Post
	*/
	public function physical_products_form_post()
	{
		$this->settings_model->update_physical_products_form();
		$this->session->set_flashdata('msg_physical', 1);
		$this->session->set_flashdata('success', trans("msg_updated"));
		redirect($this->agent->referrer());
	}

	/*
	* Digital Products Form Post
	*/
	public function digital_products_form_post()
	{
		$this->settings_model->update_digital_products_form();
		$this->session->set_flashdata('msg_digital', 1);
		$this->session->set_flashdata('success', trans("msg_updated"));
		redirect($this->agent->referrer());
	}

	/*
	* File Upload Form Post
	*/
	public function file_upload_form_post()
	{
		$this->settings_model->update_file_upload_form();
		$this->session->set_flashdata('msg_file_upload', 1);
		$this->session->set_flashdata('success', trans("msg_updated"));
		redirect($this->agent->referrer());
	}
	/*
	*-------------------------------------------------------------------------------------------------
	* SHIPPING OPTIONS
	*-------------------------------------------------------------------------------------------------
	*/

	/*
	* Shipping Options
	*/
	public function shipping_options()
	{
		$data['title'] = trans("shipping_options");

		$data['shipping_options'] = $this->settings_model->get_grouped_shipping_options();
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings_form/shipping_options', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Add Shipping Option
	*/
	public function add_shipping_option_post()
	{
		$this->settings_model->add_shipping_option();
		$this->session->set_flashdata('success_form', trans("msg_option_added"));
		redirect($this->agent->referrer());
	}

	/*
	* Edit Shipping Option
	*/
	public function edit_shipping_option($id)
	{
		$data['title'] = trans("edit_shipping_option");
		$data['main_option'] = $this->settings_model->get_shipping_option($id);
		if (empty($data['main_option'])) {
			redirect(admin_url() . "form-settings/shipping-options");
		}

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings_form/edit_shipping_option', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Edit Shipping Option Post
	*/
	public function edit_shipping_option_post()
	{
		$this->settings_model->edit_shipping_option();
		$this->session->set_flashdata('success_form', trans("msg_updated"));
		redirect($this->agent->referrer());
	}

	/*
	* Delete Shipping Option Post
	*/
	public function delete_shipping_option_post()
	{
		$common_id = $this->input->post('id', true);
		$this->settings_model->delete_shipping_option($common_id);
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* PRODUCT CONDITIONS
	*-------------------------------------------------------------------------------------------------
	*/

	/*
	* Product Conditions
	*/
	public function product_conditions()
	{
		$data['title'] = trans("product_conditions");

		$data['product_conditions'] = $this->settings_model->get_grouped_product_conditions();
		
		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings_form/product_conditions', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Add Product Condition
	*/
	public function add_product_condition_post()
	{
		$this->settings_model->add_product_condition();
		$this->session->set_flashdata('success_form', trans("msg_option_added"));
		redirect($this->agent->referrer());
	}

	/*
	* Edit Product Condition
	*/
	public function edit_product_condition($id)
	{
		$data['title'] = trans("edit_product_condition");
		$data['main_option'] = $this->settings_model->get_product_condition($id);
		if (empty($data['main_option'])) {
			redirect(admin_url() . "form-settings/product-conditions");
		}

		$this->load->view('admin/includes/_header', $data);
		$this->load->view('admin/settings_form/edit_product_condition', $data);
		$this->load->view('admin/includes/_footer');
	}

	/*
	* Edit Product Condition Post
	*/
	public function edit_product_condition_post()
	{
		$this->settings_model->edit_product_condition();
		$this->session->set_flashdata('success_form', trans("msg_updated"));
		redirect($this->agent->referrer());
	}

	/*
	* Delete Product Condition Post
	*/
	public function delete_product_condition_post()
	{
		$common_id = $this->input->post('id', true);
		$this->settings_model->delete_product_condition($common_id);
	}
}

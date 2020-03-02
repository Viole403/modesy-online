<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();

		//general settings
		$global_data['general_settings'] = $this->settings_model->get_general_settings();
		$this->general_settings = $global_data['general_settings'];
		//storage settings
		$this->storage_settings = $this->settings_model->get_storage_settings();
		//form settings
		$this->form_settings = $this->settings_model->get_form_settings();

		//set timezone
		date_default_timezone_set($this->general_settings->timezone);
		//lang base url
		$global_data['lang_base_url'] = base_url();
		//languages
		$global_data['languages'] = $this->language_model->get_active_languages();
		$this->languages = $global_data['languages'];

		//site lang
		$global_data['site_lang'] = $this->language_model->get_language($this->general_settings->site_lang);
		if (empty($global_data['site_lang'])) {
			$global_data['site_lang'] = $this->language_model->get_language('1');
		}

		$global_data['selected_lang'] = $global_data['site_lang'];
		$this->selected_lang = $global_data['site_lang'];

		//set language
		$lang_segment = $this->uri->segment(1);
		foreach ($this->languages as $lang) {
			if ($lang_segment == $lang->short_form) {
				if ($this->general_settings->multilingual_system == 1):
					$global_data['selected_lang'] = $lang;
					$global_data['lang_base_url'] = base_url() . $lang->short_form . "/";
				else:
					redirect(base_url());
				endif;
			}
		}
		$this->selected_lang = $global_data['selected_lang'];
		if (!file_exists(APPPATH . "language/" . $this->selected_lang->folder_name)) {
			echo "Language folder doesn't exists!";
			exit();
		}

		//set lang base url
		if ($this->general_settings->site_lang == $this->selected_lang->id) {
			$global_data['lang_base_url'] = base_url();
		} else {
			$global_data['lang_base_url'] = base_url() . $global_data['selected_lang']->short_form . "/";
		}
		$this->lang_base_url = $global_data['lang_base_url'];
		$global_data['rtl'] = false;
		if ($global_data['site_lang']->text_direction == "rtl") {
			$global_data['rtl'] = true;
		}
		$this->rtl = $global_data['rtl'];
		//set language
		$this->config->set_item('language', $this->selected_lang->folder_name);
		$this->lang->load("site_lang", $this->selected_lang->folder_name);

		//check auth
		$this->auth_check = auth_check();
		if ($this->auth_check) {
			$this->auth_user = user();
		}

		//settings
		$global_data['settings'] = $this->settings_model->get_settings($this->selected_lang->id);
		$this->settings = $global_data['settings'];
		$global_data['payment_settings'] = $this->settings_model->get_payment_settings();
		$this->payment_settings = $global_data['payment_settings'];

		//check promoted posts
		$this->product_model->check_promoted_products();

		$global_data['img_bg_product_small'] = base_url() . "assets/img/img_bg_product_small.jpg";
		$global_data['img_bg_blog_small'] = base_url() . "assets/img/img_bg_blog_small.jpg";
		$global_data['app_name'] = $this->general_settings->application_name;
		$this->app_name = $global_data['app_name'];

		//promoted products enabled or disabled
		$global_data['promoted_products_enabled'] = $this->general_settings->promoted_products;
		$this->promoted_products_enabled = $global_data['promoted_products_enabled'];

		$this->username_maxlength = 40;
		if (empty($this->storage_settings->aws_bucket)) {
			$this->aws_base_url = $this->storage_settings->aws_base_url;
		} else {
			$this->aws_base_url = $this->storage_settings->aws_base_url . $this->storage_settings->aws_bucket . "/";
		}
		//check promoted posts
		$this->product_model->check_promoted_products();

		$this->thousands_separator = '.';
		$this->input_initial_price = '0.00';
		if ($this->payment_settings->currency_format == 'european') {
			$this->thousands_separator = ',';
			$this->input_initial_price = '0,00';
		}
		//update last seen time
		$this->auth_model->update_last_seen();

		$this->load->vars($global_data);
	}

}

class Home_Core_Controller extends Core_Controller
{
	public function __construct()
	{
		parent::__construct();

		//maintenance mode
		if ($this->general_settings->maintenance_mode_status == 1) {
			if (!is_admin()) {
				$this->maintenance_mode();
			}
		}

		if ($this->input->method() == "post") {
			//set post language
			$lang_folder = $this->input->post('lang_folder', true);
			if (!empty($lang_folder)) {
				$this->config->set_item('language', $lang_folder);
				$this->lang->load("site_lang", $lang_folder);
			}
			//set post language base url
			$form_lang_base_url = $this->input->post('form_lang_base_url', true);
			if (!empty($form_lang_base_url)) {
				$global_data['lang_base_url'] = $form_lang_base_url;
				$this->lang_base_url = $form_lang_base_url;
			}
		}

		$global_data['parent_categories'] = $this->category_model->get_parent_categories();
		$global_data['footer_quick_links'] = $this->page_model->get_quick_links();
		$global_data['footer_information_links'] = $this->page_model->get_information_links();
		$global_data["countries"] = $this->location_model->get_countries();

		//recaptcha status
		$global_data['recaptcha_status'] = true;
		if (empty($this->general_settings->recaptcha_site_key) || empty($this->general_settings->recaptcha_secret_key)) {
			$global_data['recaptcha_status'] = false;
		}
		$this->recaptcha_status = $global_data['recaptcha_status'];

		if (auth_check()) {
			$global_data['unread_message_count'] = get_unread_conversations_count($this->auth_user->id);
		} else {
			$global_data['unread_message_count'] = 0;
		}

		//default location
		$global_data['default_location'] = "";
		$this->default_location_id = 0;
		if (isset($_SESSION["modesy_default_location"])) {
			$this->default_location_id = $_SESSION["modesy_default_location"];
			$location_country = $this->location_model->get_country($_SESSION["modesy_default_location"]);
			if (!empty($location_country)) {
				$global_data['default_location'] = $location_country->name;
			}
		} else {
			$global_data['default_location'] = trans("all");
		}

		//only one location
		if ($this->general_settings->default_product_location != 0) {
			$location_country = $this->location_model->get_country($this->general_settings->default_product_location);
			if (!empty($location_country)) {
				$global_data['default_location'] = $location_country->name;
			}
		}

		$global_data["countries"] = $this->location_model->get_countries();


		$this->load->vars($global_data);
	}

	//maintenance mode
	public function maintenance_mode()
	{
		$this->load->view('maintenance');
	}

	//verify recaptcha
	public function recaptcha_verify_request()
	{
		if (!$this->recaptcha_status) {
			return true;
		}

		$this->load->library('recaptcha');
		$recaptcha = $this->input->post('g-recaptcha-response');
		if (!empty($recaptcha)) {
			$response = $this->recaptcha->verifyResponse($recaptcha);
			if (isset($response['success']) && $response['success'] === true) {
				return true;
			}
		}
		return false;
	}

	//initialize iyzico
	public function initialize_iyzico()
	{
		if ($this->payment_settings->iyzico_enabled == 1) {
			require_once(APPPATH . 'third_party/iyzipay/IyzipayBootstrap.php');
			IyzipayBootstrap::init();
			$options = new \Iyzipay\Options();
			$options->setApiKey($this->payment_settings->iyzico_api_key);
			$options->setSecretKey($this->payment_settings->iyzico_secret_key);
			if ($this->payment_settings->iyzico_mode == "sandbox") {
				$options->setBaseUrl("https://sandbox-api.iyzipay.com");
			} else {
				$options->setBaseUrl("https://api.iyzipay.com");
			}
			return $options;
		}
	}

	public function paginate($url, $total_rows, $per_page)
	{
		//initialize pagination
		$page = $this->security->xss_clean($this->input->get('page'));
		$page = clean_number($page);
		if (empty($page)) {
			$page = 0;
		}

		if ($page != 0) {
			$page = $page - 1;
		}

		$config['num_links'] = 4;
		$config['base_url'] = $url;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = true;
		$this->pagination->initialize($config);

		$per_page = clean_number($per_page);

		return array('per_page' => $per_page, 'offset' => $page * $per_page, 'current_page' => $page + 1);
	}
}

class Admin_Core_Controller extends Core_Controller
{

	public function __construct()
	{
		parent::__construct();
	}

	public function paginate($url, $total_rows)
	{
		//initialize pagination
		$page = $this->security->xss_clean($this->input->get('page'));
		$per_page = $this->input->get('show', true);
		if (empty($page)) {
			$page = 0;
		}

		if ($page != 0) {
			$page = $page - 1;
		}

		if (empty($per_page)) {
			$per_page = 15;
		}
		$config['num_links'] = 4;
		$config['base_url'] = $url;
		$config['total_rows'] = $total_rows;
		$config['per_page'] = $per_page;
		$config['reuse_query_string'] = true;
		$this->pagination->initialize($config);

		return array('per_page' => $per_page, 'offset' => $page * $per_page);
	}
}


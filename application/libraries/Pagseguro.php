<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * PagSeguro PHP library
 *
 **/
class Pagseguro
{

	/**
	 * Privates
	 */
	private $ci;
	private $pagseguro_email = '';
	private $pagseguro_token = '';
	private $pagseguro_api_url = 'https://ws.pagseguro.uol.com.br/v2';
	private $mode = 'production';

	/**
	 * Constructor
	 *
	 * @access public
	 * @param array
	 */
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->pagseguro_email = $this->ci->payment_settings->pagseguro_email;
		$this->pagseguro_token = $this->ci->payment_settings->pagseguro_token;
		$this->mode = $this->ci->payment_settings->pagseguro_mode;
		if ($this->mode == 'sandbox') {
			$this->pagseguro_api_url = 'https://ws.sandbox.pagseguro.uol.com.br/v2';
		}
	}

	/**
	 * Pay with Credit Card
	 *
	 * @access public
	 */
	public function pay_with_credit_card($inputs)
	{
		$params = array(
			'email' => $this->pagseguro_email,
			'token' => $this->pagseguro_token,
			'creditCardToken' => $inputs['token'],
			'senderHash' => $inputs['senderHash'],
			'receiverEmail' => $this->pagseguro_email,
			'paymentMode' => 'default',
			'paymentMethod' => 'creditCard',
			'currency' => 'BRL',
			'itemId1' => uniqid(),
			'itemDescription1' => $this->ci->general_settings->application_name . " " . trans("payment"),
			'itemAmount1' => $inputs['total_amount'],
			'itemQuantity1' => 1,
			'reference' => uniqid(),
			'senderName' => $inputs['full_name'],
			'senderCPF' => $inputs['cpf'],
			'senderAreaCode' => $this->explode_area_code($inputs['phone']),
			'senderPhone' => $this->explode_phone_number($inputs['phone']),
			'senderEmail' => $inputs['email'],
			'shippingAddressRequired' => false,
			'installmentQuantity' => 1,
			'noInterestInstallmentQuantity' => 3,
			'installmentValue' => $inputs['total_amount'],
			'creditCardHolderName' => $inputs['full_name'],
			'creditCardHolderCPF' => $inputs['cpf'],
			'creditCardHolderBirthDate' => $inputs['date_of_birth'],
			'creditCardHolderAreaCode' => $this->explode_area_code($inputs['phone']),
			'creditCardHolderPhone' => $this->explode_phone_number($inputs['phone']),
			'billingAddressStreet' => '-',
			'billingAddressNumber' => '1234',
			'billingAddressDistrict' => "-",
			'billingAddressPostalCode' => $inputs['postal_code'],
			'billingAddressCity' => $inputs['city'],
			'billingAddressState' => "SP",
			'billingAddressCountry' => 'BRA'
		);

		$header = array('Content-Type' => 'application/json; charset=UTF-8;');
		$response = $this->curlExec($this->pagseguro_api_url . "/transactions", $params, $header);
		$json = json_decode(json_encode(simplexml_load_string($response)));
		if (!empty($json->status)) {
			return $json;
		} else {
			$error = '';
			if (is_array($json->error)) {
				$error = @$json->error[0]->message;
			} else {
				$error = @$json->error->message;
			}
			if ($error == 'credit card token is required.') {
				$error = 'Invalid card informations.';
			}
			if ($error == '') {
				$error = trans("msg_error");
			}
			$this->ci->session->set_flashdata('error_credit_card', $error);
			return false;
		}
	}

	/**
	 * Pay with Boleto
	 *
	 * @access public
	 */
	public function pay_with_boleto($inputs)
	{
		$params = array(
			'email' => $this->pagseguro_email,
			'token' => $this->pagseguro_token,
			'senderHash' => $inputs['senderHash'],
			'receiverEmail' => $this->pagseguro_email,
			'paymentMode' => 'default',
			'paymentMethod' => 'boleto',
			'currency' => 'BRL',
			'itemId1' => uniqid(),
			'itemDescription1' => $this->ci->general_settings->application_name . " " . trans("payment"),
			'itemAmount1' => $inputs['total_amount'],
			'itemQuantity1' => 1,
			'reference' => uniqid(),
			'senderName' => $inputs['full_name'],
			'senderCPF' => $inputs['cpf'],
			'senderAreaCode' => $this->explode_area_code($inputs['phone']),
			'senderPhone' => $this->explode_phone_number($inputs['phone']),
			'senderEmail' => $inputs['email'],
			'shippingAddressRequired' => false
		);

		$header = array('Content-Type' => 'application/json; charset=UTF-8;');
		$response = $this->curlExec($this->pagseguro_api_url . "/transactions", $params, $header);
		$json = json_decode(json_encode(simplexml_load_string($response)));
		if (!empty($json->status)) {
			return $json;
		} else {
			$error = '';
			if (is_array($json->error)) {
				$error = @$json->error[0]->message;
			} else {
				$error = @$json->error->message;
			}
			if ($error == '') {
				$error = trans("msg_error");
			}
			$this->ci->session->set_flashdata('error_boleto', $error);
			return false;
		}
	}

	/**
	 * Generate Session Code
	 *
	 * @access public
	 */
	public function get_session_code()
	{
		$session_code = null;
		$params = array(
			'email' => $this->pagseguro_email,
			'token' => $this->pagseguro_token
		);
		$header = array();

		$response = $this->curlExec($this->pagseguro_api_url . "/sessions", $params, $header);
		if (!empty($response)) {
			$json = @json_decode(json_encode(simplexml_load_string($response)));
			if (!empty($json)) {
				$session_code = $json->id;
			} else {
				$this->ci->session->set_flashdata('error', "Invalid email or token!");
			}
		}

		return $session_code;
	}

	/**
	 * Explode Area Code from Input
	 *
	 * @access public
	 */
	public function explode_area_code($input)
	{
		$phone_array = explode('-', $input);
		$area_code = trim($phone_array[0]);
		$area_code = str_replace('(', '', $area_code);
		$area_code = str_replace(')', '', $area_code);
		return $area_code;
	}

	/**
	 * Explode Phone from Input
	 *
	 * @access public
	 */
	public function explode_phone_number($input)
	{
		$phone_array = explode('-', $input);
		return trim($phone_array[1]);
	}

	/**
	 * cURL Request Function
	 *
	 * @access public
	 */
	public function curlExec($url, $post = NULL, array $header = array())
	{
		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		if (count($header) > 0) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		}
		if ($post !== null) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post, '', '&'));
		}
		//Ignore SSL
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
	}
}

/* End of file pagseguro.php */
/* Location: ./application/libraries/pagseguro.php */

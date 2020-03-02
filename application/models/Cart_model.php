<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model
{
	//add to cart
	public function add_to_cart($product)
	{
		if (!$this->check_item_quantity($product)) {
			return false;
		}
		$cart = $this->get_sess_cart_items();
		$quantity = $this->input->post('product_quantity', true);
		$appended_variations = $this->append_selected_variations($product->id);

		$item = new stdClass();
		$item->cart_item_id = generate_unique_id();
		$item->product_id = $product->id;
		$item->product_type = $product->product_type;
		$item->product_title = $product->title . " " . $appended_variations;
		$item->quantity = $quantity;
		$item->unit_price = $product->price;
		$item->total_price = $product->price * $quantity;
		$item->currency = $product->currency;
		$item->shipping_cost = $product->shipping_cost;
		$item->is_avaible = check_product_available_for_sale($product);
		$item->purchase_type = 'product';
		$item->quote_request_id = 0;
		array_push($cart, $item);

		$this->session->set_userdata('mds_shopping_cart', $cart);
	}

	//add to cart quote
	public function add_to_cart_quote($quote_request_id)
	{
		$this->load->model('bidding_model');
		$quote_request = $this->bidding_model->get_quote_request($quote_request_id);

		if (!empty($quote_request)) {
			$product = $this->product_model->get_product_by_id($quote_request->product_id);
			if (!empty($product)) {
				$cart = $this->get_sess_cart_items();
				$item = new stdClass();
				$item->cart_item_id = generate_unique_id();
				$item->product_id = $product->id;
				$item->product_type = $product->product_type;
				$item->product_title = $quote_request->product_title;
				$item->quantity = $quote_request->product_quantity;
				$item->unit_price = $quote_request->price_offered / $quote_request->product_quantity;
				$item->total_price = $quote_request->price_offered;
				$item->currency = $quote_request->price_currency;
				$item->shipping_cost = $quote_request->shipping_cost;
				$item->is_avaible = true;
				$item->purchase_type = 'bidding';
				$item->quote_request_id = $quote_request->id;
				array_push($cart, $item);

				$this->session->set_userdata('mds_shopping_cart', $cart);
				return true;
			}
		}
		return false;
	}

	//remove from cart
	public function remove_from_cart($cart_item_id)
	{
		$cart = $this->cart_model->get_sess_cart_items();
		if (!empty($cart)) {
			$new_cart = array();
			foreach ($cart as $item) {
				if ($item->cart_item_id != $cart_item_id) {
					array_push($new_cart, $item);
				}
			}
			$this->session->set_userdata('mds_shopping_cart', $new_cart);
		}
	}

	//append selected variations
	public function append_selected_variations($product_id)
	{
		$variations = $this->variation_model->get_product_variations_by_lang($product_id, $this->selected_lang->id);
		$str = "";
		if (!empty($variations)) {
			foreach ($variations as $variation) {
				$append_text = "";
				if (!empty($variation)) {
					$variation_val = $this->input->post('variation' . $variation->id, true);
					if (!empty($variation_val)) {
						//check multiselect
						if (is_array($variation_val)) {
							$i = 0;
							foreach ($variation_val as $item) {
								if ($i == 0) {
									$append_text .= $item;
								} else {
									$append_text .= " - " . $item;
								}
								$i++;
							}
						} else {
							$append_text = $variation_val;
						}

						if (empty($str)) {
							$str .= "(" . $variation->label . ": " . $append_text;
						} else {
							$str .= ", " . $variation->label . ": " . $append_text;
						}
					}
				}
			}
			if (!empty($str)) {
				$str = $str . ")";
			}
		}
		return $str;
	}
	//clear cart
	public function clear_cart()
	{
		$this->unset_sess_cart_items();
		$this->unset_sess_cart_payment_method();
		$this->unset_sess_cart_shipping_address();
	}

	//update cart product quantity
	public function update_cart_product_quantity($product_id, $cart_item_id, $quantity)
	{
		$product = $this->product_model->get_product_by_id($product_id);
		if (!empty($product)) {
			$cart = $this->get_sess_cart_items();
			if (!empty($cart)) {
				foreach ($cart as $item) {
					if ($item->cart_item_id == $cart_item_id) {
						$item->quantity = $quantity;
						$item->unit_price = $product->price;
						$item->total_price = $product->price * $quantity;
					}
				}
			}
			$this->session->set_userdata('mds_shopping_cart', $cart);
		}
	}

	//calculate cart total
	public function calculate_cart_total()
	{
		$cart = $this->get_sess_cart_items();
		$cart_total = new stdClass();
		$cart_total->subtotal = 0;
		$cart_total->shipping_cost = 0;
		$cart_total->total = 0;
		$cart_total->currency = $this->payment_settings->default_product_currency;

		if (!empty($cart)) {
			foreach ($cart as $item) {
				$product = $this->product_model->get_product_by_id($item->product_id);
				if ($item->purchase_type == 'bidding') {
					$this->load->model('bidding_model');
					$quote_request = $this->bidding_model->get_quote_request($item->quote_request_id);
					if (!empty($quote_request)) {
						$cart_total->subtotal += $quote_request->price_offered;
						$cart_total->shipping_cost += $quote_request->shipping_cost;
						if ($this->form_settings->shipping != 1) {
							$cart_total->shipping_cost = 0;
						}
					}
				} else {
					$cart_total->subtotal += $product->price * $item->quantity;
					$cart_total->shipping_cost += $product->shipping_cost;
					if ($this->form_settings->shipping != 1) {
						$cart_total->shipping_cost = 0;
					}
				}
			}
		}
		$cart_total->total = $cart_total->subtotal + $cart_total->shipping_cost;
		$this->session->set_userdata('mds_shopping_cart_total', $cart_total);
	}

	//set cart shipping address session
	public function set_sess_cart_shipping_address()
	{
		$std = new stdClass();
		$std->shipping_first_name = $this->input->post('shipping_first_name', true);
		$std->shipping_last_name = $this->input->post('shipping_last_name', true);
		$std->shipping_email = $this->input->post('shipping_email', true);
		$std->shipping_phone_number = $this->input->post('shipping_phone_number', true);
		$std->shipping_address_1 = $this->input->post('shipping_address_1', true);
		$std->shipping_address_2 = $this->input->post('shipping_address_2', true);
		$std->shipping_country_id = $this->input->post('shipping_country_id', true);
		$std->shipping_state = $this->input->post('shipping_state', true);
		$std->shipping_city = $this->input->post('shipping_city', true);
		$std->shipping_zip_code = $this->input->post('shipping_zip_code', true);
		$std->billing_first_name = $this->input->post('billing_first_name', true);
		$std->billing_last_name = $this->input->post('billing_last_name', true);
		$std->billing_email = $this->input->post('billing_email', true);
		$std->billing_phone_number = $this->input->post('billing_phone_number', true);
		$std->billing_address_1 = $this->input->post('billing_address_1', true);
		$std->billing_address_2 = $this->input->post('billing_address_2', true);
		$std->billing_country_id = $this->input->post('billing_country_id', true);
		$std->billing_state = $this->input->post('billing_state', true);
		$std->billing_city = $this->input->post('billing_city', true);
		$std->billing_zip_code = $this->input->post('billing_zip_code', true);
		$std->use_same_address_for_billing = $this->input->post('use_same_address_for_billing', true);
		if (!isset($std->use_same_address_for_billing)) {
			$std->use_same_address_for_billing = 0;
		}

		if ($std->use_same_address_for_billing == 1) {
			$std->billing_first_name = $std->shipping_first_name;
			$std->billing_last_name = $std->shipping_last_name;
			$std->billing_email = $std->shipping_email;
			$std->billing_phone_number = $std->shipping_phone_number;
			$std->billing_address_1 = $std->shipping_address_1;
			$std->billing_address_2 = $std->shipping_address_2;
			$std->billing_country_id = $std->shipping_country_id;
			$std->billing_state = $std->shipping_state;
			$std->billing_city = $std->shipping_city;
			$std->billing_zip_code = $std->shipping_zip_code;
		} else {
			if (empty($std->billing_first_name)) {
				$std->billing_first_name = $std->shipping_first_name;
			}
			if (empty($std->billing_last_name)) {
				$std->billing_last_name = $std->shipping_last_name;
			}
			if (empty($std->billing_email)) {
				$std->billing_email = $std->shipping_email;
			}
			if (empty($std->billing_phone_number)) {
				$std->billing_phone_number = $std->shipping_phone_number;
			}
			if (empty($std->billing_address_1)) {
				$std->billing_address_1 = $std->shipping_address_1;
			}
			if (empty($std->billing_address_2)) {
				$std->billing_address_2 = $std->shipping_address_2;
			}
			if (empty($std->billing_country_id)) {
				$std->billing_country_id = $std->shipping_country_id;
			}
			if (empty($std->billing_state)) {
				$std->billing_state = $std->shipping_state;
			}
			if (empty($std->billing_city)) {
				$std->billing_city = $std->shipping_state;
			}
			if (empty($std->billing_zip_code)) {
				$std->billing_zip_code = $std->shipping_zip_code;
			}
		}
		$this->session->set_userdata('mds_cart_shipping_address', $std);
	}

	//get cart shipping address session
	public function get_sess_cart_shipping_address()
	{
		if (!empty($this->session->userdata('mds_cart_shipping_address'))) {
			return $this->session->userdata('mds_cart_shipping_address');
		}
		$std = new stdClass();
		$row = null;

		if (auth_check()) {
			$row = $this->profile_model->get_user_shipping_address(user()->id);
		} else {
			$row = $this->profile_model->get_user_shipping_address(null);
		}
		$std->shipping_first_name = $row->shipping_first_name;
		$std->shipping_last_name = $row->shipping_last_name;
		$std->shipping_email = $row->shipping_email;
		$std->shipping_phone_number = $row->shipping_phone_number;
		$std->shipping_address_1 = $row->shipping_address_1;
		$std->shipping_address_2 = $row->shipping_address_2;
		$std->shipping_country_id = $row->shipping_country_id;
		$std->shipping_state = $row->shipping_state;
		$std->shipping_city = $row->shipping_city;
		$std->shipping_zip_code = $row->shipping_zip_code;
		$std->billing_first_name = $row->shipping_first_name;
		$std->billing_last_name = $row->shipping_last_name;
		$std->billing_email = $row->shipping_email;
		$std->billing_phone_number = $row->shipping_phone_number;
		$std->billing_address_1 = $row->shipping_address_1;
		$std->billing_address_2 = $row->shipping_address_2;
		$std->billing_country_id = $row->shipping_country_id;
		$std->billing_state = $row->shipping_state;
		$std->billing_city = $row->shipping_city;
		$std->billing_zip_code = $row->shipping_zip_code;
		$std->use_same_address_for_billing = 1;
		$this->session->unset_userdata('mds_cart_shipping_address');
		return $std;
	}

	//get cart items session
	public function get_sess_cart_items()
	{
		$cart = array();
		$new_cart = array();
		if (!empty($this->session->userdata('mds_shopping_cart'))) {
			$cart = $this->session->userdata('mds_shopping_cart');
		}

		foreach ($cart as $cart_item) {
			$product = $this->product_model->get_available_product($cart_item->product_id);
			if (!empty($product)) {
				if (check_product_available_for_sale($product)) {
					//if purchase type is bidding
					if ($cart_item->purchase_type == 'bidding') {
						$this->load->model('bidding_model');
						$quote_request = $this->bidding_model->get_quote_request($cart_item->quote_request_id);
						if (!empty($quote_request) && $quote_request->status == 'pending_payment') {
							$item = new stdClass();
							$item->cart_item_id = $cart_item->cart_item_id;
							$item->product_id = $product->id;
							$item->product_type = $cart_item->product_type;
							$item->product_title = $cart_item->product_title;
							$item->quantity = $cart_item->quantity;
							$item->unit_price = $quote_request->price_offered / $quote_request->product_quantity;
							$item->total_price = $quote_request->price_offered;
							$item->currency = $product->currency;
							$item->shipping_cost = $quote_request->shipping_cost;
							$item->purchase_type = $cart_item->purchase_type;
							$item->quote_request_id = $cart_item->quote_request_id;
							$item->is_quantity_available = true;
							if ($this->form_settings->shipping != 1) {
								$item->shipping_cost = 0;
							}
							array_push($new_cart, $item);
						}
					} else {
						$item = new stdClass();
						$item->cart_item_id = $cart_item->cart_item_id;
						$item->product_id = $product->id;
						$item->product_type = $cart_item->product_type;
						$item->product_title = $cart_item->product_title;
						$item->quantity = $cart_item->quantity;
						$item->unit_price = $product->price;
						$item->total_price = $product->price * $cart_item->quantity;
						$item->currency = $product->currency;
						$item->shipping_cost = $product->shipping_cost;
						$item->purchase_type = $cart_item->purchase_type;
						$item->quote_request_id = $cart_item->quote_request_id;
						$item->is_quantity_available = $this->is_quantity_available($product);
						if ($this->form_settings->shipping != 1) {
							$item->shipping_cost = 0;
						}
						array_push($new_cart, $item);
					}
				}
			}
		}
		$this->session->set_userdata('mds_shopping_cart', $new_cart);
		return $new_cart;
	}

	//check cart has physical products
	public function check_cart_has_physical_product()
	{
		$cart_items = $this->get_sess_cart_items();
		if (!empty($cart_items)) {
			foreach ($cart_items as $cart_item) {
				if ($cart_item->product_type == 'physical') {
					return true;
				}
			}
		}
		return false;
	}

	//check cart has digital products
	public function check_cart_has_digital_product()
	{
		$cart_items = $this->get_sess_cart_items();
		if (!empty($cart_items)) {
			foreach ($cart_items as $cart_item) {
				if ($cart_item->product_type == 'digital') {
					return true;
				}
			}
		}
		return false;
	}

	//check item quantity
	public function check_item_quantity($product, $allow_equal = false)
	{
		$quantity = 0;
		$cart_items = $this->session->userdata('mds_shopping_cart');
		if (!empty($cart_items)) {
			foreach ($cart_items as $cart_item) {
				if ($cart_item->product_id == $product->id) {
					$quantity += $cart_item->quantity;
				}
			}
		}
		if ($allow_equal == true) {
			if ($product->quantity >= $quantity) {
				return true;
			}
		} else {
			if ($product->quantity > $quantity) {
				return true;
			}
		}
		return false;
	}

	//is quantity available
	public function is_quantity_available($product)
	{
		$quantity = 0;
		$cart_items = $this->session->userdata('mds_shopping_cart');
		if (!empty($cart_items)) {
			foreach ($cart_items as $cart_item) {
				if ($cart_item->product_id == $product->id) {
					$quantity += $cart_item->quantity;
				}
			}
		}
		if ($product->quantity >= $quantity) {
			return true;
		}
		return false;
	}

	//unset cart items session
	public function unset_sess_cart_items()
	{
		if (!empty($this->session->userdata('mds_shopping_cart'))) {
			$this->session->unset_userdata('mds_shopping_cart');
		}
	}

	//get cart total session
	public function get_sess_cart_total()
	{
		$cart_total = new stdClass();
		if (!empty($this->session->userdata('mds_shopping_cart_total'))) {
			$cart_total = $this->session->userdata('mds_shopping_cart_total');
		}
		return $cart_total;
	}

	//set cart payment method option session
	public function set_sess_cart_payment_method()
	{
		$std = new stdClass();
		$std->payment_option = $this->input->post('payment_option', true);
		$std->terms_conditions = $this->input->post('terms_conditions', true);
		$this->session->set_userdata('mds_cart_payment_method', $std);
	}

	//get cart payment method option session
	public function get_sess_cart_payment_method()
	{
		if (!empty($this->session->userdata('mds_cart_payment_method'))) {
			return $this->session->userdata('mds_cart_payment_method');
		}
	}

	//unset cart payment method option session
	public function unset_sess_cart_payment_method()
	{
		if (!empty($this->session->userdata('mds_cart_payment_method'))) {
			$this->session->unset_userdata('mds_cart_payment_method');
		}
	}

	//unset cart shipping address session
	public function unset_sess_cart_shipping_address()
	{
		if (!empty($this->session->userdata('mds_cart_shipping_address'))) {
			$this->session->unset_userdata('mds_cart_shipping_address');
		}
	}
}

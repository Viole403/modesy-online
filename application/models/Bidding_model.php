<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bidding_model extends CI_Model
{
	//request quote
	public function request_quote($product)
	{
		$appended_variations = $this->cart_model->append_selected_variations($product->id);
		$data = array(
			'product_id' => $product->id,
			'product_title' => $product->title . " " . $appended_variations,
			'product_quantity' => $this->input->post('product_quantity', true),
			'seller_id' => $product->user_id,
			'buyer_id' => user()->id,
			'status' => 'new_quote_request',
			'price_offered' => 0,
			'price_currency' => '',
			'shipping_cost' => 0,
			'updated_at' => date('Y-m-d H:i:s'),
			'created_at' => date('Y-m-d H:i:s')
		);
		if ($this->db->insert('quote_requests', $data)) {
			return $this->db->insert_id();
		}
		return false;
	}

	//submit quote
	public function submit_quote($quote_request)
	{
		if (!empty($quote_request) && $this->auth_user->id == $quote_request->seller_id) {
			$data = array(
				'price_offered' => $this->input->post('price', true),
				'price_currency' => $this->input->post('currency', true),
				'shipping_cost' => $this->input->post('shipping_cost', true),
				'status' => "pending_quote",
				'updated_at' => date('Y-m-d H:i:s')
			);
			$data["price_offered"] = price_database_format($data["price_offered"]);
			if (empty($data["price_offered"])) {
				$data["price_offered"] = 0;
			}
			$data["shipping_cost"] = price_database_format($data["shipping_cost"]);
			if (empty($data["shipping_cost"])) {
				$data["shipping_cost"] = 0;
			}
			$this->db->where('id', $quote_request->id);
			return $this->db->update('quote_requests', $data);
		}
		return false;
	}

	//accept quote
	public function accept_quote($quote_request)
	{
		if (!empty($quote_request) && $this->auth_user->id == $quote_request->buyer_id) {
			$data = array(
				'status' => "pending_payment",
				'updated_at' => date('Y-m-d H:i:s')
			);
			$this->db->where('id', $quote_request->id);
			return $this->db->update('quote_requests', $data);
		}
	}

	//reject quote
	public function reject_quote($quote_request)
	{
		if (!empty($quote_request) && $this->auth_user->id == $quote_request->buyer_id) {
			$data = array(
				'status' => "rejected_quote",
				'updated_at' => date('Y-m-d H:i:s')
			);
			$this->db->where('id', $quote_request->id);
			return $this->db->update('quote_requests', $data);
		}
	}

	//get quote request
	public function get_quote_request($id)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('quote_requests');
		return $query->row();
	}

	//get sent quote requests count
	public function get_sent_quote_requests_count($user_id)
	{
		$user_id = clean_number($user_id);
		$this->db->where('buyer_id', $user_id);
		$this->db->where('is_buyer_deleted', 0);
		$query = $this->db->get('quote_requests');
		return $query->num_rows();
	}

	//get sent quote requests
	public function get_sent_quote_requests_paginated($user_id, $per_page, $offset)
	{
		$user_id = clean_number($user_id);
		$this->db->where('buyer_id', $user_id);
		$this->db->where('is_buyer_deleted', 0);
		$this->db->order_by('updated_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('quote_requests');
		return $query->result();
	}

	//get received quote requests count
	public function get_received_quote_requests_count($user_id)
	{
		$user_id = clean_number($user_id);
		$this->db->where('seller_id', $user_id);
		$this->db->where('is_seller_deleted', 0);
		$query = $this->db->get('quote_requests');
		return $query->num_rows();
	}

	//get received quote requests
	public function get_received_quote_requests_paginated($user_id, $per_page, $offset)
	{
		$user_id = clean_number($user_id);
		$this->db->where('seller_id', $user_id);
		$this->db->where('is_seller_deleted', 0);
		$this->db->order_by('updated_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('quote_requests');
		return $query->result();
	}

	//delete quote request
	public function delete_quote_request($id)
	{
		$id = clean_number($id);
		$quote_request = $this->get_quote_request($id);
		if (!empty($quote_request)) {
			if ($this->auth_user->id == $quote_request->seller_id || $this->auth_user->id == $quote_request->buyer_id) {
				if ($this->auth_user->id == $quote_request->buyer_id) {
					$data = array(
						'is_buyer_deleted' => 1,
						'status' => 'closed',
						'updated_at' => date('Y-m-d H:i:s')
					);
					if ($quote_request->status == 'completed') {
						$data['status'] = 'completed';
					}
					$this->db->where('id', $id);
					return $this->db->update('quote_requests', $data);
				} elseif ($this->auth_user->id == $quote_request->seller_id) {
					$data = array(
						'is_seller_deleted' => 1,
						'status' => 'closed',
						'updated_at' => date('Y-m-d H:i:s')
					);
					if ($quote_request->status == 'completed') {
						$data['status'] = 'completed';
					}
					$this->db->where('id', $id);
					return $this->db->update('quote_requests', $data);
				}
			}
		}
		return false;
	}

	//delete quote if both deleted
	public function delete_quote_request_if_both_deleted($id)
	{
		$id = clean_number($id);
		$quote_request = $this->get_quote_request($id);
		if (!empty($quote_request)) {
			if ($this->auth_user->id == $quote_request->seller_id || $this->auth_user->id == $quote_request->buyer_id) {
				if ($quote_request->is_buyer_deleted == 1 && $quote_request->is_seller_deleted == 1) {
					$this->db->where('id', $id);
					return $this->db->delete('quote_requests');
				}
			}
		}
		return false;
	}

	//set bidding quotes as completed after purchase
	public function set_bidding_quotes_as_completed_after_purchase()
	{
		$cart_items = $this->cart_model->get_sess_cart_items();
		if (!empty($cart_items)) {
			foreach ($cart_items as $cart_item) {
				if ($cart_item->purchase_type == 'bidding') {
					$data = array(
						'status' => 'completed',
						'updated_at' => date('Y-m-d H:i:s')
					);
					$this->db->where('id', $cart_item->quote_request_id);
					@$this->db->update('quote_requests', $data);
				}
			}
		}
	}

	//get admin quote requests count
	public function get_admin_quote_requests_count()
	{
		$this->filter_quote_requests();
		$query = $this->db->get('quote_requests');
		return $query->num_rows();
	}

	//get admin quote requests
	public function get_admin_paginated_quote_requests($per_page, $offset)
	{
		$this->filter_quote_requests();
		$this->db->order_by('created_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('quote_requests');
		return $query->result();
	}

	//filter quote requests
	public function filter_quote_requests()
	{
		$data = array(
			'q' => $this->input->get('q', true)
		);
		$data['q'] = clean_number($data['q']);
		if (!empty($data['q'])) {
			$this->db->where('id', $data['q']);
		}
	}

	//delete admin quote request
	public function delete_admin_quote_request($id)
	{
		if (is_admin()) {
			$id = clean_number($id);
			$quote_request = $this->get_quote_request($id);
			if (!empty($quote_request)) {
				$this->db->where('id', $id);
				return $this->db->delete('quote_requests');
			}
		}
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product_admin_model extends CI_Model
{
	//get products
	public function get_products()
	{
		$this->db->where('status', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$query = $this->db->get('products');
		return $query->result();
	}

	//get latest products
	public function get_latest_products($limit)
	{
		$limit = clean_number($limit);
		$this->db->where('status', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get products count
	public function get_products_count()
	{
		$this->db->where('status', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get pending products
	public function get_pending_products()
	{
		$this->db->where('status !=', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$query = $this->db->get('products');
		return $query->result();
	}

	//get latest pending products
	public function get_latest_pending_products($limit)
	{
		$limit = clean_number($limit);
		$this->db->where('status !=', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get pending products count
	public function get_pending_products_count()
	{
		$this->db->where('status !=', 1);
		$this->db->where('products.is_draft', 0);
		$this->db->where('products.is_deleted', 0);
		$this->db->order_by('products.created_at', 'DESC');
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//filter by values
	public function filter_products()
	{
		$data = array(
			'category' => $this->input->get('category', true),
			'subcategory' => $this->input->get('subcategory', true),
			'q' => $this->input->get('q', true),
		);

		$data['q'] = trim($data['q']);
		$category_id = $data['category'];
		if (!empty($data['subcategory'])) {
			$category_id = $data['subcategory'];
		}
		if (!empty($category_id)) {
			$category_tree_ids = $this->category_model->get_category_tree_ids_string($category_id);
			if (!empty($category_tree_ids)) {
				$this->db->where("products.category_id IN (" . $category_tree_ids . ")", NULL, FALSE);
			}
		}
		if (!empty($data['type']) && $data['type'] == "regular") {
			$this->db->where('products.is_promoted', 0);
		}
		if (!empty($data['q'])) {
			$this->db->like('products.title', $data['q']);
		}
	}

	//filter by list
	public function filter_products_list($list)
	{
		if (!empty($list)) {
			if ($list == "products") {
				$this->db->where('products.visibility', 1);
				$this->db->where('products.is_draft', 0);
				$this->db->where('products.is_deleted', 0);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "promoted_products") {
				$this->db->where('products.visibility', 1);
				$this->db->where('products.is_promoted', 1);
				$this->db->where('products.is_draft', 0);
				$this->db->where('products.is_deleted', 0);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "pending_products") {
				$this->db->where('products.visibility', 1);
				$this->db->where('products.is_draft', 0);
				$this->db->where('products.is_deleted', 0);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "hidden_products") {
				$this->db->where('products.visibility', 0);
				$this->db->where('products.is_draft', 0);
				$this->db->where('products.is_deleted', 0);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "sold_products") {
				$this->db->where('products.is_draft', 0);
				$this->db->where('products.is_sold', 1);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "drafts") {
				$this->db->where('products.is_draft', 1);
				$this->db->where('products.is_deleted', 0);
				$this->db->order_by('products.created_at', 'DESC');
			}
			if ($list == "deleted_products") {
				$this->db->where('products.is_deleted', 1);
				$this->db->order_by('products.created_at', 'DESC');
			}
		}
	}

	//get paginated products count
	public function get_paginated_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status', 1);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated products
	public function get_paginated_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status', 1);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated promoted products count
	public function get_paginated_promoted_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status', 1);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated promoted products
	public function get_paginated_promoted_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status', 1);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated pending products count
	public function get_paginated_pending_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status !=', 1);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated pending products
	public function get_paginated_pending_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->where('products.status !=', 1);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated drafts count
	public function get_paginated_drafts_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated drafts
	public function get_paginated_drafts($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated hidden product count
	public function get_paginated_hidden_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated hidden products
	public function get_paginated_hidden_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated deleted product count
	public function get_paginated_deleted_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated deleted products
	public function get_paginated_deleted_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get paginated sold product count
	public function get_sold_products_count($list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$query = $this->db->get('products');
		return $query->num_rows();
	}

	//get paginated sold products
	public function get_paginated_sold_products($per_page, $offset, $list)
	{
		$this->filter_products();
		$this->filter_products_list($list);
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('products');
		return $query->result();
	}

	//get product
	public function get_product($id)
	{
		$id = clean_number($id);
		$this->db->where('products.id', $id);
		$query = $this->db->get('products');
		return $query->row();
	}

	//approve product
	public function approve_product($id)
	{
		$id = clean_number($id);
		$product = $this->get_product($id);
		if (!empty($product)) {
			$data = array(
				'status' => 1,
			);
			$this->db->where('id', $id);
			return $this->db->update('products', $data);
		}

		return false;
	}

	//add remove promoted products
	public function add_remove_promoted_products($product_id, $day_count)
	{
		$product_id = clean_number($product_id);
		$day_count = clean_number($day_count);
		$product = $this->get_product($product_id);
		if (!empty($product)) {
			if ($product->is_promoted == 1) {
				$data = array(
					'is_promoted' => 0,
				);
			} else {
				$date = date('Y-m-d H:i:s');
				$end_date = date('Y-m-d H:i:s', strtotime($date . ' + ' . $day_count . ' days'));
				$data = array(
					'is_promoted' => 1,
					'promote_start_date' => $date,
					'promote_end_date' => $end_date
				);
			}
			$this->db->where('id', $product_id);
			return $this->db->update('products', $data);
		}

		return false;
	}

	//delete product
	public function delete_product($product_id)
	{
		$product_id = clean_number($product_id);
		$product = $this->get_product($product_id);
		if (!empty($product)) {
			$data = array(
				'is_deleted' => 1
			);
			$this->db->where('id', $product_id);
			return $this->db->update('products', $data);
		}
		return false;
	}

	//delete product permanently
	public function delete_product_permanently($id)
	{
		$id = clean_number($id);
		$product = $this->get_product($id);
		if (!empty($product)) {
			//delete images
			$this->file_model->delete_product_images($id);
			$this->db->where('id', $id);
			return $this->db->delete('products');
		}
		return false;
	}

	//delete multi product
	public function delete_multi_products($product_ids)
	{
		if (!empty($product_ids)) {
			foreach ($product_ids as $id) {
				$this->delete_product($id);
			}
		}
	}


	//delete multi product
	public function delete_multi_products_permanently($product_ids)
	{
		if (!empty($product_ids)) {
			foreach ($product_ids as $id) {
				$product = $this->get_product($id);

				if (!empty($product)) {
					//delete images
					$this->file_model->delete_product_images($id);

					$this->db->where('id', $id);
					$this->db->delete('products');
				}
			}
		}
	}

	//restore product
	public function restore_product($product_id)
	{
		$product_id = clean_number($product_id);
		$product = $this->get_product($product_id);
		if (!empty($product)) {
			$data = array(
				'is_deleted' => 0
			);
			$this->db->where('id', $product_id);
			return $this->db->update('products', $data);
		}
		return false;
	}

}

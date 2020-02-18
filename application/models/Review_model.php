<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Review_model extends CI_Model
{
	//add review
	public function add_review()
	{
		$data = array(
			'product_id' => $this->input->post('product_id', true),
			'user_id' => user()->id,
			'rating' => $this->input->post('rating', true),
			'review' => $this->input->post('review', true),
			'created_at' => date("Y-m-d H:i:s")
		);

		if (!empty($data['product_id']) && !empty($data['user_id']) && !empty($data['rating'])) {
			$this->db->insert('reviews', $data);
			//update product rating
			$this->update_product_rating($data['product_id']);
		}
	}

	//get review count
	public function get_review_count($product_id)
	{
		$product_id = clean_number($product_id);
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->where('reviews.product_id', $product_id);
		$query = $this->db->get('reviews');
		return $query->num_rows();
	}

	//get reviews
	public function get_reviews($product_id)
	{
		$product_id = clean_number($product_id);
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->select('reviews.*, users.username as user_username');
		$this->db->where('reviews.product_id', $product_id);
		$this->db->order_by('reviews.created_at', 'DESC');
		$query = $this->db->get('reviews');
		return $query->result();
	}

	//get all reviews
	public function get_all_reviews()
	{
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->select('reviews.*, users.username as user_username');
		$this->db->order_by('reviews.created_at', 'DESC');
		$query = $this->db->get('reviews');
		return $query->result();
	}

	//get latest reviews
	public function get_latest_reviews($limit)
	{
		$limit = clean_number($limit);
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->select('reviews.*, users.username as user_username');
		$this->db->order_by('reviews.created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get('reviews');
		return $query->result();
	}

	//get limited reviews
	public function get_limited_reviews($product_id, $limit)
	{
		$product_id = clean_number($product_id);
		$limit = clean_number($limit);
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->select('reviews.*, users.username as user_username, users.slug as user_slug');
		$this->db->where('reviews.product_id', $product_id);
		$this->db->order_by('reviews.created_at', 'DESC');
		$this->db->limit($limit);
		$query = $this->db->get('reviews');
		return $query->result();
	}

	//get review
	public function get_review($product_id, $user_id)
	{
		$product_id = clean_number($product_id);
		$user_id = clean_number($user_id);
		$this->db->join('users', 'users.id = reviews.user_id');
		$this->db->where('reviews.product_id', $product_id);
		$this->db->where('users.id', $user_id);
		$query = $this->db->get('reviews');
		return $query->row();
	}

	//update product rating
	public function update_product_rating($product_id)
	{
		$product_id = clean_number($product_id);
		$reviews = $this->get_reviews($product_id);
		$data = array();
		if (!empty($reviews)) {
			$count = count($reviews);
			$total = 0;
			foreach ($reviews as $review) {
				$total += $review->rating;
			}
			$data['rating'] = round($total / $count);
		} else {
			$data['rating'] = 0;
		}
		$this->db->where('id', $product_id);
		$this->db->update('products', $data);
	}

	//delete review
	public function delete_review($id, $product_id = null)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('reviews');
		$row = $query->row();
		if (empty($row)) {
			return false;
		}
		$product = get_product($row->product_id);
		if (empty($product)) {
			return false;
		}

		$this->db->where('id', $id);
		if ($this->db->delete('reviews')) {
			//update product rating
			$this->update_product_rating($product->id);
			return true;
		}
		return false;
	}

	//delete multi reviews
	public function delete_multi_reviews($review_ids)
	{
		if (!empty($review_ids)) {
			foreach ($review_ids as $id) {
				$this->delete_review($id);
			}
		}
	}

}

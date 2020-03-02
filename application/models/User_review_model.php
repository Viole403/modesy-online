<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_review_model extends CI_Model
{

    //add review
    public function add_review()
    {
        $data = array(
            'seller_id' => $this->input->post('seller_id', true),
            'user_id' => user()->id,
            'rating' => $this->input->post('rating', true),
            'review' => $this->input->post('review', true),
            'created_at' => date("Y-m-d H:i:s")
        );

        if (!empty($data['seller_id']) && !empty($data['user_id']) && !empty($data['rating'])) {
            $this->db->insert('user_reviews', $data);
        }
    }

    //get review
    public function get_review($id)
    {
        $id = clean_number($id);
        $this->db->where('id', $id);
        $query = $this->db->get('user_reviews');
        return $query->row();
    }

    //get review by user
    public function get_review_by_user($seller_id, $user_id)
    {
        $seller_id = clean_number($seller_id);
        $user_id = clean_number($user_id);
        $this->db->join('users', 'users.id = user_reviews.user_id');
        $this->db->where('user_reviews.seller_id', $seller_id);
        $this->db->where('users.id', $user_id);
        $query = $this->db->get('user_reviews');
        return $query->row();
    }


    //get limited reviews
    public function get_limited_reviews($seller_id, $limit)
    {
        $seller_id = clean_number($seller_id);
        $limit = clean_number($limit);
        $this->db->join('users', 'users.id = user_reviews.user_id');
        $this->db->select('user_reviews.*, users.username as user_username, users.slug as user_slug');
        $this->db->where('user_reviews.seller_id', $seller_id);
        $this->db->order_by('user_reviews.created_at', 'DESC');
        $this->db->limit($limit);
        $query = $this->db->get('user_reviews');
        return $query->result();
    }

    //get reviews
    public function get_reviews($seller_id)
    {
        $seller_id = clean_number($seller_id);
        $this->db->join('users', 'users.id = user_reviews.user_id');
        $this->db->select('user_reviews.*, users.username as user_username');
        $this->db->where('user_reviews.seller_id', $seller_id);
        $this->db->order_by('user_reviews.created_at', 'DESC');
        $query = $this->db->get('user_reviews');
        return $query->result();
    }

    //get review count
    public function get_review_count($seller_id)
    {
        $seller_id = clean_number($seller_id);
        $this->db->join('users', 'users.id = user_reviews.user_id');
        $this->db->where('user_reviews.seller_id', $seller_id);
        $query = $this->db->get('user_reviews');
        return $query->num_rows();
    }

    //get all reviews
    public function get_all_reviews()
    {
        $this->db->join('users', 'users.id = user_reviews.user_id');
        $this->db->select('user_reviews.*, users.username as user_username, users.slug as user_slug');
        $this->db->order_by('user_reviews.created_at', 'DESC');
        $query = $this->db->get('user_reviews');
        return $query->result();
    }

    //get user rating
    public function get_user_rating($seller_id)
    {
        $seller_id = clean_number($seller_id);
        $reviews = $this->get_reviews($seller_id);
        if (!empty($reviews)) {
            $count = count($reviews);
            $total = 0;
            foreach ($reviews as $review) {
                $total += $review->rating;
            }
            $rating = round($total / $count);
            return $rating;
        }
        return 0;
    }

    //delete review
    public function delete_review($id)
    {
        $id = clean_number($id);
        $data["review"] = $this->get_review($id);
        if (is_admin() || $data["review"]->user_id == user()->id) {
            $this->db->where('id', $id);
            return $this->db->delete('user_reviews');
        }
        return false;
    }

    //delete multi reviews
    public function delete_multi_reviews($review_ids)
    {
        if (!empty($review_ids)) {
            foreach ($review_ids as $id) {
                $this->db->where('id', $id);
                $this->db->delete('user_reviews');
            }
        }
    }

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Blog_model extends CI_Model
{
	//input values
	public function input_values()
	{
		$data = array(
			'lang_id' => $this->input->post('lang_id', true),
			'title' => $this->input->post('title', true),
			'slug' => $this->input->post('slug', true),
			'summary' => $this->input->post('summary', true),
			'keywords' => $this->input->post('keywords', true),
			'category_id' => $this->input->post('category_id', true),
			'content' => $this->input->post('content', false),
			'user_id' => user()->id
		);
		return $data;
	}

	//add post
	public function add_post()
	{
		$data = $this->input_values();

		if (empty($data["slug"])) {
			//slug for title
			$data["slug"] = str_slug($data["title"]);
		}
		$data["created_at"] = date('Y-m-d H:i:s');
		$data["storage"] = "local";

		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data["image_default"] = $this->upload_model->blog_image_default_upload($temp_path);
			$data["image_small"] = $this->upload_model->blog_image_small_upload($temp_path);
			$this->upload_model->delete_temp_image($temp_path);
		} else {
			$data["image_default"] = "";
			$data["image_small"] = "";
		}

		//move to s3
		if ($this->storage_settings->storage == "aws_s3") {
			$this->load->model("aws_model");
			$data["storage"] = "aws_s3";
			//move default image
			if ($data["image_default"] != "") {
				$this->aws_model->put_blog_object($data["image_default"], FCPATH . $data["image_default"]);
				delete_file_from_server($data["image_default"]);
			}
			//move small image
			if ($data["image_small"] != "") {
				$this->aws_model->put_blog_object($data["image_small"], FCPATH . $data["image_small"]);
				delete_file_from_server($data["image_small"]);
			}
		}
		return $this->db->insert('blog_posts', $data);
	}

	//update slug
	public function update_slug($id)
	{
		$id = clean_number($id);
		$post = $this->get_post($id);

		if (empty($post->slug) || $post->slug == "-") {
			$data = array(
				'slug' => $post->id
			);
			$this->db->where('id', $id);
			$this->db->update('blog_posts', $data);
		} else {
			if ($this->check_is_slug_unique($post->slug, $id) == true) {
				$data = array(
					'slug' => $post->slug . "-" . $post->id
				);

				$this->db->where('id', $id);
				$this->db->update('blog_posts', $data);
			}
		}
	}

	//check slug
	public function check_is_slug_unique($slug, $id)
	{
		$this->db->where('blog_posts.slug', $slug);
		$this->db->where('blog_posts.id !=', $id);
		$query = $this->db->get('blog_posts');
		if ($query->num_rows() > 0) {
			return true;
		} else {
			return false;
		}
	}

	//get post
	public function get_post($id)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('blog_posts');
		return $query->row();
	}

	//get post joined
	public function get_post_joined($id)
	{
		$id = clean_number($id);
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.id', $id);
		$query = $this->db->get('blog_posts');
		return $query->row();
	}

	//get post by slug
	public function get_post_by_slug($slug)
	{
		$slug = remove_special_characters($slug);
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.slug', $slug);
		$query = $this->db->get('blog_posts');
		return $query->row();
	}

	//get posts
	public function get_posts()
	{
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get posts count
	public function get_posts_count()
	{
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$query = $this->db->get('blog_posts');
		return $query->num_rows();
	}

	//get all posts
	public function get_posts_all()
	{
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get all posts joined
	public function get_posts_all_joined()
	{
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get all posts count
	public function get_all_posts_count()
	{
		$query = $this->db->get('blog_posts');
		return $query->num_rows();
	}

	//get latest posts
	public function get_latest_posts($limit)
	{
		$limit = clean_number($limit);
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$this->db->limit($limit);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get posts by category
	public function get_posts_by_category($category_id)
	{
		$category_id = clean_number($category_id);
		$this->db->where('blog_posts.category_id', $category_id);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get post count by category
	public function get_post_count_by_category($category_id)
	{
		$category_id = clean_number($category_id);
		$this->db->where('blog_posts.category_id', $category_id);
		$query = $this->db->get('blog_posts');
		return $query->num_rows();
	}

	//get paginated posts
	public function get_paginated_posts($per_page, $offset)
	{
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get paginated category posts
	public function get_paginated_category_posts($per_page, $offset, $category_id)
	{
		$category_id = clean_number($category_id);
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.category_id', $category_id);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get paginated tag posts
	public function get_paginated_tag_posts($per_page, $offset, $tag_slug)
	{
		$tag_slug = remove_special_characters($tag_slug);
		$this->db->join('blog_tags', 'blog_posts.id= blog_tags.post_id');
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_tags.tag_slug', $tag_slug);
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$this->db->order_by('blog_posts.created_at', 'DESC');
		$this->db->limit($per_page, $offset);
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//get paginated tag posts count
	public function get_paginated_tag_posts_count($tag_slug)
	{
		$tag_slug = remove_special_characters($tag_slug);
		$this->db->join('blog_tags', 'blog_posts.id= blog_tags.post_id');
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_tags.tag_slug', $tag_slug);
		$this->db->where('blog_posts.lang_id', $this->selected_lang->id);
		$query = $this->db->get('blog_posts');
		return $query->num_rows();
	}

	//get related posts
	public function get_related_posts($category_id, $post_id)
	{
		$category_id = clean_number($category_id);
		$post_id = clean_number($post_id);
		$this->db->join('blog_categories', 'blog_posts.category_id= blog_categories.id');
		$this->db->select('blog_posts.*, blog_categories.name as category_name, blog_categories.slug as category_slug');
		$this->db->where('blog_posts.id !=', $post_id);
		$this->db->where('blog_posts.category_id', $category_id);
		$this->db->order_by('rand()');
		$this->db->limit(3);
		$query = $this->db->get('blog_posts');
		return $query->result();
	}

	//update post
	public function update_post($id)
	{
		$data = $this->input_values();
		$post = $this->get_post($id);

		//slug for title
		if (empty($data["slug"])) {
			$data["slug"] = str_slug($data["title"]);
		}

		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data["image_default"] = $this->upload_model->blog_image_default_upload($temp_path);
			$data["image_small"] = $this->upload_model->blog_image_small_upload($temp_path);
			$this->upload_model->delete_temp_image($temp_path);
			$data["storage"] = "local";
			//move to s3
			if ($this->storage_settings->storage == "aws_s3") {
				$this->load->model("aws_model");
				$data["storage"] = "aws_s3";
				//move default image
				$this->aws_model->put_blog_object($data["image_default"], FCPATH . $data["image_default"]);
				delete_file_from_server($data["image_default"]);
				//move small image
				$this->aws_model->put_blog_object($data["image_small"], FCPATH . $data["image_small"]);
				delete_file_from_server($data["image_small"]);
			}
			//delete old image
			if ($post->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_blog_object($post->image_default);
				$this->aws_model->delete_blog_object($post->image_small);
			} else {
				delete_file_from_server($post->image_default);
				delete_file_from_server($post->image_small);
			}
		}

		$this->db->where('id', $id);
		return $this->db->update('blog_posts', $data);
	}

	//delete post
	public function delete_post($id)
	{
		$id = clean_number($id);
		$post = $this->get_post($id);

		if (!empty($post)) {
			//delete from s3
			if ($post->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_blog_object($post->image_default);
				$this->aws_model->delete_blog_object($post->image_small);
			} else {
				delete_file_from_server($post->image_default);
				delete_file_from_server($post->image_small);
			}
			//delete post tags
			$this->tag_model->delete_post_tags($id);
			$this->db->where('id', $id);
			return $this->db->delete('blog_posts');
		} else {
			return false;
		}
	}

}

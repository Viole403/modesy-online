<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{
	//input values
	public function input_values()
	{
		$data = array(
			'slug' => $this->input->post('slug', true),
			'title_meta_tag' => $this->input->post('title_meta_tag', true),
			'description' => $this->input->post('description', true),
			'keywords' => $this->input->post('keywords', true),
			'category_order' => $this->input->post('category_order', true),
			'homepage_order' => $this->input->post('homepage_order', true),
			'visibility' => $this->input->post('visibility', true),
			'show_on_homepage' => $this->input->post('show_on_homepage', true),
			'show_image_on_navigation' => $this->input->post('show_image_on_navigation', true),
			'created_at' => date('Y-m-d H:i:s')
		);

		return $data;
	}

	//add category
	public function add_category()
	{
		$data = $this->input_values();
		//set slug
		if (empty($data["slug"])) {
			//slug for title
			$data["slug"] = str_slug($this->input->post('name_lang_' . $this->general_settings->site_lang, true));
		}

		//set parent id
		$data["parent_id"] = 0;
		$category_ids_array = $this->input->post('parent_id', true);
		if (!empty($category_ids_array)) {
			foreach ($category_ids_array as $key => $value) {
				if (!empty($value)) {
					$data["parent_id"] = $value;
				}
			}
		}

		$data["storage"] = "local";
		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data["image_1"] = $this->upload_model->category_image_upload($temp_path, 420, 312);
			$data["image_2"] = $this->upload_model->category_image_upload($temp_path, 440, 541);
			$this->upload_model->delete_temp_image($temp_path);
		} else {
			$data["image_1"] = "";
			$data["image_2"] = "";
		}
		//move to s3
		if ($this->storage_settings->storage == "aws_s3") {
			$this->load->model("aws_model");
			$data["storage"] = "aws_s3";
			//move image 1
			if ($data["image_1"] != "") {
				$this->aws_model->put_category_object($data["image_1"], FCPATH . $data["image_1"]);
				delete_file_from_server($data["image_1"]);
			}
			//move image 2
			if ($data["image_2"] != "") {
				$this->aws_model->put_category_object($data["image_2"], FCPATH . $data["image_2"]);
				delete_file_from_server($data["image_2"]);
			}
		}

		return $this->db->insert('categories', $data);
	}

	//add category name
	public function add_category_name($category_id)
	{
		$category_id = clean_number($category_id);
		$data = array();
		foreach ($this->languages as $language) {
			$data["category_id"] = $category_id;
			$data["lang_id"] = $language->id;
			$data["name"] = $this->input->post('name_lang_' . $language->id, true);
			$this->db->insert('categories_lang', $data);
		}
	}

	//update slug
	public function update_slug($id)
	{
		$id = clean_number($id);
		$category = $this->get_category($id);
		if (empty($category->slug) || $category->slug == "-") {
			$data = array(
				'slug' => $category->id
			);
			$this->db->where('id', $id);
			return $this->db->update('categories', $data);
		} else {
			if (!empty($this->check_category_slug($category->slug, $id))) {
				$data = array(
					'slug' => $category->slug . "-" . $category->id
				);

				$this->db->where('id', $id);
				return $this->db->update('categories', $data);
			}
		}
	}

	//update category
	public function update_category($id)
	{
		$id = clean_number($id);
		$data = $this->input_values();
		//set slug
		if (empty($data["slug"])) {
			//slug for title
			$data["slug"] = str_slug($this->input->post('name_lang_' . $this->general_settings->site_lang, true));
		}

		//set parent id
		$data["parent_id"] = 0;
		$category_ids_array = $this->input->post('parent_id', true);
		if (!empty($category_ids_array)) {
			foreach ($category_ids_array as $key => $value) {
				if (!empty($value)) {
					$data["parent_id"] = $value;
				}
			}
		}

		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data["image_1"] = $this->upload_model->category_image_upload($temp_path, 420, 312);
			$data["image_2"] = $this->upload_model->category_image_upload($temp_path, 440, 541);
			$this->upload_model->delete_temp_image($temp_path);
			$category = $this->get_category($id);
			$data["storage"] = "local";

			//move to s3
			if ($this->storage_settings->storage == "aws_s3") {
				$this->load->model("aws_model");
				$data["storage"] = "aws_s3";
				//move image 1
				$this->aws_model->put_category_object($data["image_1"], FCPATH . $data["image_1"]);
				delete_file_from_server($data["image_1"]);
				//move image 2
				$this->aws_model->put_category_object($data["image_2"], FCPATH . $data["image_2"]);
				delete_file_from_server($data["image_2"]);
			}
			//delete old images
			if ($category->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_category_object($category->image_1);
				$this->aws_model->delete_category_object($category->image_2);
			} else {
				delete_file_from_server($category->image_1);
				delete_file_from_server($category->image_2);
			}
		}

		$this->db->where('id', $id);
		return $this->db->update('categories', $data);
	}

	//update category name
	public function update_category_name($category_id)
	{
		$category_id = clean_number($category_id);
		$data = array();
		foreach ($this->languages as $language) {
			$data["category_id"] = $category_id;
			$data["lang_id"] = $language->id;
			$data["name"] = $this->input->post('name_lang_' . $language->id, true);

			//check category name exists
			$this->db->where('category_id', $category_id);
			$this->db->where('lang_id', $language->id);
			$row = $this->db->get('categories_lang')->row();
			if (empty($row)) {
				$this->db->insert('categories_lang', $data);
			} else {
				$this->db->where('category_id', $category_id);
				$this->db->where('lang_id', $language->id);
				$this->db->update('categories_lang', $data);
			}
		}
	}

	//check category slug
	public function check_category_slug($slug, $id)
	{
		$id = clean_number($id);
		$this->db->where('slug', $slug);
		$this->db->where('id !=', $id);
		$query = $this->db->get('categories');
		return $query->row();
	}

	//get category
	public function get_category($id)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('categories');
		return $query->row();
	}

	//get category joined
	public function get_category_joined($id)
	{
		$id = clean_number($id);
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('categories.id', $id);
		$query = $this->db->get('categories');
		$row = $query->row();
		if (empty($row)) {
			$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
			$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
			$this->db->where('categories.id', $id);
			$query = $this->db->get('categories');
			$row = $query->row();
		}
		return $row;
	}

	//get all categories
	public function get_categories_all()
	{
		$this->db->order_by('created_at', 'DESC');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get all categories ordered by name
	public function get_categories_ordered_by_name()
	{
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->order_by('categories_lang.name');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get sitemap categories
	public function get_sitemap_categories()
	{
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get parent categories
	public function get_parent_categories()
	{
		$this->db->where('categories.parent_id', 0);
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get all parent categories
	public function get_all_parent_categories()
	{
		$this->db->where('categories.parent_id', 0);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get subcategories by parent id
	public function get_subcategories_by_parent_id($parent_id)
	{
		$parent_id = clean_number($parent_id);
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('parent_id', $parent_id);
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get subcategories by parent id except one
	public function get_subcategories_by_parent_id_except_one($parent_id, $except_id)
	{
		$parent_id = clean_number($parent_id);
		$except_id = clean_number($except_id);
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('parent_id', $parent_id);
		$this->db->where('categories.id !=', $except_id);
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get subcategories by parent id by lang
	public function get_subcategories_by_parent_id_by_lang($parent_id, $lang_id)
	{
		$parent_id = clean_number($parent_id);
		$lang_id = clean_number($lang_id);
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $lang_id);
		$this->db->where('parent_id', $parent_id);
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('category_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get category name by lang
	public function get_category_name_by_lang($category_id, $lang_id)
	{
		$category_id = clean_number($category_id);
		$category_name = "";
		$this->db->where('categories_lang.category_id', $category_id);
		$this->db->where('categories_lang.lang_id', $lang_id);
		$query = $this->db->get('categories_lang');
		$row = $query->row();
		if (!empty($row)) {
			$category_name = $row->name;
		} else {
			$this->db->where('categories_lang.category_id', $category_id);
			$query = $this->db->get('categories_lang');
			$row = $query->row();
			if (!empty($row)) {
				$category_name = $row->name;
			}
		}
		return $category_name;
	}

	//get category name by lang not completed
	public function get_category_name_by_lang_not_completed($category_id, $lang_id)
	{
		$category_id = clean_number($category_id);
		$category_name = "";
		$this->db->where('categories_lang.category_id', $category_id);
		$this->db->where('categories_lang.lang_id', $lang_id);
		$query = $this->db->get('categories_lang');
		$row = $query->row();
		if (!empty($row)) {
			$category_name = $row->name;
		}
		return $category_name;
	}

	//get category by slug
	public function get_category_by_slug($slug)
	{
		$slug = remove_special_characters($slug);
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('categories.slug', $slug);
		$this->db->where('categories.visibility', 1);
		$query = $this->db->get('categories');
		$row = $query->row();
		if (empty($row)) {
			$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
			$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
			$this->db->where('categories.slug', $slug);
			$this->db->where('categories.visibility', 1);
			$query = $this->db->get('categories');
			$row = $query->row();
		}
		return $row;
	}

	//get featured categories
	public function get_featured_categories()
	{
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('show_on_homepage', 1);
		$this->db->where('categories.visibility', 1);
		$this->db->order_by('homepage_order');
		$query = $this->db->get('categories');
		return $query->result();
	}

	//get featured categories count
	public function get_featured_categories_count()
	{
		$this->db->join('categories_lang', 'categories_lang.category_id = categories.id');
		$this->db->select('categories.*, categories_lang.lang_id as lang_id, categories_lang.name as name, categories.parent_id as join_parent_id, (SELECT slug From categories WHERE id = join_parent_id) as parent_slug');
		$this->db->where('categories_lang.lang_id', $this->selected_lang->id);
		$this->db->where('show_on_homepage', 1);
		$this->db->where('categories.visibility', 1);
		$query = $this->db->get('categories');
		return $query->num_rows();
	}

	//get featured category
	public function get_featured_category($count)
	{
		$count = clean_number($count);
		$categories = $this->get_featured_categories();
		if (!empty($categories)) {
			$i = 1;
			foreach ($categories as $category) {
				if ($i == $count) {
					return $category;
					break;
				}
				$i++;
			}
		}
		return false;
	}

	//get categories json
	public function get_categories_json($lang_id)
	{
		$categories = $this->get_categories_all();
		$array = array();
		if (!empty($categories)) {
			foreach ($categories as $category) {
				$item = array(
					'id' => $category->id,
					'parent_id' => $category->parent_id,
					'name' => "",
				);
				$this->db->where('category_id', $category->id);
				$this->db->where('lang_id', $lang_id);
				$query = $this->db->get('categories_lang');
				$row = $query->row();
				if (empty($row)) {
					$this->db->where('category_id', $category->id);
					$this->db->where('lang_id', $this->general_settings->site_lang);
					$query = $this->db->get('categories_lang');
					$row = $query->row();
				}
				if (!empty($row)) {
					$item['name'] = $row->name;
				}
				array_push($array, $item);
			}
		}
		echo json_encode($array);
	}

	//get parent categories array by category id
	public function get_parent_categories_array_by_category_id($category_id)
	{
		$array_categories = array();
		$category = $this->get_category($category_id);
		if (!empty($category)) {
			array_push($array_categories, $category);
			for ($i = 0; $i < 50; $i++) {
				$parent = $this->get_category($category->parent_id);
				if (!empty($parent)) {
					array_push($array_categories, $parent);
					$category = $parent;
					if ($category->parent_id == 0) {
						break;
					}
				}
			}
		}
		return array_reverse($array_categories);
	}

	//get parent categories ids array by category id
	public function get_parent_categories_ids_array_by_category_id($category_id)
	{
		$array_ids = array();
		$category = $this->get_category($category_id);
		if (!empty($category)) {
			array_push($array_ids, $category->id);
			for ($i = 0; $i < 50; $i++) {
				$parent = $this->get_category($category->parent_id);
				if (!empty($parent)) {
					array_push($array_ids, $parent->id);
					$category = $parent;
					if ($category->parent_id == 0) {
						break;
					}
				}
			}
		}
		return array_reverse($array_ids);
	}

	//get parent category tree ids string (All parent categories will be returned)
	public function get_parent_category_tree_ids_string($category_id)
	{
		$ids = $this->get_parent_categories_ids_array_by_category_id($category_id);
		if (!empty($ids)) {
			$ids = implode(", ", $ids);
			return $ids;
		}
		return false;
	}

	//get category tree ids array (All subcategories will be returned)
	public function get_category_tree_ids_array($parent_id)
	{
		$parent_id = clean_number($parent_id);
		$ids = array($parent_id);
		$query = $this->db->query("SELECT id FROM categories WHERE parent_id = " . $parent_id);
		foreach ($query->result() as $row) {
			$ids = array_merge($ids, $this->get_category_tree_ids_array($row->id));
		}
		return $ids;
	}

	//get category tree ids string (All subcategories will be returned)
	public function get_category_tree_ids_string($parent_id)
	{
		$ids = $this->get_category_tree_ids_array($parent_id);
		if (!empty($ids)) {
			$ids = implode(", ", $ids);
			return $ids;
		}
		return false;
	}

	//delete category name
	public function delete_category_name($category_id)
	{
		$category_id = clean_number($category_id);
		$this->db->where('category_id', $category_id);
		$query = $this->db->get('categories_lang');
		$results = $query->result();
		if (!empty($results)) {
			foreach ($results as $result) {
				$this->db->where('id', $result->id);
				$this->db->delete('categories_lang');
			}
		}
	}

	//delete category
	public function delete_category($id)
	{
		$id = clean_number($id);
		$category = $this->get_category($id);

		if (!empty($category)) {
			//delete from s3
			if ($category->storage == "aws_s3") {
				$this->load->model("aws_model");
				if (!empty($category->image_1)) {
					$this->aws_model->delete_category_object($category->image_1);
				}
				if (!empty($category->image_2)) {
					$this->aws_model->delete_category_object($category->image_2);
				}
			} else {
				delete_file_from_server($category->image_1);
				delete_file_from_server($category->image_2);
			}
			//delete category name
			$this->delete_category_name($id);
			$this->db->where('id', $id);
			return $this->db->delete('categories');
		}
		return false;
	}

}

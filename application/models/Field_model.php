<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Field_model extends CI_Model
{
	//input values
	public function input_values()
	{
		$data = array(
			'row_width' => $this->input->post('row_width', true),
			'is_required' => $this->input->post('is_required', true),
			'status' => $this->input->post('status', true),
			'field_order' => $this->input->post('field_order', true)
		);
		return $data;
	}

	//add field
	public function add_field()
	{
		$data = $this->input_values();
		if (empty($data["is_required"])) {
			$data["is_required"] = 0;
		}
		//generate filter key
		$field_name = $this->input->post('name_lang_' . $this->selected_lang->id, true);
		$key = url_title(convert_accented_characters(trim($field_name)), "_", true);

		//check filter key exists
		$row = $this->get_field_by_filter_key($key);
		if (!empty($row)) {
			$key = 'q_' . $key;
			$row = $this->get_field_by_filter_key($key);
			if (!empty($row)) {
				$key = $key . rand(1, 999);
			}
		}
		$data['product_filter_key'] = $key;
		$data['field_type'] = $this->input->post('field_type', true);

		return $this->db->insert('custom_fields', $data);
	}

	//update field
	public function update_field($id)
	{
		$data = $this->input_values();
		if (empty($data["is_required"])) {
			$data["is_required"] = 0;
		}
		$this->db->where('id', $id);
		return $this->db->update('custom_fields', $data);
	}

	//add field name
	public function add_field_name($field_id)
	{
		$data = array();
		foreach ($this->languages as $language) {
			$data["field_id"] = $field_id;
			$data["lang_id"] = $language->id;
			$data["name"] = $this->input->post('name_lang_' . $language->id, true);
			$this->db->insert('custom_fields_lang', $data);
		}
	}

	//update field name
	public function update_field_name($field_id)
	{
		$data = array();
		foreach ($this->languages as $language) {
			$data["field_id"] = $field_id;
			$data["lang_id"] = $language->id;
			$data["name"] = $this->input->post('name_lang_' . $language->id, true);
			//check field name exists
			$this->db->where('field_id', $field_id);
			$this->db->where('lang_id', $language->id);
			$row = $this->db->get('custom_fields_lang')->row();
			if (empty($row)) {
				$this->db->insert('custom_fields_lang', $data);
			} else {
				$this->db->where('field_id', $field_id);
				$this->db->where('lang_id', $language->id);
				$this->db->update('custom_fields_lang', $data);
			}
		}
	}

	//add field option
	public function add_field_option($field_id)
	{
		$common_id = generate_short_unique_id();
		foreach ($this->languages as $language) {
			$option = $this->input->post('option_lang_' . $language->id, true);
			if (!empty($option)) {
				$data = array(
					"lang_id" => $language->id,
					"field_id" => $field_id,
					"field_option" => trim($option),
					"common_id" => $common_id
				);
				$this->db->insert('custom_fields_options', $data);
			}
		}
	}

	//update field options
	public function update_field_options()
	{
		$common_id = $this->input->post('common_id', true);
		$options = $this->get_field_option_by_common_id($common_id);
		if (!empty($options)) {
			foreach ($options as $option) {
				if (!empty($option)) {
					$data = array(
						"field_option" => trim($this->input->post('option_lang_' . $option->lang_id, true))
					);
					$this->db->where('id', $option->id);
					$this->db->update('custom_fields_options', $data);
				}
			}
		}
	}

	//get field
	public function get_field($id)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('custom_fields');
		return $query->row();
	}

	//get field by filter key
	public function get_field_by_filter_key($filter_key)
	{
		$this->db->where('product_filter_key', $filter_key);
		$query = $this->db->get('custom_fields');
		return $query->row();
	}

	//get field joined
	public function get_field_joined($id)
	{
		$id = clean_number($id);
		$this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
		$this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name');
		$this->db->where('custom_fields.id', $id);
		$query = $this->db->get('custom_fields');
		return $query->row();
	}

	//get fields
	public function get_fields()
	{
		$this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
		$this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name');
		$this->db->where('custom_fields_lang.lang_id', $this->selected_lang->id);
		$this->db->order_by('field_order');
		$query = $this->db->get('custom_fields');
		return $query->result();
	}

	//get category fields
	public function get_category_fields($category_id)
	{
		$category_id = clean_number($category_id);
		$category_tree_ids = $this->category_model->get_parent_category_tree_ids_string($category_id);
		if (empty($category_tree_ids)) {
			return array();
		}
		$this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
		$this->db->join('custom_fields_category', 'custom_fields_category.field_id = custom_fields.id');
		$this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name, custom_fields_category.category_id as category_id');
		$this->db->where('custom_fields_lang.lang_id', $this->selected_lang->id);
		$this->db->where('custom_fields.status', 1);
		$this->db->where('custom_fields_category.category_id IN (' . $category_tree_ids . ')', NULL, FALSE);
		$this->db->order_by('custom_fields.field_order');
		$query = $this->db->get('custom_fields');
		return $query->result();
	}

	//get category fields by lang
	public function get_custom_fields_by_lang($category_id, $lang_id)
	{
		$category_id = clean_number($category_id);
		$category_tree_ids = $this->category_model->get_parent_category_tree_ids_string($category_id);
		if (empty($category_tree_ids)) {
			return array();
		}
		$lang_id = clean_number($lang_id);
		$this->db->join('custom_fields_lang', 'custom_fields_lang.field_id = custom_fields.id');
		$this->db->join('custom_fields_category', 'custom_fields_category.field_id = custom_fields.id');
		$this->db->select('custom_fields.*, custom_fields_lang.lang_id as lang_id, custom_fields_lang.name as name, custom_fields_category.category_id as category_id');
		$this->db->where('custom_fields_lang.lang_id', $lang_id);
		$this->db->where('custom_fields.status', 1);
		$this->db->where('custom_fields_category.category_id IN (' . $category_tree_ids . ')', NULL, FALSE);
		$this->db->order_by('custom_fields.field_order');
		$query = $this->db->get('custom_fields');
		return $query->result();
	}

	//get custom fields
	public function get_custom_filters($category_id)
	{
		$category_id = clean_number($category_id);
		$array = array();
		$array_ids = array();
		$array_product_filters = array();
		if (!empty($this->session->userdata('mds_custom_product_filters'))) {
			$this->session->unset_userdata('mds_custom_product_filters');
		}
		if (!empty($category_id)) {
			$custom_fields = $this->get_category_fields($category_id);
		} else {
			$custom_fields = $this->get_fields();
		}
		if (!empty($custom_fields)) {
			foreach ($custom_fields as $custom_field) {
				if ($custom_field->is_product_filter == 1) {
					if (!in_array($custom_field->id, $array_ids)) {
						$array_ids[] = $custom_field->id;
						$data = new stdClass();
						$data->id = $custom_field->id;
						$data->field_type = $custom_field->field_type;
						$data->name = $custom_field->name;
						$data->product_filter_key = $custom_field->product_filter_key;
						array_push($array, $data);
						//add to product filters
						$filter = new stdClass();
						$filter->id = $custom_field->id;
						$filter->name = $custom_field->name;
						$filter->product_filter_key = $custom_field->product_filter_key;
						array_push($array_product_filters, $filter);
					}
				}
			}
		}

		$this->session->set_userdata('mds_custom_product_filters', $array_product_filters);
		return $array;
	}

	//generate custom fields array
	public function generate_custom_fields_array($category_id, $product_id)
	{
		$category_id = clean_number($category_id);
		$product_id = clean_number($product_id);

		$array = array();
		$custom_fields = $this->field_model->get_category_fields($category_id);
		foreach ($custom_fields as $custom_field) {
			$data = new stdClass();
			$data->id = $custom_field->id;
			$data->field_type = $custom_field->field_type;
			$data->name = $custom_field->name;
			$data->is_required = $custom_field->is_required;
			$data->category_id = $custom_field->category_id;
			$data->row_width = $custom_field->row_width;
			$data->product_filter_key = $custom_field->product_filter_key;
			$data->field_value = "";
			$data->field_common_ids = array();

			$field_values = $this->get_product_custom_field_values($custom_field->id, $product_id);
			if (!empty($field_values)) {
				foreach ($field_values as $field_value) {
					if (!empty($field_value->field_value)) {
						$data->field_value = $field_value->field_value;
					}
					$data->field_common_ids[] = $field_value->selected_option_common_id;
				}
			}
			array_push($array, $data);
		}
		return $array;
	}

	//get field categories
	public function get_field_categories($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_category');
		return $query->result();
	}

	//get field name by lang
	public function get_field_name_by_lang($field_id, $lang_id)
	{
		$field_id = clean_number($field_id);
		$lang_id = clean_number($lang_id);
		$this->db->where('custom_fields_lang.field_id', $field_id);
		$this->db->where('custom_fields_lang.lang_id', $lang_id);
		$query = $this->db->get('custom_fields_lang');
		$row = $query->row();
		if (!empty($row)) {
			return $row->name;
		} else {
			return "";
		}
	}

	//get field options
	public function get_field_options($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('lang_id', $this->selected_lang->id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_options');
		return $query->result();
	}

	//get field options by lang
	public function get_custom_field_options_by_lang($field_id, $lang_id)
	{
		$field_id = clean_number($field_id);
		$lang_id = clean_number($lang_id);
		$this->db->where('lang_id', $lang_id);
		$this->db->where('field_id', $field_id);
		$this->db->order_by('field_option');
		$query = $this->db->get('custom_fields_options');
		return $query->result();
	}

	//get field all options
	public function get_field_all_options($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_options');
		return $query->result();
	}

	//get field option
	public function get_field_option($option_id)
	{
		$option_id = clean_number($option_id);
		$this->db->where('id', $option_id);
		$query = $this->db->get('custom_fields_options');
		return $query->row();
	}

	//get field option by common id
	public function get_field_option_by_common_id($common_id)
	{
		$this->db->where('common_id', $common_id);
		$query = $this->db->get('custom_fields_options');
		return $query->result();
	}

	//get field option by lang id
	public function get_field_option_by_lang($common_id, $lang_id)
	{
		$lang_id = clean_number($lang_id);
		$this->db->where('common_id', $common_id);
		$this->db->where('lang_id', $lang_id);
		$query = $this->db->get('custom_fields_options');
		return $query->row();
	}

	//add category to field
	public function add_category_to_field()
	{
		$field_id = clean_number($this->input->post("field_id"));
		$category_id = 0;
		$category_ids_array = $this->input->post('category_id', true);
		if (!empty($category_ids_array)) {
			foreach ($category_ids_array as $key => $value) {
				if (!empty($value)) {
					$category_id = $value;
				}
			}
		}

		$row = $this->get_category_field($field_id, $category_id);
		if (empty($row)) {
			$data = array(
				'field_id' => $field_id,
				'category_id' => $category_id
			);
			return $this->db->insert('custom_fields_category', $data);
		}
		return false;
	}

	//get category field
	public function get_category_field($field_id, $category_id)
	{
		$field_id = clean_number($field_id);
		$category_id = clean_number($category_id);
		$this->db->where('field_id', $field_id);
		$this->db->where('category_id', $category_id);
		$query = $this->db->get('custom_fields_category');
		return $query->row();
	}

	//get product custom field values
	public function get_product_custom_field_values($field_id, $product_id)
	{
		$field_id = clean_number($field_id);
		$product_id = clean_number($product_id);
		$this->db->where('field_id', $field_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('custom_fields_product');
		return $query->result();
	}

	//delete category from field
	public function delete_category_from_field($field_id, $category_id)
	{
		$field_id = clean_number($field_id);
		$category_id = clean_number($category_id);
		$this->db->where('field_id', $field_id);
		$this->db->where('category_id', $category_id);
		return $this->db->delete('custom_fields_category');
	}

	//delete custom field option
	public function delete_custom_field_option($common_id)
	{
		$option = $this->get_field_option_by_common_id($common_id);
		if (!empty($option)) {
			foreach ($option as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_options');
			}
		}
	}

	//clear field categories
	public function clear_field_categories($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_category');
		$fields = $query->result();
		if (!empty($fields)) {
			foreach ($fields as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_category');
			}
		}
	}

	//add remove custom field filters
	public function add_remove_custom_field_filters($field_id)
	{
		$field_id = clean_number($field_id);
		$field = $this->get_field($field_id);
		if (!empty($field)) {
			if ($field->is_product_filter == 1) {
				$data = array(
					"is_product_filter" => 0
				);
			} else {
				$data = array(
					"is_product_filter" => 1
				);
			}
			$this->db->where('id', $field->id);
			return $this->db->update('custom_fields', $data);
		}
	}

	//delete field options
	public function delete_field_options($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_options');
		$fields = $query->result();
		if (!empty($fields)) {
			foreach ($fields as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_options');
			}
		}
	}

	//delete field product values
	public function delete_field_product_values($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_product');
		$fields = $query->result();
		if (!empty($fields)) {
			foreach ($fields as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_product');
			}
		}
	}

	//delete field product values by product id
	public function delete_field_product_values_by_product_id($product_id)
	{
		$product_id = clean_number($product_id);
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('custom_fields_product');
		$fields = $query->result();
		if (!empty($fields)) {
			foreach ($fields as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_product');
			}
		}
	}

	//delete field name
	public function delete_field_name($field_id)
	{
		$field_id = clean_number($field_id);
		$this->db->where('field_id', $field_id);
		$query = $this->db->get('custom_fields_lang');
		$results = $query->result();
		if (!empty($results)) {
			foreach ($results as $item) {
				$this->db->where('id', $item->id);
				$this->db->delete('custom_fields_lang');
			}
		}
	}

	//delete field
	public function delete_field($id)
	{
		$id = clean_number($id);
		$field = $this->get_field($id);
		if (!empty($field)) {

			//delete field name
			$this->delete_field_name($id);
			//delete fields category
			$this->clear_field_categories($id);
			//delete options
			$this->delete_field_options($id);
			//delete product values
			$this->delete_field_product_values($id);

			$this->db->where('id', $id);
			return $this->db->delete('custom_fields');
		}
		return false;
	}

}

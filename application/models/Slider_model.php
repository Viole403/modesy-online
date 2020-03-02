<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Slider_model extends CI_Model
{
	//add item
	public function add_item()
	{
		$data = array(
			'lang_id' => $this->input->post('lang_id', true),
			'link' => $this->input->post('link', true),
			'item_order' => $this->input->post('item_order', true)
		);

		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data["image"] = $this->upload_model->slider_image_upload($temp_path);
			$this->upload_model->delete_temp_image($temp_path);
		} else {
			$data["image"] = "";
		}

		$temp_path = $this->upload_model->upload_temp_image('file_small');
		if (!empty($temp_path)) {
			$data["image_small"] = $this->upload_model->slider_small_image_upload($temp_path);
			$this->upload_model->delete_temp_image($temp_path);
		} else {
			$data["image_small"] = "";
		}

		return $this->db->insert('slider', $data);
	}

	//update item
	public function update_item($id)
	{
		$data = array(
			'lang_id' => $this->input->post('lang_id', true),
			'link' => $this->input->post('link', true),
			'item_order' => $this->input->post('item_order', true)
		);

		$item = $this->get_slider_item($id);
		if (!empty($item)) {
			$this->load->model('upload_model');
			$temp_path = $this->upload_model->upload_temp_image('file');
			if (!empty($temp_path)) {
				delete_file_from_server($item->image);
				$data["image"] = $this->upload_model->slider_image_upload($temp_path);
				$this->upload_model->delete_temp_image($temp_path);
			}
			$temp_path = $this->upload_model->upload_temp_image('file_small');
			if (!empty($temp_path)) {
				delete_file_from_server($item->image_small);
				$data["image_small"] = $this->upload_model->slider_small_image_upload($temp_path);
				$this->upload_model->delete_temp_image($temp_path);
			}
			$this->db->where('id', $id);
			return $this->db->update('slider', $data);
		}
		return false;
	}

	//get slider item
	public function get_slider_item($id)
	{
		$id = clean_number($id);
		$this->db->where('id', $id);
		$query = $this->db->get('slider');
		return $query->row();
	}

	//get slider items
	public function get_slider_items()
	{
		$this->db->where('slider.lang_id', $this->selected_lang->id);
		$this->db->order_by('item_order');
		$query = $this->db->get('slider');
		return $query->result();
	}

	//get all slider items
	public function get_slider_items_all()
	{
		$this->db->order_by('item_order');
		$query = $this->db->get('slider');
		return $query->result();
	}

	//delete slider item
	public function delete_slider_item($id)
	{
		$id = clean_number($id);
		$slider_item = $this->get_slider_item($id);
		if (!empty($slider_item)) {
			//delete from s3
			if ($slider_item->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_slider_object($slider_item->image);
			} else {
				delete_file_from_server($slider_item->image);
				delete_file_from_server($slider_item->image_small);
			}
			$this->db->where('id', $id);
			return $this->db->delete('slider');
		}
		return false;
	}

}

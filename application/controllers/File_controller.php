<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_controller extends Home_Core_Controller
{
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Upload Image
	 */
	public function upload_image()
	{
		$this->file_model->upload_image();
	}

	/**
	 * Upload Image Session
	 */
	public function upload_image_session()
	{
		$this->file_model->upload_image_session();
	}

	/**
	 * Get Uploaded Image Session
	 */
	public function get_sess_uploaded_image()
	{
		$file_id = $this->input->post('file_id', true);
		$modesy_images = $this->file_model->get_sess_product_images_array();
		if (!empty($modesy_images)) {
			foreach ($modesy_images as $modesy_image) {
				if ($modesy_image->file_id == $file_id) {
					echo '<img src="' . base_url() . "uploads/temp/" . $modesy_image->img_small . '" alt="">' .
						'<a href="javascript:void(0)" class="btn-img-delete btn-delete-product-img-session" data-file-id="' . $modesy_image->file_id . '"><i class="icon-close"></i></a>' .
						'<a href="javascript:void(0)" class="badge badge-secondary badge-is-image-main btn-set-image-main-session" data-file-id="' . $modesy_image->file_id . '">' . trans("main") . '</a>';
					break;
				}
			}
		}
	}

	/**
	 * Get Uploaded Image
	 */
	public function get_uploaded_image()
	{
		$image_id = $this->input->post('image_id', true);
		$product_image = $this->file_model->get_image($image_id);
		if (!empty($product_image)) {
			echo '<img src="' . get_product_image_url($product_image, 'image_small') . '" alt="">' .
				'<a href="javascript:void(0)" class="btn-img-delete btn-delete-product-img" data-file-id="' . $product_image->id . '"><i class="icon-close"></i></a>' .
				'<a href="javascript:void(0)" class="badge badge-secondary badge-is-image-main btn-set-image-main" data-image-id="' . $product_image->id . '" data-product-id="' . $product_image->product_id . '">' . trans("main") . '</a>';
		}
	}

	/**
	 * Load Image Section
	 */
	public function load_image_section()
	{
		$this->load->view("product/_image_upload_box");
	}

	/**
	 * Load Image Update Section
	 */
	public function load_image_update_section()
	{
		$product_id = $this->input->post('product_id', true);
		$data["images_array"] = $this->file_model->get_product_images_array($product_id);
		$data["product"] = $this->product_admin_model->get_product($product_id);

		$this->load->view("product/_image_update_box", $data);
	}

	/**
	 * Set Main Image Session
	 */
	public function set_image_main_session()
	{
		$image_id = $this->input->post('file_id', true);
		$this->file_model->set_sess_image_main($image_id);
	}

	/**
	 * Set Main Image
	 */
	public function set_image_main()
	{
		$image_id = $this->input->post('image_id', true);
		$product_id = $this->input->post('product_id', true);
		$this->file_model->set_image_main($image_id, $product_id);
	}

	/**
	 * Delete Image Session
	 */
	public function delete_image_session()
	{
		$file_id = $this->input->post('file_id', true);
		$this->file_model->delete_image_session($file_id);
	}

	/**
	 * Delete Image
	 */
	public function delete_image()
	{
		$image_id = $this->input->post('file_id', true);
		$this->file_model->delete_product_image($image_id);
	}

	/**
	 * --------------------------------------------------------------------------
	 * File Manager Image Upload
	 * --------------------------------------------------------------------------
	 */

	//upload file manager images
	public function upload_file_manager_images()
	{
		$this->file_model->upload_file_manager_images();
	}

	//get file manager images
	public function get_file_manager_images()
	{
		$images = $this->file_model->get_user_file_manager_images();
		foreach ($images as $image):
			echo '<div class="col-file-manager" id="ckimg_col_id_' . $image->id . '">';
			echo '<div class="file-box" data-file-id="' . $image->id . '" data-file-path="' . get_file_manager_image($image) . '">';
			echo '<div class="image-container">';
			echo '<img src="' . get_file_manager_image($image) . '" alt="" class="img-responsive">';
			echo '</div></div> </div>';
			$this->session->set_userdata("fm_last_ckimg_id", $image->id);
		endforeach;
	}

	//delete file manager image
	public function delete_file_manager_image()
	{
		$file_id = $this->input->post('file_id', true);
		$this->file_model->delete_file_manager_image($file_id);
	}

	/**
	 * --------------------------------------------------------------------------
	 * Digital Files Upload
	 * --------------------------------------------------------------------------
	 */

	//upload digital files
	public function upload_digital_files()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->upload_digital_files($product_id);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$this->load->view('product/_digital_files_upload_response', $data);
	}

	//delete digital file
	public function delete_digital_file()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->delete_digital_file($product_id);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$this->load->view('product/_digital_files_upload_response', $data);
	}

	//download digital file
	public function download_digital_file()
	{
		if (!auth_check()) {
			redirect($this->agent->referrer());
		}
		$id = $this->input->post('file_id', true);
		$file = $this->file_model->get_digital_file($id);
		if (!empty($file)) {
			if (($file->user_id == user()->id) || (user()->role == 'admin')) {
				$this->load->helper('download');
				force_download(FCPATH . "uploads/digital-files/" . $file->file_name, NULL);
			}
		}
		redirect($this->agent->referrer());
	}

	//download purchased digital file
	public function download_purchased_digital_file()
	{
		post_method();
		if (!auth_check()) {
			redirect($this->agent->referrer());
		}
		$sale_id = $this->input->post('sale_id', true);
		$sale = $this->product_model->get_digital_sale($sale_id);
		if (!empty($sale)) {
			if ($sale->buyer_id == user()->id) {
				$submit = $this->input->post('submit', true);
				if ($submit == 'license_certificate') {
					//download license certificate
					$this->file_model->create_license_key_file($sale);
				} else {
					$file = $this->file_model->get_product_digital_file($sale->product_id);
					$this->load->helper('download');
					@force_download(FCPATH . "uploads/digital-files/" . $file->file_name, NULL);
				}
			}
		}
		redirect($this->agent->referrer());
	}

	//download free digital file
	public function download_free_digital_file()
	{
		if (!auth_check()) {
			redirect($this->agent->referrer());
		}
		$product_id = $this->input->post('product_id', true);
		$file = $this->file_model->get_product_digital_file($product_id);
		if (!empty($file)) {
			$this->load->helper('download');
			@force_download(FCPATH . "uploads/digital-files/" . $file->file_name, NULL);
		}
	}

	/**
	 * --------------------------------------------------------------------------
	 * Video Upload
	 * --------------------------------------------------------------------------
	 */

	//upload video
	public function upload_video()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->upload_video($product_id);
		echo $product_id;
	}

	//load video preview
	public function load_video_preview()
	{
		$product_id = $this->input->post('product_id', true);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$this->load->view('product/_video_upload_response', $data);
	}

	//delete video
	public function delete_video()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->delete_video($product_id);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$this->load->view('product/_video_upload_response', $data);
	}

	/**
	 * --------------------------------------------------------------------------
	 * Audio Upload
	 * --------------------------------------------------------------------------
	 */

	//upload audio
	public function upload_audio()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->upload_audio($product_id);
		echo $product_id;
	}

	//load audio preview
	public function load_audio_preview()
	{
		$product_id = $this->input->post('product_id', true);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$data['audio'] = $this->file_model->get_product_audio($product_id);
		$this->load->view('product/_audio_upload_response', $data);
	}

	//delete audio
	public function delete_audio()
	{
		$product_id = $this->input->post('product_id', true);
		$this->file_model->delete_audio($product_id);
		$data['product'] = $this->product_model->get_product_by_id($product_id);
		$this->load->view('product/_audio_upload_response', $data);
	}

}

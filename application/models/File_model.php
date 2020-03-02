<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class File_model extends Core_Model
{
	public function __construct()
	{
		parent::__construct();
		$this->user_id = 0;
		if ($this->auth_check) {
			$this->user_id = $this->auth_user->id;
		}
	}

	//upload image
	public function upload_image()
	{
		$product_id = $this->input->post('product_id', true);

		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$data = array(
				'product_id' => $product_id,
				'image_default' => $this->upload_model->product_default_image_upload($temp_path, "images"),
				'image_big' => $this->upload_model->product_big_image_upload($temp_path, "images"),
				'image_small' => $this->upload_model->product_small_image_upload($temp_path, "images"),
				'is_main' => 0,
				'storage' => "local"
			);
			$this->upload_model->delete_temp_image($temp_path);

			//move to s3
			if ($this->storage_settings->storage == "aws_s3") {
				$this->load->model("aws_model");
				$data["storage"] = "aws_s3";
				//move images
				if (!empty($data["image_default"])) {
					$this->aws_model->put_product_object($data["image_default"], FCPATH . "uploads/images/" . $data["image_default"]);
					delete_file_from_server("uploads/images/" . $data["image_default"]);
				}
				if (!empty($data["image_big"])) {
					$this->aws_model->put_product_object($data["image_big"], FCPATH . "uploads/images/" . $data["image_big"]);
					delete_file_from_server("uploads/images/" . $data["image_big"]);
				}
				if (!empty($data["image_small"])) {
					$this->aws_model->put_product_object($data["image_small"], FCPATH . "uploads/images/" . $data["image_small"]);
					delete_file_from_server("uploads/images/" . $data["image_small"]);
				}
			}
			$this->db->insert('images', $data);
			$data = array(
				'image_id' => $this->db->insert_id()
			);
			echo json_encode($data);
		}
	}

	//upload image session
	public function upload_image_session()
	{
		$this->load->model('upload_model');
		$temp_path = $this->upload_model->upload_temp_image('file');
		if (!empty($temp_path)) {
			$file_id = $this->input->post('file_id', true);
			$modesy_images = $this->get_sess_product_images_array();
			$item = new stdClass();
			$item->img_default = $this->upload_model->product_default_image_upload($temp_path, "temp");
			$item->img_big = $this->upload_model->product_big_image_upload($temp_path, "temp");
			$item->img_small = $this->upload_model->product_small_image_upload($temp_path, "temp");
			$item->file_id = $file_id;
			$item->is_main = 0;
			$item->file_time = time();
			array_push($modesy_images, $item);
			$this->set_sess_product_images_array($modesy_images);
			$this->upload_model->delete_temp_image($temp_path);
		}
	}

	//add product images
	public function add_product_images($product_id)
	{
		$modesy_images = $this->get_sess_product_images_array();
		if (!empty($modesy_images)) {
			foreach ($modesy_images as $modesy_image) {
				if (!empty($modesy_image)) {
					$image_storage = "local";
					if ($this->storage_settings->storage == "aws_s3") {
						$image_storage = "aws_s3";
						$this->load->model("aws_model");
						//move default image
						$this->aws_model->put_product_object($modesy_image->img_default, FCPATH . "uploads/temp/" . $modesy_image->img_default);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_default);
						//move big image
						$this->aws_model->put_product_object($modesy_image->img_big, FCPATH . "uploads/temp/" . $modesy_image->img_big);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_big);
						//move small image
						$this->aws_model->put_product_object($modesy_image->img_small, FCPATH . "uploads/temp/" . $modesy_image->img_small);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_small);
					} else {
						//move default image
						copy(FCPATH . "uploads/temp/" . $modesy_image->img_default, FCPATH . "uploads/images/" . $modesy_image->img_default);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_default);
						//move big image
						copy(FCPATH . "uploads/temp/" . $modesy_image->img_big, FCPATH . "uploads/images/" . $modesy_image->img_big);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_big);
						//move small image
						copy(FCPATH . "uploads/temp/" . $modesy_image->img_small, FCPATH . "uploads/images/" . $modesy_image->img_small);
						delete_file_from_server("uploads/temp/" . $modesy_image->img_small);
					}

					//add to database
					$data = array(
						'product_id' => $product_id,
						'image_default' => $modesy_image->img_default,
						'image_big' => $modesy_image->img_big,
						'image_small' => $modesy_image->img_small,
						'is_main' => $modesy_image->is_main,
						'storage' => $image_storage
					);
					$this->db->insert('images', $data);
				}
			}
		}

		$this->unset_sess_product_images_array();
	}

	//set product images array session
	public function set_sess_product_images_array($modesy_images)
	{
		$this->session->set_userdata('modesy_product_images', $modesy_images);
	}

	//set image main session
	public function set_sess_image_main($file_id)
	{
		$modesy_images = $this->get_sess_product_images_array();
		if (!empty($modesy_images)) {
			foreach ($modesy_images as $modesy_image) {
				if ($modesy_image->file_id == $file_id) {
					$modesy_image->is_main = 1;
				} else {
					$modesy_image->is_main = 0;
				}
			}
		}
		$this->set_sess_product_images_array($modesy_images);
	}

	//set image main
	public function set_image_main($image_id, $product_id)
	{
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('images');
		$rows = $query->result();

		if (!empty($rows)) {
			foreach ($rows as $row) {
				if ($row->id == $image_id) {
					$data['is_main'] = 1;
				} else {
					$data['is_main'] = 0;
				}
				$this->db->where('id', $row->id);
				$this->db->update('images', $data);
			}
		}
	}

	//get product images array session
	public function get_sess_product_images_array()
	{
		$modesy_images = array();
		if (!empty($this->session->userdata('modesy_product_images'))) {
			$modesy_images = $this->session->userdata('modesy_product_images');
		}
		if (!empty($modesy_images)) {
			usort($modesy_images, function ($a, $b) {
				return $a->file_time < $b->file_time ? 1 : -1;
			});
		}
		return $modesy_images;
	}

	//unset product images array session
	public function unset_sess_product_images_array()
	{
		if (!empty($this->session->userdata('modesy_product_images'))) {
			$this->session->unset_userdata('modesy_product_images');
		}
	}

	//get product images
	public function get_product_images($product_id)
	{
		$key = "product_images_" . $product_id;
		$rows = get_cached_data($key);
		if (empty($rows)) {
			$this->db->where('product_id', $product_id);
			$this->db->order_by('images.is_main', 'DESC');
			$query = $this->db->get('images');
			$rows = $query->result();
			set_cache_data($key, $rows);
		}
		return $rows;
	}

	//get product images uncached
	public function get_product_images_uncached($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->order_by('images.is_main', 'DESC');
		$query = $this->db->get('images');
		return $query->result();
	}

	//get product image
	public function get_image($image_id)
	{
		$this->db->where('images.id', $image_id);
		$query = $this->db->get('images');
		return $query->row();
	}

	//get image by product
	public function get_image_by_product($product_id)
	{
		$key = "img_product_" . $product_id;
		$row = get_cached_data($key);
		if (empty($row)) {
			$this->db->where('product_id', $product_id);
			$this->db->order_by('images.is_main', 'DESC');
			$query = $this->db->get('images');
			$row = $query->row();
			set_cache_data($key, $row);
		}
		return $row;
	}

	//delete image session
	public function delete_image_session($file_id)
	{
		$modesy_images = $this->get_sess_product_images_array();
		$modesy_images_new = array();
		if (!empty($modesy_images)) {
			foreach ($modesy_images as $modesy_image) {
				if ($modesy_image->file_id == $file_id) {
					delete_file_from_server("uploads/temp/" . $modesy_image->img_default);
					delete_file_from_server("uploads/temp/" . $modesy_image->img_big);
					delete_file_from_server("uploads/temp/" . $modesy_image->img_small);
				} else {
					$item = new stdClass();
					$item->img_default = $modesy_image->img_default;
					$item->img_big = $modesy_image->img_big;
					$item->img_small = $modesy_image->img_small;
					$item->file_id = $modesy_image->file_id;
					$item->is_main = $modesy_image->is_main;
					$item->file_time = $modesy_image->file_time;
					array_push($modesy_images_new, $item);
				}
			}
		}
		unset($modesy_images);
		$this->set_sess_product_images_array($modesy_images_new);
	}

	//delete product image
	public function delete_product_image($image_id)
	{
		$image = $this->get_image($image_id);
		if (!empty($image)) {
			if ($image->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_product_object($image->image_default);
				$this->aws_model->delete_product_object($image->image_big);
				$this->aws_model->delete_product_object($image->image_small);
			} else {
				delete_file_from_server("uploads/images/" . $image->image_default);
				delete_file_from_server("uploads/images/" . $image->image_big);
				delete_file_from_server("uploads/images/" . $image->image_small);
			}
			$this->db->where('id', $image->id);
			$this->db->delete('images');
		}
	}

	//delete product images
	public function delete_product_images($product_id)
	{
		$images = $this->get_product_images($product_id);
		if (!empty($images)) {
			foreach ($images as $image) {
				$this->delete_product_image($image->id);
			}
		}
	}



	/*
	*-------------------------------------------------------------------------------------------------
	* FILE MANAGER
	*-------------------------------------------------------------------------------------------------
	*/

	//upload file manager images
	public function upload_file_manager_images()
	{
		if (!empty($_FILES['files'])) {
			$this->load->model('upload_model');
			$file_count = count($_FILES['files']['name']);
			for ($i = 0; $i < $file_count; $i++) {
				if (isset($_FILES['files']['name'])) {
					//file
					$_FILES['file']['name'] = $_FILES['files']['name'][$i];
					$_FILES['file']['type'] = $_FILES['files']['type'][$i];
					$_FILES['file']['tmp_name'] = $_FILES['files']['tmp_name'][$i];
					$_FILES['file']['error'] = $_FILES['files']['error'][$i];
					$_FILES['file']['size'] = $_FILES['files']['size'][$i];
					//upload
					$temp_path = $this->upload_model->upload_temp_image('file');
					if (!empty($temp_path)) {
						$data = array(
							'image_path' => $this->upload_model->file_manager_image_upload($temp_path),
							'storage' => "local"
						);
						//move to s3
						if ($this->storage_settings->storage == "aws_s3") {
							$this->load->model("aws_model");
							$data["storage"] = "aws_s3";
							//move images
							if (!empty($data["image_path"])) {
								$this->aws_model->put_file_manager_image_object($data["image_path"], FCPATH . "uploads/images-file-manager/" . $data["image_path"]);
								delete_file_from_server("uploads/images-file-manager/" . $data["image_path"]);
							}
						}
						$this->insert_image($data);
						$this->upload_model->delete_temp_image($temp_path);
					}
				}
			}
		}
	}

	//get user file manager images
	public function get_user_file_manager_images()
	{
		$this->db->where('user_id', $this->user_id);
		$query = $this->db->get('images_file_manager');
		return $query->result();
	}

	//get file manager image
	public function get_file_manager_image($file_id)
	{
		$this->db->where('id', $file_id);
		$query = $this->db->get('images_file_manager');
		return $query->row();
	}

	//delete file manager image
	public function delete_file_manager_image($file_id)
	{
		$image = $this->get_file_manager_image($file_id);
		if (!empty($image) && $image->user_id == $this->user_id) {
			if ($image->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_file_manager_image_object($image->image_path);
			} else {
				delete_file_from_server("uploads/images-file-manager/" . $image->image_path);
			}
			$this->db->where('id', $image->id);
			$this->db->delete('images_file_manager');
		}
	}

	//insert image
	function insert_image($data)
	{
		$ci =& get_instance();
		$ci->load->database();
		// Connect to the database
		$mysqli = new mysqli($ci->db->hostname, $ci->db->username, $ci->db->password, $ci->db->database);

		$image_big = $ci->security->xss_clean($data["image_path"]);
		$storage = $ci->security->xss_clean($data["storage"]);

		// Check for errors
		if (!mysqli_connect_errno()) {
			$mysqli->query("INSERT INTO `images_file_manager` (`image_path`, `storage`, `user_id`) VALUES ('" . $image_big . "','" . $storage . "','" . $this->user_id . "');");
		}
		// Close the connection
		$mysqli->close();
	}

	/*
	*-------------------------------------------------------------------------------------------------
	* DIGITAL FILES
	*-------------------------------------------------------------------------------------------------
	*/

	//upload digital files
	public function upload_digital_files($product_id)
	{
		if (isset($_FILES['file'])) {
			if (empty($_FILES['file']['name'])) {
				exit();
			}
		}
		$product = $this->product_model->get_product_by_id($product_id);
		if (!empty($product)) {
			$name_slug = substr(str_slug($product->title), 0, 25);
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$app_name = @str_slug($this->general_settings->application_name);
			if (!empty($app_name)) {
				$file_name = $app_name . "-" . $name_slug . "-" . $product->id . "-" . uniqid() . "." . $ext;
			} else {
				$file_name = $name_slug . "-" . $product->id . "-" . uniqid() . "." . $ext;
			}
			$this->load->model('upload_model');
			if ($this->upload_model->digital_file_upload('file', $file_name)) {
				$data = array(
					'product_id' => $product_id,
					'user_id' => $this->user_id,
					'file_name' => $file_name,
					'storage' => 'local',
					'created_at' => date('Y-m-d H:i:s')
				);
				$this->db->insert('digital_files', $data);
			}
		}
	}

	//get product digital file
	public function get_product_digital_file($product_id)
	{
		$this->db->where('product_id', $product_id);
		$query = $this->db->get('digital_files');
		return $query->row();
	}

	//get digital file
	public function get_digital_file($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('digital_files');
		return $query->row();
	}

	//create license key file
	public function create_license_key_file($sale)
	{
		$seller = get_user($sale->seller_id);
		$buyer = get_user($sale->buyer_id);
		$product = get_product($sale->product_id);

		$text = "\n" . strtoupper($this->general_settings->application_name) . " " . strtoupper(trans("license_certificate")) . "\n==============================================\n\n";
		if (!empty($product)) {
			$text .= trans("product") . ":\n";
			$text .= $product->title . "\n\n";
			$text .= trans("product_url") . ":\n";
			$text .= generate_product_url($product) . "\n\n";
		}
		if (!empty($seller)) {
			$text .= trans("seller") . ":\n";
			$text .= $seller->username . "\n\n";
		}
		if (!empty($buyer)) {
			$text .= trans("buyer") . ":\n";
			$text .= $buyer->username . "\n\n";
		}
		$text .= trans("purchase_code") . ":\n";
		$text .= $sale->purchase_code . "\n\n";
		if (!empty($sale->license_key)) {
			$text .= trans("license_key") . ":\n";
			$text .= $sale->license_key . "\n\n";
		}

		$handle = fopen("license_certificate.txt", "w");
		fwrite($handle, $text);
		fclose($handle);
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . basename('license_certificate.txt'));
		header('Expires: 0');
		header('Cache-Control: must-revalidate');
		header('Pragma: public');
		header('Content-Length: ' . filesize('license_certificate.txt'));
		readfile('license_certificate.txt');
		exit();
	}

	//delete digital file
	public function delete_digital_file($product_id)
	{
		$digital_file = $this->get_product_digital_file($product_id);
		if (!empty($digital_file)) {
			if (($digital_file->user_id == user()->id) || (user()->role == 'admin')) {
				//delete file
				if ($digital_file->storage == "aws_s3") {
					$this->load->model("aws_model");
					$this->aws_model->delete_digital_file_object($digital_file->file_name);
				} else {
					delete_file_from_server("uploads/digital-files/" . $digital_file->file_name);
				}
				$this->db->where('id', $digital_file->id);
				return $this->db->delete('digital_files');
			}
		}
	}



	/*
	*-------------------------------------------------------------------------------------------------
	* VIDEO
	*-------------------------------------------------------------------------------------------------
	*/

	//upload video
	public function upload_video($product_id)
	{
		$storage = "local";
		if ($this->storage_settings->storage == "aws_s3") {
			$this->load->model("aws_model");
			$storage = "aws_s3";
			if (isset($_FILES['file'])) {
				if (empty($_FILES['file']['name'])) {
					return null;
				}
			}
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$file_name = 'video_' . generate_unique_id() . "." . $ext;
			$this->aws_model->put_video_object($file_name, $_FILES['file']['tmp_name']);
		} else {
			$this->load->model('upload_model');
			$file_name = $this->upload_model->video_upload('file');
		}
		//add to database
		if (!empty($file_name)) {
			$data = array(
				'product_id' => $product_id,
				'media_type' => 'video',
				'file_name' => $file_name,
				'storage' => $storage
			);
			$this->db->insert('media', $data);
		}
	}

	//get product video
	public function get_product_video($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->where('media_type', 'video');
		$query = $this->db->get('media');
		return $query->row();
	}

	//delete video
	public function delete_video($product_id)
	{
		$video = $this->get_product_video($product_id);
		if (!empty($video)) {
			//delete file
			if ($video->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_video_object($video->file_name);
			} else {
				delete_file_from_server("uploads/videos/" . $video->file_name);
			}
			$this->db->where('id', $video->id);
			return $this->db->delete('media');
		}
	}


	/*
	*-------------------------------------------------------------------------------------------------
	* AUDIO
	*-------------------------------------------------------------------------------------------------
	*/

	//upload audio
	public function upload_audio($product_id)
	{
		$storage = "local";
		if ($this->storage_settings->storage == "aws_s3") {
			$this->load->model("aws_model");
			$storage = "aws_s3";
			if (isset($_FILES['file'])) {
				if (empty($_FILES['file']['name'])) {
					return null;
				}
			}
			$ext = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
			$file_name = 'audio_' . generate_unique_id() . "." . $ext;
			$this->aws_model->put_audio_object($file_name, $_FILES['file']['tmp_name']);
		} else {
			$this->load->model('upload_model');
			$file_name = $this->upload_model->audio_upload('file');
		}
		//add to database
		if (!empty($file_name)) {
			$data = array(
				'product_id' => $product_id,
				'media_type' => 'audio',
				'file_name' => $file_name,
				'storage' => $storage
			);
			$this->db->insert('media', $data);
		}
	}

	//get product audio
	public function get_product_audio($product_id)
	{
		$this->db->where('product_id', $product_id);
		$this->db->where('media_type', 'audio');
		$query = $this->db->get('media');
		return $query->row();
	}

	//delete audio
	public function delete_audio($product_id)
	{
		$audio = $this->get_product_audio($product_id);
		if (!empty($audio)) {
			//delete file
			if ($audio->storage == "aws_s3") {
				$this->load->model("aws_model");
				$this->aws_model->delete_audio_object($audio->file_name);
			} else {
				delete_file_from_server("uploads/audios/" . $audio->file_name);
			}
			$this->db->where('id', $audio->id);
			return $this->db->delete('media');
		}
	}

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Language_model extends CI_Model
{
	//input values
	public function input_values()
	{
		$data = array(
			'name' => $this->input->post('name', true),
			'short_form' => $this->input->post('short_form', true),
			'language_code' => $this->input->post('language_code', true),
			'language_order' => $this->input->post('language_order', true),
			'text_direction' => "ltr",
			'ckeditor_lang' => $this->input->post('ckeditor_lang', true),
			'status' => $this->input->post('status', true),
		);
		return $data;
	}

	//add language
	public function add_language()
	{
		$data = $this->input_values();
		$folder_name = str_slug($data["name"]);
		if (empty($folder_name)) {
			$folder_name = "lang_" . uniqid();
		}

		$data["folder_name"] = $folder_name;

		if ($this->create_language_folder($folder_name)) {

			return $this->db->insert('languages', $data);
		} else {
			return false;
		}
	}

	//add language settings
	public function add_language_settings($lang_id)
	{
		//add settings
		$settings = array(
			'lang_id' => $lang_id,
			'site_title' => "Modesy",
			'homepage_title' => "Index",
			'site_description ' => "Modesy",
			'keywords' => "modesy",
			'facebook_url' => "",
			'twitter_url' => "",
			'instagram_url' => "",
			'pinterest_url' => "",
			'linkedin_url' => "",
			'vk_url' => "",
			'youtube_url' => "",
			'about_footer' => "",
			'contact_text' => "",
			'contact_address' => "",
			'contact_email' => "",
			'contact_phone' => "",
			'copyright' => "",
			'cookies_warning' => 1,
			'cookies_warning_text' => "This site uses cookies. By continuing to browse the site you are agreeing to our use of cookies.",
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('settings', $settings);
	}

	//add language pages
	public function add_language_pages($lang_id)
	{
		//add terms page
		$page = array(
			'lang_id' => $lang_id,
			'title' => "Terms & Conditions",
			'slug' => "terms-conditions",
			'description' => "Page, Terms Conditions",
			'keywords' => "Page, Terms Conditions",
			'page_content' => "",
			'page_order' => 1,
			'visibility' => 1,
			'title_active' => 1,
			'location' => "information",
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('pages', $page);
	}

	//add language category names
	public function add_language_category_names($lang_id)
	{
		$categories = $this->category_model->get_categories_all();
		if (!empty($categories)) {
			foreach ($categories as $category) {
				$this->db->where('category_id', $category->id);
				$query = $this->db->get('categories_lang');
				$row = $query->row();
				if (!empty($row)) {
					$data = array(
						'category_id' => $category->id,
						'lang_id' => $lang_id,
						'name' => $row->name
					);
					$this->db->insert('categories_lang', $data);
				}
			}
		}

		//add terms page
		$page = array(
			'lang_id' => $lang_id,
			'title' => "Terms & Conditions",
			'slug' => "terms-conditions",
			'description' => "Page, Terms Conditions",
			'keywords' => "Page, Terms Conditions",
			'page_content' => "",
			'page_order' => 1,
			'visibility' => 1,
			'title_active' => 1,
			'location' => "information",
			'created_at' => date('Y-m-d H:i:s')
		);

		$this->db->insert('pages', $page);
	}

	//get language
	public function get_language($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('languages');
		return $query->row();
	}

	//get languages
	public function get_languages()
	{
		$this->db->order_by('languages.language_order');
		$query = $this->db->get('languages');
		return $query->result();
	}

	//get active languages
	public function get_active_languages()
	{
		$this->db->where('status', 1);
		$this->db->order_by('languages.language_order');
		$query = $this->db->get('languages');
		return $query->result();
	}

	//update language
	public function update_language($id)
	{
		$data = $this->input_values();

		$this->db->where('id', $id);
		return $this->db->update('languages', $data);
	}

	//set language
	public function set_language()
	{
		$data = array(
			'site_lang' => $this->input->post('site_lang', true),
		);

		$lang = $this->language_model->get_language($data["site_lang"]);

		if (!empty($lang)) {
			$this->db->where('id', 1);
			if ($this->db->update('general_settings', $data)) {
				$this->session->set_userdata("modesy_selected_lang", $data["site_lang"]);
				return true;
			}
		}

		return false;
	}

	//delete language
	public function delete_language($id)
	{
		$language = $this->get_language($id);
		if (!empty($language)) {
			$this->remove_language_folder($language->folder_name);
			$this->db->where('id', $id);
			return $this->db->delete('languages');
		} else {
			return false;
		}
	}

	//create language folder
	public function create_language_folder($lang_name)
	{
		$root = FCPATH . "application/language/" . $lang_name;
		$default = FCPATH . "application/language/default";

		if (!file_exists($root) && is_writable("application/language")) {
			mkdir($root, 0777, true);
			copy($default . '/db_lang.php', $root . '/db_lang.php');
			copy($default . '/email_lang.php', $root . '/email_lang.php');
			copy($default . '/form_validation_lang.php', $root . '/form_validation_lang.php');
			copy($default . '/upload_lang.php', $root . '/upload_lang.php');
			copy($default . '/site_lang.php', $root . '/site_lang.php');
			copy($default . '/index.html', $root . '/index.html');
			return true;
		} else {
			return false;
		}
	}

	//remove language folder
	public function remove_language_folder($lang_name)
	{
		$root = FCPATH . "application/language/" . $lang_name;

		//delete files
		$files = glob($root . '/*');
		if (!empty($files)) {
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file); //delete file
				}
			}
		}

		//delete folder
		if (is_dir($root)) {
			rmdir($root);
		}
	}

	//get phrases
	public function get_phrases($lang_name)
	{
		$lang = array();
		$phrases = array();
		include 'application/language/' . $lang_name . '/site_lang.php';

		foreach ($lang as $key => $value) {
			$phrases[] = array(
				'phrase' => $key,
				'label' => $value
			);
		}

		return $phrases;
	}

	//search phrases
	public function search_phrases($lang_name, $q)
	{
		$lang = array();
		$phrases = array();
		include 'application/language/' . $lang_name . '/site_lang.php';

		foreach ($lang as $key => $value) {
			if ((strpos($key, $q) !== false) || (strpos($value, $q) !== false)) {
				$phrases[] = array(
					'phrase' => $key,
					'label' => $value
				);
			}
		}

		return $phrases;
	}

	//create language file
	public function create_language_file($lang_name, $phrases, $labels)
	{
		$start = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL . PHP_EOL;
		$keys = '';
		$end = '?>';

		$i = 0;
		foreach ($phrases["phrase"] as $item) {
			if (!empty($item) && !empty($labels["label"][$i])) {
				//escape
				if (strpos($labels["label"][$i], '"') !== false) {
					$labels["label"][$i] = str_replace('"', '&quot;', $labels["label"][$i]);
				}
				$keys .= '$lang["' . $item . '"] = "' . $labels["label"][$i] . '";' . PHP_EOL;
			}

			$i++;
		}

		$content = $start . $keys . $end;

		file_put_contents(FCPATH . "application/language/" . $lang_name . "/site_lang.php", $content);
	}

	//update language file
	public function update_language_file($lang_name, $phrases, $labels)
	{
		$start = '<?php defined("BASEPATH") OR exit("No direct script access allowed");' . PHP_EOL . PHP_EOL;
		$keys = '';
		$end = '?>';

		$old_phrases = $this->get_phrases($lang_name);

		foreach ($old_phrases as $old_item) {

			$i = 0;

			foreach ($phrases["phrase"] as $item) {

				if (!empty($item) && !empty($labels["label"][$i])) {

					if ($old_item["phrase"] == $item) {
						//echo $labels["label"][$i];
						$old_item["label"] = $labels["label"][$i];
					}

					//escape
					if (strpos($labels["label"][$i], '"') !== false) {
						$labels["label"][$i] = str_replace('"', '&quot;', $labels["label"][$i]);
					}

				}

				$i++;
			}

			$keys .= '$lang["' . $old_item["phrase"] . '"] = "' . $old_item["label"] . '";' . PHP_EOL;

		}

		$content = $start . $keys . $end;

		file_put_contents(FCPATH . "application/language/" . $lang_name . "/site_lang.php", $content);
	}
}

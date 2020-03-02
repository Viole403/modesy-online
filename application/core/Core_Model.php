<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Core_Model extends CI_Model
{

	function __construct()
	{
		parent::__construct();

		//check auth
		$this->auth_check = auth_check();
		if ($this->auth_check) {
			$this->auth_user = user();
		}
	}
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Core extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }

    public function change_language($lang) {
		switch ($lang) {
			case 'id':
				$language = 'indonesian';
				break;
			case 'en':
				$language = 'english';
				break;
			default:
				$language = 'english';
				break;
		}

		$this->config->set_item('language', 'indonesian');
		$this->session->set_userdata('azlang', $language);
		redirect(app_url());
	}
	

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Info extends AZ_Controller {
	public function __construct() {
        parent::__construct();
    }

	public function index(){
		$this->load->view('info/v_info');
	}
}
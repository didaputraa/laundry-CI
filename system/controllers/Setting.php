<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->load->helper("az_core");
        $this->load->helper('az_auth');
        az_check_auth('setting');
        $this->load->helper("array");
		$this->load->library('AZApp');
    }

	public function index(){
		$azapp = $this->azapp;

		$data_header['title'] = "SETTING";
		$data_header['breadcrumb'] = array('setting');
		$azapp->set_data_header($data_header);

		$this->az_js_plugin = array(
			'ckeditor/ckeditor.js'
		);

		$data = array();
		$this->load->helper("az_config");
		$data['app_name'] = az_get_config('app_name');
		$data['app_description'] = az_get_config('app_description');
		$data['app_preface'] = az_get_config('app_preface');
		$data['app_login_title'] = az_get_config('app_login_title');
		$image = $azapp->add_image();
		$image->set_id('logo');
		$image->set_image_width("140px");
		$image->set_image_height("50px");
		$image->set_image_url(base_url().AZAPP.'assets/images/logo.png');
		$data["image"] = $image->render();

		$content = $this->load->view("setting/v_setting", $data, true);
		$azapp->add_content($content);

		$js = $this->load->view('setting/vjs_setting', '', true);
		$js = str_replace('<script>', '', $js);
		$azapp->add_js($js);
		echo $azapp->render();
	}

	
	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('app_name', azlang('App Name'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('app_description', azlang('App Description'), 'required|trim|max_length[70]');

		$data_post = $this->input->post(NULL, false);
		$err_code = 0;
		$err_message = '';
                
        if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				foreach ($data_post as $key => $value) {
					$this->db->where("key", $key);
					if (!$this->db->update("config", array("value" => $value))) {
						$err = $this->db->error();
						$err_code = $err["code"];
						$err_message = $err["message"];
					}
				}

				if(isset($_FILES['logo']['tmp_name'])){
					$config['upload_path'] = APPPATH.'/assets/images/';
					$config['allowed_types'] = 'gif|jpg|png';
					$config['overwrite'] = true;
					$config['max_size']	= '300';
					$config['max_width']  = '1000';
					$config['file_name'] = 'logo.png';
					
					$this->load->library('upload', $config);
					if (!$this->upload->do_upload('logo')){
						$err_message = $this->upload->display_errors();
					}
					else {
						$data = array('upload_data' => $this->upload->data());
					}
				}
			}
        }
		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	
}
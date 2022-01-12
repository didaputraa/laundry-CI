<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Media_manager extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->load->helper("az_core");
        $this->load->helper('az_auth');
        az_check_auth('content_media_manager');
        $this->load->helper("array");
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$data_header['title'] = azlang('Media Manager');
		$data_header['breadcrumb'] = array('content', 'content_media_manager');
		$azapp->set_data_header($data_header);

		$azapp->add_content('<iframe width="100%" style="border:none;overflow:hidden;height:800px" src="'.app_url().'media_manager/media_list?select=noselect"></iframe>');

		echo $azapp->render();
	}

	function media_list() {
		$this->load->helper('az_config');
		$this->load->helper('directory');
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$js = az_add_js('media_manager/vjs_media_manager', '', true);
		$js_core = az_add_js('js_core', '', true);
		$js = $azapp->minify_js($js);
		$js_core = $azapp->minify_js($js_core);
		$data['js'] = $js;
		$data['js_core'] = $js_core;

		$data_modal['media_selected'] = $this->input->get('media');
		$data['modal'] = '';
		if (strlen($this->input->get('media'))) {
			$vmodal = $this->load->view('media_manager/v_modal_media_manager', $data_modal, true);
			$modal = $azapp->add_modal();
			$modal->set_id('upload');
			$modal->set_modal($vmodal);
			$modal->set_modal_title(azlang('Upload Image'));
			$data['modal'] = $modal->render();
		}

		$data['aztype'] = $this->input->get('aztype');
		$data['directory'] = directory_map(APPPATH_FRONT.'assets/media_manager');
		// echo $data['directory'];die;
		$this->load->view('media_manager/v_media_manager', $data);
	}

	function delete() {
		$filename = $this->input->post('filename');
		$err_code = 0;
		$err_message = '';
		try {
			unlink(APPPATH_FRONT.'assets/media_manager/'.$filename);
		} catch (Exception $e) {
			$err_code++;
			$err_message = $e->getMessage();
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);

		echo json_encode($return);
	}

	function save() {
		$err_code = 0;
		$err_message = '';
		$media = '';
		if(isset($_FILES['file_image']['tmp_name'])){

			$config['overwrite'] = false;
			$config['max_size']	= 1000;
			$this->load->library('upload', $config);

			$config['upload_path'] = APPPATH_FRONT.'assets/media_manager/';
			// $config['allowed_types'] = 'jpg|jpeg|png';
			$config['allowed_types'] = '*';

			$title = $_FILES['file_image']['name'];
			$title = str_replace(' ', '-', $title);
			$title = str_replace('&', '-', $title);
			$new_name = Date('YmdHis').'-'.$title;
			$config['file_name'] = $new_name;
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('file_image')){
				$err_code++;
				$err_message = $this->upload->display_errors();
			}
			else {
				$data = array('upload_data' => $this->upload->data());
				$media = $new_name;
			}
		}
		else {
			$err_code++;
			$err_message = azlang('Please Upload Image');
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'media' => $media
		);

		echo json_encode($return);
	}
}
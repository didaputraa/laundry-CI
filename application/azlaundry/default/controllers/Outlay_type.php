<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlay_type extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('outlay_type');

        $this->table = 'outlay_type';
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Outlay Type Name'), azlang('Action')));
		$crud->set_id($this->table);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('outlay_type/v_outlay_type', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Outlay Type"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Outlay Type');
		$data_header['breadcrumb'] = array('master', 'outlay_type');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select('idoutlay_type, outlay_type_name');
		$crud->set_filter('outlay_type_name');
		$crud->set_sorting('outlay_type_name');
		$crud->set_id($this->table);
		$crud->add_where("status > 0");
		$crud->set_table($this->table);

		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('outlay_type_name', azlang('Outlay Type Name'), 'required|trim|max_length[200]');

		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				"outlay_type_name" => azarr($data_post, 'outlay_type_name'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_crud_edit('idoutlay_type, outlay_type_name');
	}

	public function delete() {
		$id = $this->input->post("id");
		az_crud_delete('outlay_type', $id);
	}
}
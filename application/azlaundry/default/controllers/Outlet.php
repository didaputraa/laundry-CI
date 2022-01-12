<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlet extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('outlet');
        $this->table = 'outlet';
        $this->controller = 'outlet';
        $this->load->helper('az_crud');
    }

	public function index(){
		$this->load->helper('az_location');
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();

		$crud->set_column(array('#', azlang('Outlet Code'), azlang('Name'), azlang('Address'), azlang('Phone'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('outlet/v_outlet', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Outlet"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Outlet');
		$data_header['breadcrumb'] = array('master', 'outlet');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();
		$crud->set_select('idoutlet, outlet_code, outlet_name, address, phone');
		$crud->set_filter('outlet_name');
		$crud->set_sorting('outlet_code, outlet_name, address, phone');
		$crud->set_id($this->controller);
		$crud->add_where("outlet.status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by('outlet_name');
		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('outlet_code', azlang('Outlet Code'), 'required|trim|max_length[3]');
		$this->form_validation->set_rules('outlet_name', azlang('Name'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('address', azlang('Address'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'outlet_code' => $this->input->post('outlet_code'),
				'outlet_name' => $this->input->post('outlet_name'),
				'address' => $this->input->post('address'),
				'phone' => $this->input->post('phone'),
			);

			if (strlen($this->input->post('outlet_code')) != 3) {
				$err_code++;
				$err_message = azlang('Outlet code must 3 digit');
			}

			if ($err_code == 0) {
				if ($this->config->item('demo')) {
					$err_code++;
					$err_message = azlang('Demo Version');
				}
			}

			if ($err_code == 0) {
				$this->db->where('outlet_code', $this->input->post('outlet_code'));
				$this->db->where('status', 1);
				if (strlen($idpost) > 0) {
					$this->db->where('idoutlet != ', $idpost);
				}
				$outlet = $this->db->get('outlet');
				if ($outlet->num_rows() > 0) {
					$err_code++;
					$err_message = azlang('Outlet code already used');
				}
			}

			if ($err_code == 0) {
				$response_save = az_crud_save($idpost, $this->table, $data_save);
				$err_code = azarr($response_save, 'err_code');
				$err_message = azarr($response_save, 'err_message');
				$insert_id = azarr($response_save, 'insert_id');
			}
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_crud_edit('idoutlet, outlet_code, outlet_name, address, phone');
	}

	public function delete() {
		if ($this->config->item('demo')) {
			$return = array(
				'err_code' => 1,
				'err_message' => azlang('Demo version')
			);
			echo json_encode($return);
		}
		else {
			$id = $this->input->post('id');
			az_crud_delete($this->table, $id);
		}
	}
}
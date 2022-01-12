<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Employee extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('employee');
        $this->table = 'employee';
        $this->controller = 'employee';
        $this->load->helper('az_crud');
    }

	public function index(){
		$this->load->helper('az_location');
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();

		$crud->set_column(array('#', azlang('Outlet'), azlang('Employee Code'), azlang('Employee Name'), azlang('Address'), azlang('Email'), azlang('Phone'), azlang('Postal Code'), azlang('City'), azlang('Province'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('employee/v_employee', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Employee"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Employee');
		$data_header['breadcrumb'] = array('master', 'employee');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();
		$crud->set_select('idemployee, outlet_name, employee_code, employee_name, employee.address, employee.email, employee.phone, postal_code, city_name, province_name');
		$crud->set_select_table('idemployee, outlet_name, employee_code, employee_name, address, email, phone, postal_code, city_name, province_name');
		$crud->add_join('province', 'left');
		$crud->add_join('outlet', 'left');
		$crud->add_join('city', 'left');
		$crud->set_filter('employee_name');
		$crud->set_sorting('outlet_name, employee_code, employee_name, employee.address, employee.email, employee.phone, postal_code, city_name, province_name');
		$crud->set_id($this->controller);
		$crud->add_where("employee.status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by('employee_name');
		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idoutlet', azlang('Outlet'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('employee_code', azlang('Employee Code'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('employee_name', azlang('Employee Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'idoutlet' => $this->input->post('idoutlet'),
				'employee_code' => $this->input->post('employee_code'),
				'employee_name' => $this->input->post('employee_name'),
				'address' => $this->input->post('address'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
				'postal_code' => $this->input->post('postal_code'),
				'idcity' => $this->input->post('idcity'),
				'idprovince' => $this->input->post('idprovince'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		$this->db->join('province', 'province.idprovince = employee.idprovince', 'left');
		$this->db->join('city', 'city.idcity = employee.idcity', 'left');
		$this->db->join('outlet', 'employee.idoutlet = outlet.idoutlet', 'left');
		az_crud_edit('idemployee, employee_code, employee_name, employee.address, employee.phone, email, postal_code, employee.idcity, employee.idprovince, city_name as ajax_idcity, province_name as ajax_idprovince, employee.idoutlet, outlet_name as ajax_idoutlet');
	}

	public function delete() {
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}
}
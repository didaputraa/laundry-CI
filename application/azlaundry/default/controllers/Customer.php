<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('customer');
        $this->table = 'customer';
        $this->controller = 'customer';
        $this->load->helper('az_crud');
    }

	public function index(){
		$this->load->helper('az_location');
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();

		$crud->set_column(array('#', azlang('Outlet'), azlang('Customer Code'), azlang('Customer Name'), azlang('Address'), azlang('Email'), azlang('Phone'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('customer/v_customer', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Customer"));
		$v_modal = $crud->generate_modal();

		if (strlen($this->session->userdata('idoutlet')) == 0) {
			$v_filter = $this->load->view('customer/v_top_customer', '', true);
			$crud->set_top_filter($v_filter);
		}
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);


		$data_header['title'] = azlang('Customer');
		$data_header['breadcrumb'] = array('master', 'customer');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();
		$crud->set_select('idcustomer, outlet_name, customer_code, customer_name, customer.address, customer.email, customer.phone');
		$crud->add_join('outlet', 'left');
		$crud->set_filter('customer_name');
		$crud->set_sorting('outlet_name, customer_code, customer_name, customer.address, customer.email, customer.phone');
		$crud->set_id($this->controller);
		$crud->add_where("customer.status > 0");
		$crud->set_table($this->table);
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$crud->add_where('customer.idoutlet = '.$sess_idoutlet);
		}
		$crud->set_order_by('customer_name');
		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$idoutlet = $this->session->userdata('idoutlet');
		if (strlen($idoutlet) == 0) {
			$this->form_validation->set_rules('idoutlet', azlang('Outlet'), 'required|trim');
			$idoutlet = azarr($data_post, 'idoutlet');
		}
		$this->form_validation->set_rules('customer_code', azlang('Customer Code'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('customer_name', azlang('Customer Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'idoutlet' => $idoutlet,
				'customer_code' => $this->input->post('customer_code'),
				'customer_name' => $this->input->post('customer_name'),
				'address' => $this->input->post('address'),
				'email' => $this->input->post('email'),
				'phone' => $this->input->post('phone'),
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
		$this->db->join('outlet', 'customer.idoutlet = outlet.idoutlet', 'left');
		az_crud_edit('idcustomer, customer_code, customer_name, customer.address, customer.phone, customer.email, customer.idoutlet, outlet_name as ajax_idoutlet');
	}

	public function delete() {
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}
}
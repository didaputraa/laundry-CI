<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('user_user');

        $this->table = 'user';
        $this->table_column = 'iduser, outlet_name as ajax_idoutlet, user.idoutlet, idrole, name, username, email, user.address';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Outlet Name'), azlang('Role Name'), azlang('Name'), azlang('Username'), azlang('Email'), azlang('Address'), azlang('Action')));
		$crud->set_id($this->table);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('user/v_user', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("User"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('User');
		$data_header['breadcrumb'] = array('user', 'user_user');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select('iduser, outlet_name, role.title as role_name, user.name as user_name, username, email, user.address');
		$crud->set_select_table('iduser, outlet_name, role_name, user_name, username, email, user.address');
		$crud->add_join('role');
		$crud->add_join('outlet', 'left');
		$crud->set_filter('user.name');
		$crud->set_sorting('outlet_name,role_name, user_name, username, email, user.address');
		$crud->set_id($this->table);
		$crud->add_where("user.status > 0");
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

		$this->form_validation->set_rules('name', azlang('Name'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('username', azlang('Username'), 'required|trim|max_length[30]');
		$this->form_validation->set_rules('email', azlang('Email'), 'required|trim|max_length[300]');
		$this->form_validation->set_rules('address', azlang('Address'), 'required|trim|max_length[300]');
		if (strlen($idpost) == 0) {
			$this->form_validation->set_rules('password', azlang('Password'), 'required|trim|max_length[300]');
		}

		$err_code = "";
		$err_message = "";

		if ($this->config->item('demo')) {
			$err_code++;
			$err_message = azlang('Demo version');
		}

		if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				$data_save = array(
					'idrole' => azarr($data_post, 'idrole'),
					"name" => azarr($data_post, 'name'),
					"username" => azarr($data_post, 'username'),
					"email" => azarr($data_post, 'email'),
					"address" => azarr($data_post, 'address'),
					"idoutlet" => azarr($data_post, 'idoutlet')
				);

				$password = azarr($data_post, 'password');
				$pw = true;
				if (strlen($idpost) > 0 && strlen($password) == 0) {
					$pw = false;
				}

				if ($pw) {
					$data_save['password'] = md5($password);
				}

				$response_save = az_crud_save($idpost, $this->table, $data_save);
				$err_code = azarr($response_save, 'err_code');
				$err_message = azarr($response_save, 'err_message');
			}
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		$this->db->join('outlet', 'user.idoutlet = outlet.idoutlet', 'left');
		az_crud_edit($this->table_column);
	}

	public function delete() {
		$id = $this->input->post("id");
		if ($this->config->item('demo')) {
			$return = array(
				'err_code' => 1,
				'err_message' => azlang('Demo version')
			);
			echo json_encode($return);
		}
		else {
			az_crud_delete('user', $id);
		}
	}
}
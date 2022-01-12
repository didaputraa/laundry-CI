<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Role extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('user_role');
        $this->table = 'role';
        $this->table_column = 'idrole, name, title, description, parent';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$this->load->helper('az_role');

		$role = az_get_role();
		$data_role = $this->generate_role($role);
		$data['data'] = $data_role;
		$data['role_option'] = az_generate_role_option($role);
		
		$v_content = $this->load->view('role/v_role_content', $data, true);
		$v_modal = $this->load->view('role/v_role', '', true);
		$modal = $azapp->add_modal();
		$modal->set_modal($v_modal);
		$modal->set_id('role');
		$modal->set_modal_title(azlang('Role'));
		$modal->set_action_modal(array('save' => azlang('Save')));
		$modal = $modal->render();
		$azapp->add_content($v_content);
		$azapp->add_content($modal);

		$js = $this->load->view('role/vjs_role', '', true);
		$js = str_replace('<script>', '', $js);
		$azapp->add_js($js);
		
		$data_header['title'] = azlang('Role');
		$data_header['breadcrumb'] = array('user', 'user_role');
		$azapp->set_data_header($data_header);
		echo $azapp->render();	
	}

	public function generate_role($data_role = array()) {
		$ret = '';

		foreach ($data_role as $key => $value) {
			$sub = '';
			if (count($value['subrole']) > 0) {
				$sub .= '<div class="role-subrole">';
				$sub .= $this->generate_role($value['subrole']);
				$sub .= '</div>';
			}

			$ret .= '<div class="role-item">';
			$ret .= '	<div class="role-item-name">';
			$ret .= 		azarr($value, 'title');
			$ret .= '	</div>';
			$ret .= '	<div class="role-action">';
			$ret .= '		<button class="btn btn-default btn-xs btn-edit-role" data-id="'.$value['idrole'].'">Edit</button>';
			$ret .= '		<button class="btn btn-danger btn-xs btn-delete-role" data-id="'.$value['idrole'].'">Hapus</button>';
			$ret .= '	</div>';
			$ret .= $sub;
			$ret .= '</div>';
		}

		return $ret;
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select($this->table_column);
		$crud->set_filter('title');
		$crud->set_sorting('title, description');
		$crud->set_id($this->table);
		$crud->add_where("status > 0");
		$crud->set_table($this->table);

		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('name', azlang('Name'), 'required|trim|max_length[100]');
		$this->form_validation->set_rules('title', azlang('Title'), 'required|trim|max_length[100]');
		$this->form_validation->set_rules('description', azlang('Description'), 'required|trim|max_length[300]');

		$data_post = $this->input->post();
		$err_code = 0;
		$err_message = "";

		if ($this->config->item('demo')) {
			$err_code++;
			$err_message = azlang('Demo version');
		}

		if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				$idpost = azarr($data_post, 'id'.$this->table);

				$data_save = array(
					"name" => azarr($data_post, 'name'),
					"title" => azarr($data_post, 'title'),
					"description" => azarr($data_post, 'description'),
					"parent" => azarr($data_post, 'parent')
				);

				$response_save = az_crud_save($idpost, $this->table, $data_save);
				$err_code = azarr($response_save, 'err_code');
				$err_message = azarr($response_save, 'err_message');
			}
		}

		$err_message = validation_errors().$err_message;
		$this->session->set_flashdata('error', $err_message);
		redirect(app_url().'role');
	}

	public function edit() {
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
			az_crud_delete('role', $id);
		}
	}
}
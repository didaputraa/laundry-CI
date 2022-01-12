<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_province extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        $this->load->helper('az_core');
        az_check_login();

        $this->table = 'province';
        $this->controller = 'master_province';
        $this->table_column = 'idprovince, province_name';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
        az_check_auth('master_province');
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Province Name'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('master_province/v_master_province', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Province"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Master Province');
		$data_header['breadcrumb'] = array('master', 'master_province');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		az_check_auth('master_province');
		$crud = $this->crud;
		$crud->set_select('idprovince, province_name');
		$crud->set_filter('province_name');
		$crud->set_sorting('province_name');
		$crud->set_id($this->controller);
		$crud->add_where("status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by('province_name');

		echo $crud->get_table();
	}

	public function save(){
		az_check_auth('master_province');
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('province_name', azlang('Province Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'province_name' => azarr($data_post, 'province_name'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_check_auth('master_province');
		az_crud_edit($this->table_column);
	}

	public function delete() {
		az_check_auth('master_province');
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}

	public function get_data(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("province_name");
		if (strlen($q) > 0) {
			$this->db->like("province_name", $q);
		}
		$this->db->select("idprovince as id, province_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("province", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("province_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("province");
		$count = $cdata->num_rows();

		$endCount = $offset + $limit;
		$morePages = $endCount < $count;

		$results = array(
		  "results" => $data->result_array(),
		  "pagination" => array(
		  	"more" => $morePages
		  )
		);
		echo json_encode($results);
	}
}
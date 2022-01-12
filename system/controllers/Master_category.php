<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_category extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');

        $this->table = 'category';
        $this->controller = 'master_category';
        $this->table_column = 'idcategory, category_name';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
        az_check_auth('master_category');
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Category Name'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$v_modal = $this->load->view('master_category/v_master_category', '', true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Category"));
		$v_modal = $crud->generate_modal();
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Master Category');
		$data_header['breadcrumb'] = array('master', 'master_category');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		az_check_auth('master_category');
		$crud = $this->crud;
		$crud->set_select('idcategory, category_name');
		$crud->set_filter('category_name');
		$crud->set_sorting('category_name');
		$crud->set_id($this->controller);
		$crud->add_where("status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by('category_name');

		echo $crud->get_table();
	}

	public function save(){
		az_check_auth('master_category');
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('category_name', azlang('Category Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'category_name' => azarr($data_post, 'category_name'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_check_auth('master_category');
		az_crud_edit($this->table_column);
	}

	public function delete() {
		az_check_auth('master_category');
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}

	public function get_data(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("category_name");
		if (strlen($q) > 0) {
			$this->db->like("category_name", $q);
		}
		$this->db->select("idcategory as id, category_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("category", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("category_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("category");
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
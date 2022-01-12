<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_city extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        $this->load->helper('az_core');
        az_check_login();

        $this->table = 'city';
        $this->controller = 'master_city';
        $this->table_column = 'idcity, province_name as ajax_idprovince, city.idprovince, city_name';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->load->library('encrypt');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
        az_check_auth('master_city');
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Province Name'), azlang('City Name'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$select = $azapp->add_select2();
		$select->set_url('master_province/get_data');
        $select->set_placeholder(azlang('Select Province'));
        $select->set_id('province');
        $province = $select->render();
        $data['province'] = $province;

		$v_modal = $this->load->view('master_city/v_master_city', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("City"));
		$v_modal = $crud->generate_modal();
		
		$select = $azapp->add_select2();
    
    	$select->set_url('master_province/get_data');
        $select->set_placeholder(azlang('Select Province'));
        $select->add_attr('data-id', $this->encrypt->encode('city.idprovince'));
        $select->add_class('element-top-filter');
        $fprovince = $select->render();

		$data_filter['fprovince'] = $fprovince;
		$v_filter = $this->load->view('master_city/v_top_master_city', $data_filter, true);
		$crud->set_top_filter($v_filter);

		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Master City');
		$data_header['breadcrumb'] = array('master', 'master_city');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		az_check_auth('master_city');
		$crud = $this->crud;
		$crud->set_select('idcity, province_name, city_name');
		$crud->set_filter('city_name');
		$crud->set_sorting('province_name, city_name');
		$crud->set_id($this->controller);
		$crud->add_where("city.status > 0");
		$crud->add_join('province');
		$crud->set_table($this->table);
		$crud->set_order_by('province_name, city_name');

		echo $crud->get_table();
	}

	public function save(){
		az_check_auth('master_city');
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idprovince', azlang('Province Name'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('city_name', azlang('City Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'idprovince' => azarr($data_post, 'idprovince'),
				'city_name' => azarr($data_post, 'city_name'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		az_check_auth('master_city');
		$this->db->join('province', 'city.idprovince = province.idprovince');
		az_crud_edit($this->table_column);
	}

	public function delete() {
		az_check_auth('master_city');
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}

	public function get_data(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get('parent');

		$offset = ($page - 1) * $limit;

		$this->db->order_by("city_name");
		if (strlen($q) > 0) {
			$this->db->like("city_name", $q);
		}
		$this->db->select("idcity as id, city_name as text");
		$this->db->where('status', '1');
		if (strlen($parent) > 0) {
			$this->db->where('idprovince', $parent);
		}

		$data = $this->db->get("city", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("city_name", $q);
		}
		$this->db->where('status', '1');
		if (strlen($parent) > 0) {
			$this->db->where('idprovince', $parent);
		}
		$cdata = $this->db->get("city");
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
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_district extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        $this->load->helper('az_core');
        az_check_login();

        $this->table = 'district';
        $this->controller = 'master_district';
        $this->table_column = 'iddistrict, city_name as ajax_idcity, district.idcity, district_name';
        $this->load->helper('az_lang');
        $this->load->helper('array');
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->load->library('encrypt');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('City Name'), azlang('District Name'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$select = $azapp->add_select2();
		$select->set_url('master_city/get_data');
        $select->set_placeholder(azlang('Select City'));
        $select->set_id('city');
        $city = $select->render();
        $data['city'] = $city;

		$v_modal = $this->load->view('master_district/v_master_district', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Master District"));
		$v_modal = $crud->generate_modal();
		
		$select = $azapp->add_select2();
    
    	$select->set_url('master_city/get_data');
        $select->set_placeholder(azlang('Select City'));
        $select->add_attr('data-id', $this->encrypt->encode('district.idcity'));
        $select->add_class('element-top-filter');
        $fcity = $select->render();

		$data_filter['fcity'] = $fcity;
		$v_filter = $this->load->view('master_district/v_top_master_district', $data_filter, true);
		$crud->set_top_filter($v_filter);

		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('District');
		$data_header['breadcrumb'] = array('master', 'master_district');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select('iddistrict, city_name, district_name');
		$crud->set_filter('district_name');
		$crud->set_sorting('city_name, district_name');
		$crud->set_id($this->controller);
		$crud->add_where("district.status > 0");
		$crud->add_join('city');
		$crud->set_table($this->table);
		$crud->set_order_by('city_name, district_name');

		echo $crud->get_table();
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idcity', azlang('City Name'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('district_name', azlang('District Name'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'idcity' => azarr($data_post, 'idcity'),
				'district_name' => azarr($data_post, 'district_name'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		$this->db->join('city', 'district.idcity = city.idcity');
		az_crud_edit($this->table_column);
	}

	public function delete() {
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}

	public function get_data(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get('parent');

		$offset = ($page - 1) * $limit;

		$this->db->order_by("district_name");
		if (strlen($q) > 0) {
			$this->db->like("district_name", $q);
		}
		$this->db->select("iddistrict as id, district_name as text");
		$this->db->where('status', '1');
		if (strlen($parent) > 0) {
			$this->db->where('idcity', $parent);
		}
		$data = $this->db->get("district", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("district_name", $q);
		}
		if (strlen($parent) > 0) {
			$this->db->where('idcity', $parent);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("district");
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
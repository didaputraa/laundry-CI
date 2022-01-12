<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Outlay extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('outlay');

        $this->table = 'outlay';
        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$crud = $this->crud;
		$this->load->helper('az_role');

		$crud->set_column(array(azlang('No'), azlang('Outlet'), azlang('Date'), azlang('Outlay Type Name'), azlang('Total'), azlang('Description'), azlang('Action')));
		$crud->set_id($this->table);
		$crud->set_default_url(true);

		$date = $azapp->add_datetime();
		$date->set_id('datetime');
		$date->set_name('datetime');
		$date->add_class('x-hidden');
		$date->set_value(Date('d-m-Y H:i:s'));
		$data['date'] = $date->render();

		$select = $azapp->add_select2();
		$select->set_id('outlay_type');
		$select->set_url('data/get_data_outlay_type');
		$select->set_placeholder(azlang('Select Outlay Type'));
		$data['outlay_type'] = $select->render();

		$v_modal = $this->load->view('outlay/v_outlay', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Outlay"));
		$v_modal = $crud->generate_modal();

		$date = $azapp->add_datetime();
		$date->set_format('DD-MM-YYYY');
		$date->set_value('01-'.Date('m-Y'));
		$date->set_id('datetime1');
		$date->add_class("con-element-top-filter");
		$date = $date->render();
		$data_filter['datetime1'] = $date;

		$date = $azapp->add_datetime();
		$date->set_format('DD-MM-YYYY');
		$date->set_id('datetime2');
		$date->set_value(Date('t').'-'.Date('m-Y'));
		$date->add_class("con-element-top-filter");
		$date = $date->render();
		$data_filter['datetime2'] = $date;

		$v_filter = $this->load->view('outlay/v_top_outlay', $data_filter, true);
		$crud->set_top_filter($v_filter);

		$callback = "jQuery('#txt_total_price').text(json.total);";
		$crud->set_callback_table_complete($callback);

		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$azapp->add_content("<div id='txt_total_price' style='margin-top:20px;text-align:right;font-size:20px;'>Total Rp 0</div>");

		$data_header['title'] = azlang('General Outlay');
		$data_header['breadcrumb'] = array('outlay', 'outlay');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$crud = $this->crud;
		$crud->set_select('idoutlay, outlet_name, datetime, outlay_type_name, total, description');
		$crud->set_filter('outlay_type_name');
		$crud->set_sorting('outlet_name, datetime, outlay_type_name, total, description');
		$crud->set_id($this->table);
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$crud->add_where('outlay.idoutlet = '.$sess_idoutlet);
		}
		$crud->add_where("outlay.status > 0");
		$crud->set_table($this->table);
		$crud->set_select_align(',,,right');
		$crud->set_select_decimal('3');
		$crud->add_join('outlay_type');
		$crud->add_join('outlet');
		$top_filter = $_REQUEST['topfilter'];
    	$arr_filter = array();
    	foreach ($top_filter as $key => $value) {
    		$decode = $this->encrypt->decode($key);
    		$arr_filter[$decode] = $value;
    	}

    	$xdate = explode('~az~', $arr_filter['datetime']);
    	$start_date = azarr($xdate, 0);
    	$end_date = azarr($xdate, 1);


    	$idoutlet = azarr($arr_filter, 'outlet.idoutlet');

    	$search = $_REQUEST['sSearch'];
    	if (strlen($search) > 0) {
    		$this->db->like('outlay_type_name', $search);
    	}
    	if (strlen($idoutlet) > 0) {
    		$this->db->where('outlay.idoutlet', $idoutlet);
    	}
    	if (strlen($sess_idoutlet) > 0) {
    		$this->db->where('outlay.idoutlet', $sess_idoutlet);
    	}
    	$this->db->join('outlay_type', 'outlay_type.idoutlay_type = outlay.idoutlay_type');
    	$this->db->where('date(datetime) >=', Date('Y-m-d', strtotime($start_date)));
    	$this->db->where('date(datetime) <=', Date('Y-m-d', strtotime($end_date)));
    	$this->db->where('outlay.status', 1);
    	$this->db->select("sum(total) as total");
    	$data_transaction = $this->db->get('outlay');
    	$total = $data_transaction->row()->total;	

    	$this->load->helper('az_core');
    	$total = azlang('Total').' '.az_thousand_separator($total);

    	$table = $crud->get_table();
    	$table = json_decode($table, true);
    	$arr_total = array('total' => $total);
    	$arr_return = array_merge($table, $arr_total);

		echo json_encode($arr_return);
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$idoutlet = $this->session->userdata('idoutlet');
		if (strlen($idoutlet) == 0) {
			$this->form_validation->set_rules('idoutlet', azlang('Outlet'), 'required|trim');
			$idoutlet = azarr($data_post, 'idoutlet');
		}
		$this->form_validation->set_rules('idoutlay_type', azlang('Outlay Type'), 'required|trim|max_length[300]');
		$this->form_validation->set_rules('total', azlang('Total'), 'required|trim');

		$err_code = "";
		$err_message = "";

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				"idoutlet" => $idoutlet,
				"idoutlay_type" => azarr($data_post, 'idoutlay_type'),
				"total" => str_replace('.', '', azarr($data_post, 'total')),
				"description" => azarr($data_post, 'description'),
				"datetime" => Date('Y-m-d H:i:s', strtotime(azarr($data_post, 'datetime')))
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function edit() {
		$this->db->join('outlay_type', 'outlay_type.idoutlay_type = outlay.idoutlay_type');
		$this->db->join('outlet', 'outlet.idoutlet = outlay.idoutlet');
		az_crud_edit('idoutlay, date_format(datetime, "%d-%m-%Y %H:%i:%s") as datetime, outlay.idoutlay_type, outlay_type_name as ajax_idoutlay_type, total, description, outlay.idoutlet, outlet.outlet_name as ajax_idoutlet');
	}

	public function delete() {
		$id = $this->input->post("id");
		az_crud_delete('outlay', $id);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends AZ_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        $this->load->helper('az_config');
        az_check_auth('dashboard');
    }

	public function index(){
		$this->load->library('AZApp');
		$app = $this->azapp;
		$data_header['title'] = azlang('Dashboard');
		$data_header['breadcrumb'] = array('dashboard');
		$app->set_data_header($data_header);

		$this->load->helper('az_core');
		$js = az_add_js('home/vjs_home');
		$app->add_js($js);

		$idoutlet = $this->session->userdata('idoutlet');
		if (strlen($idoutlet) > 0) {
			$this->db->where('transaction_group.idoutlet', $idoutlet);
		}
		$this->db->group_by('transaction_group_status');
		$this->db->where('date(date)', Date('Y-m-d'));
		$this->db->select('transaction_group_status, count(grand_total_final) as total');
		$this->db->where('status', 1);
		$tg = $this->db->get('transaction_group');

		$data['NEW'] = 0;
		$data['PROGRESS'] = 0;
		$data['FINISH'] = 0;
		$data['ACCEPTED'] = 0;
		foreach ($tg->result() as $key => $value) {
			$data[$value->transaction_group_status] = $value->total;
		}

		$view = $this->load->view('home/v_home', $data, true);
		$app->add_content($view);
		echo $app->render();	
	}

	

	

}
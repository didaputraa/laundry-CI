<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profit_report extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('profit_report');

        $this->load->helper('az_crud');
        $this->load->library('AZApp');
        $this->crud = $this->azapp->add_crud();
    }

	public function index(){
		$azapp = $this->azapp;
		$data_header['title'] = azlang('Profit Report');
		$data_header['breadcrumb'] = array('report', 'profit_report');
		$azapp->set_data_header($data_header);

		$datetime1 = $azapp->add_datetime();
		$datetime1->set_id("date_1");
		$datetime1->set_name("date_1");
		$datetime1->set_value('01'.Date("-m-Y"));
		$datetime1->set_format("DD-MM-YYYY");
		$data['datetime1'] = $datetime1->render();

		$datetime2 = $azapp->add_datetime();
		$datetime2->set_id("date_2");
		$datetime2->set_name("date_2");
		$datetime2->set_value(Date("d-m-Y",  strtotime("last day of this month")));
		$datetime2->set_format("DD-MM-YYYY");
		$data['datetime2'] = $datetime2->render();

		$this->load->helper('az_core');
		$js = az_add_js('profit_report/vjs_profit_report');
		$azapp->add_js($js);

		$view = $this->load->view('profit_report/v_profit_report', $data, true);
		$azapp->add_content($view);
		
		echo $azapp->render();	
	}

	function get_profit() {
		$this->load->helper('az_core');
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');

		$date_1 = Date('Y-m-d', strtotime($date_1));
		$date_2 = Date('Y-m-d', strtotime($date_2.' + 1 days'));
		
		$idoutlet = $this->input->post('idoutlet');
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$idoutlet = $sess_idoutlet;
		}

		$begin = new DateTime($date_1);
		$end = new DateTime($date_2);

		$interval = DateInterval::createFromDateString('1 day');
		$period = new DatePeriod($begin, $interval, $end);

		$arr_day = array();
		$grand_transaction = 0;
		$grand_outlay = 0;
		$grand_total = 0;
		foreach ( $period as $dt ) {
			$loop_date = $dt->format("Y-m-d");			
			if (strlen($idoutlet) > 0) {
				$this->db->where('idoutlet', $idoutlet);
			}
			$this->db->where('date(pay_date)', $loop_date);
			$this->db->select("sum(grand_total_final) as total");
			$this->db->where('status', 1);
			$this->db->where('pay', 'PAID');
			$sell = $this->db->get('transaction_group');
			$total_sell = $sell->row()->total;
			if (strlen($total_sell) == 0) {
				$total_sell = 0;
			}

			if (strlen($idoutlet) > 0) {
				$this->db->where('idoutlet', $idoutlet);
			}
			$this->db->where('date(datetime)', $loop_date);
			$this->db->select("sum(total) as total");
			$this->db->where('status', 1);
			$outlay = $this->db->get('outlay');
			$total_outlay = $outlay->row()->total;
			if (strlen($total_outlay) == 0) {
				$total_outlay = 0;
			}

			$total = $total_sell - $total_outlay;

			$grand_transaction += $total_sell;
			$grand_outlay += $total_outlay;
			$grand_total += $total;

			$day = array(
				'day' => Date('d-m-Y', strtotime($loop_date)),
				'transaction' => az_thousand_separator_decimal($total_sell),
				'outlay' => az_thousand_separator_decimal($total_outlay),
				'total' => az_thousand_separator_decimal($total),
			);
			$arr_day[] = $day;
		}
		$arr_detail['grand_transaction'] = az_thousand_separator_decimal($grand_transaction);
		$arr_detail['grand_outlay'] = az_thousand_separator_decimal($grand_outlay);
		$arr_detail['grand_total'] = az_thousand_separator_decimal($grand_total);

		$result = array(
			'data' => $arr_day,
			'detail' => $arr_detail
		);

		echo json_encode($result);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sales_transaction extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('transaction');
        $this->table = 'transaction_group';
        $this->controller = 'sales_transaction';
        $this->load->helper('az_crud');
    }

    function index() {
    	$this->load->library('AZApp');
    	$app = $this->azapp;

    	$crud = $app->add_crud();

		$crud->set_column(array('#', azlang('Outlet'), azlang('Status'), azlang('Invoice Code'),  azlang('Customer'), azlang('Date'), azlang('Duedate'), azlang('Pay Date'), azlang('Pay'), azlang('Total'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);
		$crud->set_single_filter(false);
		$crud->set_width(',,,,,,,,,,230px');

		$datetime1 = $app->add_datetime();
		$datetime1->set_id("date_1");
		$datetime1->set_name("date_1");
		$datetime1->set_value('01'.Date("-m-Y"));
		$datetime1->set_format("DD-MM-YYYY");
		$datetime1->add_class("con-element-top-filter");
		$datetime1 = $datetime1->render();

		$datetime2 = $app->add_datetime();
		$datetime2->set_id("date_2");
		$datetime2->set_name("date_2");
		$datetime2->set_value(Date("d-m-Y",  strtotime("last day of this month")));
		$datetime2->set_format("DD-MM-YYYY");
		$datetime2->add_class("con-element-top-filter");
		$datetime2 = $datetime2->render();

		$btn = "<button class='btn btn-default' type='button' id='btn_print_report'><i class='fa fa-print'></i> ".azlang('Print')."</button>";
		$crud->set_top_filter_btn($btn);


        $outlet = az_select_outlet('outlet', 'transaction_group');

        $data['datetime1'] = $datetime1;
        $data['datetime2'] = $datetime2;
        $data['outlet'] = $outlet;

        $callback = "jQuery('#txt_total_price').text(json.total);";
		$crud->set_callback_table_complete($callback);

        $v_filter = $this->load->view("sales_transaction/v_sales_transaction_top_filter", $data, true);

        if (!aznav('role_add_transaction')) {
        	$crud->set_btn_add(false);
        }

        $crud->set_top_filter($v_filter);
		$crud = $crud->render();
		$app->add_content($crud);	
		$app->add_content("<div id='txt_total_price' style='text-align:right;font-size:20px;'>Total Rp 0</div>");

		$btn_modal = array(
			'small-invoice' => "<i class='fa fa-print'></i> ".azlang('Small Invoice'),
			'standart-invoice' => "<i class='fa fa-print'></i> ".azlang('Standart Invoice'),
		);
		$modal_detail = $app->add_modal();
		$modal_detail->set_id('detail');
		$modal_detail->set_modal_title(azlang('Detail'));
		$modal_detail->set_modal('');
		$modal_detail->set_action_modal($btn_modal);
		$app->add_content($modal_detail->render());

		$pay_date = $app->add_datetime();
		$pay_date->set_id("pay_date");
		$pay_date->set_name("pay_date");
		$pay_date->add_class('x-focus');
		$pay_date = $pay_date->render();
		$data_process['pay_date'] = $pay_date;
		$v_modal_process = $this->load->view('sales_transaction/v_modal_process', $data_process, true);

		$modal_process = $app->add_modal();
		$modal_process->set_id('process');
		$modal_process->set_modal_title(azlang('Process'));
		$modal_process->set_modal($v_modal_process);
		$modal_process->set_action_modal($btn_modal);
		$modal_process->set_action_modal(array('process' => azlang('Save')));
		$app->add_content($modal_process->render());

		$this->load->helper('az_core');
		$js = az_add_js('sales_transaction/vjs_list_sales_transaction');
		$app->add_js($js);

    	$data_header['title'] = azlang('Sales Transaction');
		$data_header['breadcrumb'] = array('transaction', 'sales_transaction');
		$app->set_data_header($data_header);
    	echo $app->render();
    }

    public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();
		$crud->set_select("idtransaction_group, outlet_name, transaction_group_status, code, customer_name, date_format(date, '%d-%m-%Y %H:%i:%s') as date, date_format(duedate, '%d-%m-%Y %H:%i:%s') as duedate, date_format(pay_date, '%d-%m-%Y %H:%i:%s') as pay_date, pay, grand_total_final");
		$crud->set_select_table("idtransaction_group, outlet_name, transaction_group_status, code, customer_name, date, duedate, pay_date, pay, grand_total_final");
		$crud->set_sorting('outlet_name, transaction_group_status, code, customer_name, date, duedate, pay_date, pay, grand_total_final');
		$crud->set_id($this->controller);
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$crud->add_where('transaction_group.idoutlet = '. $sess_idoutlet);
		}
		$crud->set_group_by('transaction_group.idtransaction_group');
		$crud->add_join('customer', 'left');
		$crud->add_join('outlet');
		$crud->set_select_align(',,,,,,,,right');
		$crud->set_select_decimal('8');
		$crud->add_where("transaction_group.status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by("transaction_group.created desc");
		$crud->set_custom_style('custom_style');
		// echo $crud->get_table();

		$top_filter = $_REQUEST['topfilter'];
		// parse_str($_SERVER['QUERY_STRING'], $str);
		// $top_filter = azarr($str, 'topfilter');
		
    	$arr_filter = array();
    	foreach ($top_filter as $key => $value) {
    		$decode = $this->encrypt->decode($key);
    		$arr_filter[$decode] = $value;
    	}

    	$xdate = explode('~az~', $arr_filter['date']);
    	$start_date = azarr($xdate, 0);
    	$end_date = azarr($xdate, 1);
    	$idoutlet = azarr($arr_filter, 'transaction_group.idoutlet');
    	$status = $arr_filter['transaction_group_status'];
    	$pay = $arr_filter['pay'];
    	$idcustomer = $arr_filter['transaction_group.idcustomer'];
    	$xstatus = explode('~aztpwh~', $status);
    	$xpay = explode('~aztpwh~', $pay);

    	$this->db->where('date(date) >=', Date('Y-m-d', strtotime($start_date)));
    	$this->db->where('date(date) <=', Date('Y-m-d', strtotime($end_date)));
    	if (strlen($idoutlet) > 0) {
    		$this->db->where('transaction_group.idoutlet', $idoutlet);
    	}
    	if (strlen($sess_idoutlet) > 0) {
    		$this->db->where('transaction_group.idoutlet', $sess_idoutlet);
    	}
    	if (strlen($idcustomer) > 0) {
    		$this->db->where('transaction_group.idcustomer', $idcustomer);
    	}
    	if (count($xstatus) > 1) {
    		if (strlen(azarr($xstatus, 1)) > 0) {
    			$this->db->where('transaction_group_status', azarr($xstatus, 1));
    		}
    	}
    	if (count($xpay) > 1) {
    		if (strlen(azarr($xpay, 1)) > 0) {
    			$this->db->where('pay', azarr($xpay, 1));
    		}
    	}
    	$this->db->select("sum(grand_total_final) as total");
    	$this->db->where('transaction_group.status', 1);
    	$data_transaction = $this->db->get('transaction_group');
    	$total = $data_transaction->row()->total;

    	$this->load->helper('az_core');
    	$total = azlang('Total').': Rp '.az_thousand_separator_decimal($total);

    	$table = $crud->get_table();
    	$table = json_decode($table, true);
    	$arr_total = array('total' => $total);
    	$arr_return = array_merge($table, $arr_total);

		echo json_encode($arr_return);
	}

	function custom_style($key, $value, $data) {
		if ($key == 'transaction_group_status') {
			switch ($value) {
				case 'NEW':
					$lbl = 'primary';
					break;
				case 'PROGRESS':
					$lbl = 'info';
					break;
				case 'FINISH':
					$lbl = 'warning';
					break;
				case 'ACCEPTED':
					$lbl = 'success';
					break;				
				default:
					$lbl = 'default';
					break;
			}
			return "<label class='label label-".$lbl."'>".azlang($value)."</label>";
		}

		if ($key == 'pay') {
			switch ($value) {
				case 'PAID':
					$lbl = 'success';
					break;
				case 'NOT PAID YET':
					$lbl = 'danger';
					break;
				
				default:
					$lbl = 'default';
					break;
			}
			return "<label class='label label-".$lbl."'>".azlang($value)."</label>";
		}

		$idtransaction_group = azarr($data, 'idtransaction_group');
		if ($key == 'action') {
			$code = azarr($data, 'code');
			$btn = "<button data-code='".$code."' class='btn btn-info btn-detail btn-xs' type='button'><i class='fa fa-file-o'></i> ".azlang('Detail')."</button>";


			$btn_edit = '<button class="btn btn-default btn-xs btn-edit-sales_transaction" data_id="'.$idtransaction_group.'"><span class="glyphicon glyphicon-pencil"></span> '.azlang('Edit').'</button>';

			$btn_process = "";
			if (aznav('role_process_transaction')) {
				$btn_process = '<button class="btn btn-warning btn-xs btn-process-sales_transaction" data_id="'.$idtransaction_group.'"><span class="glyphicon glyphicon-refresh"></span> '.azlang('Process').'</button>';
			}
			if (!aznav('role_edit_transaction')) {
				$btn_edit = '';
			}
			$btn_delete = '<button class="btn btn-danger btn-xs btn-delete-sales_transaction" data_id="'.$idtransaction_group.'"><span class="glyphicon glyphicon-remove"></span> '.azlang('Delete').'</button>';
			if (!aznav('role_delete_transaction')) {
				$btn_delete = '';
			}

			return $btn.$btn_process.$btn_edit.$btn_delete;
		}
		return $value;
	}

	function add() {
		if (!aznav('role_add_transaction')) {
			redirect(app_url());
		}
		$this->edit();
	}

    function edit($id = '') {
    	if (strlen($id) > 0) {
    		if (!aznav('role_edit_transaction')) {
				redirect(app_url());
			}
    	}
		$this->load->helper('az_core');
		$this->load->library('AZApp');
		$app = $this->azapp;

		$date = $app->add_datetime();
		$date->set_id('date');
		$date->set_value(Date('d-m-Y H:i:s'));
		$date->set_name('date');
		$data['date'] = $date->render();

		$duedate = $app->add_datetime();
		$duedate->set_id('duedate');
		$duedate->set_name('duedate');
		$data['duedate'] = $duedate->render();

		$pay_date = $app->add_datetime();
		$pay_date->set_id("pay_date");
		$pay_date->set_name("pay_date");
		$pay_date->add_class('x-focus');
		$pay_date = $pay_date->render();
		$data['pay_date'] = $pay_date;

		$this->load->library('Laundry');
		$v_modal = $this->laundry->get_product();
		$modal = $app->add_modal();
		$modal->set_id('product');
		$modal->set_modal_title(azlang('Product'));
		$modal->set_modal("<div class='table-responsive'>".$v_modal."</div>");
		$modal = $modal->render();

		$app->add_content($modal);

		$v_customer = $this->load->view('sales_transaction/v_customer', '', true);
		$modal_customer = $app->add_modal();
		$modal_customer->set_id('customer');
		$modal_customer->set_action_modal(array('save_customer' => azlang('Save')));
		$modal_customer->set_modal_title(azlang('Add Customer'));
		$modal_customer->set_modal($v_customer);
		$modal_customer = $modal_customer->render();

		$app->add_content($modal_customer);


		$view = $this->load->view('sales_transaction/v_sales_transaction', $data, true);
		$app->add_content($view);

		$js = az_add_js('sales_transaction/vjs_sales_transaction');
		$app->add_js($js);

		$title = azlang('Add Sales Transaction');
		if (strlen($id) > 0) {
			$title = azlang('Edit Sales Transaction');
		}

		$data_header['title'] = $title;
		$data_header['breadcrumb'] = array('transaction', 'sales_transaction');
		$app->set_data_header($data_header);
		echo $app->render();
	}

	public function save($type = ''){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idoutlet', azlang('Outlet'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('idcustomer', azlang('Customer'), 'required|trim|max_length[200]');

		$pay = $this->input->post('pay');
		if ($pay == 'PAID') {
			$this->form_validation->set_rules('pay_date', azlang('Pay Date'), 'required|trim|max_length[200]');
		}

		$err_code = 0;
		$err_message = '';
		$invoice = '';

		if($this->form_validation->run() == TRUE){
			$idproduct = $this->input->post('idproduct');
			$qty = $this->input->post('qty');
			$discount = $this->input->post('discount');
			$add_cost = $this->input->post('add_cost');
			$tax = $this->input->post('tax');
			$price = $this->input->post('price');
			$total = $this->input->post('total');
			$idtransaction = $this->input->post('idtransaction');

			$price = az_crud_number($price);
			$total = az_crud_number($total);
			$discount = az_crud_number($discount);
			$add_cost = az_crud_number($add_cost);
			$tax = az_crud_number($tax);
			$qty = az_crud_number($qty);

			$detail_description = $this->input->post('detail_description');
			$detail_qty = $this->input->post('detail_qty');
			$detail_idtransaction_detail = $this->input->post('idtransaction_detail');

			$date = Date('Y-m-d H:i:s', strtotime($this->input->post('date')));
			
			$info_discount = az_crud_number($this->input->post('info_discount'));
			$info_discount_percent = az_crud_number($this->input->post('info_discount_percent'));
			$info_add_cost = az_crud_number($this->input->post('info_add_cost'));
			$info_tax = az_crud_number($this->input->post('info_tax'));
			$info_tax_percent = az_crud_number($this->input->post('info_tax_percent'));

			$info_grand_total = az_crud_number($this->input->post('info_grand_total'));
			$info_total_final = az_crud_number($this->input->post('info_total_final'));

			$idcustomer = $this->input->post('idcustomer');
			$idoutlet = $this->input->post('idoutlet');
			if (strlen($this->session->userdata('idoutlet')) > 0) {
				$idoutlet = $this->session->userdata('idoutlet');
			}
			
			$data_save = array(
				'idoutlet' => $idoutlet,
				'idcustomer' => $idcustomer,
				'date' => $date,
				'duedate' => az_crud_date($this->input->post('duedate')),
				'pay_date' => az_crud_date($this->input->post('pay_date')),
				'note' => $this->input->post('info_note'),
				'iduser' => $this->session->userdata('iduser'),
				'grand_total' => $info_grand_total,
				'grand_discount' => $info_discount,
				'grand_discount_percent' => $info_discount_percent,
				'grand_add_cost' => $info_add_cost,
				'grand_tax' => $info_tax,
				'grand_tax_percent' => $info_tax_percent,
				'grand_total_final' => $info_total_final,
				'transaction_group_status' => $this->input->post('transaction_group_status'),
				'pay' => $this->input->post('pay'),
			);

			if (strlen($idpost) == 0) {
				$invoice = $this->generate_code($idoutlet);
				$data_save['code'] = $invoice;
			}
			else {
				$this->db->where('idtransaction_group', $idpost);
				$invoice = $this->db->get('transaction_group')->row()->code;
			}

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');

			if ($err_code == 0) {
				foreach ((array)$idproduct as $key => $value) {
					if (strlen($value) == 0) {
						continue;
					}

					$arr_transaction = array(
						'idtransaction_group' => $insert_id, 
						'idproduct' => $value, 
						'qty' => azarr($qty, $key), 
						'price' => azarr($price, $key), 
						'discount' => azarr($discount, $key), 
						'add_cost' => azarr($add_cost, $key), 
						'tax' => azarr($tax, $key), 
						'total' => azarr($total, $key),
					);

					$response_save = az_crud_save(azarr($idtransaction, $key), 'transaction', $arr_transaction);
					$err_code = azarr($response_save, 'err_code');
					$err_message = azarr($response_save, 'err_message');
				}
			}

			if ($err_code == 0) {
				foreach ((array)$detail_description as $key => $value) {
					if (strlen($value) == 0) {
						continue;
					}
					$arr_description = array(
						'idtransaction_group' => $insert_id,
						'detail_description' => $value,
						'detail_qty' => azarr($detail_qty, $key),
					);

					$response_save = az_crud_save(azarr($detail_idtransaction_detail, $key), 'transaction_detail', $arr_description);
					$err_code = azarr($response_save, 'err_code');
					$err_message = azarr($response_save, 'err_message');
				}
			}

			if ($err_code == 0) {
				$x_remove_tr = $this->input->post('x_transaction');
				$x_data = explode(',', $x_remove_tr);
				foreach ($x_data as $key => $value) {
					if (strlen($value) > 0) {
						$this->db->where('idtransaction', $value);
						$this->db->delete('transaction');
					}
				}

				$x_remove_tr = $this->input->post('x_transaction_detail');
				$x_data = explode(',', $x_remove_tr);
				foreach ($x_data as $key => $value) {
					if (strlen($value) > 0) {
						$this->db->where('idtransaction_detail', $value);
						$this->db->delete('transaction_detail');
					}
				}
			}
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}
		
		$invoice = urlencode($invoice);
		$data["err_message"] = $err_message;
		$data["err_code"] = $err_code;
		$data['invoice'] = $invoice;
		echo json_encode($data);
	}

	function generate_code($idoutlet) {
		$this->load->helper('az_core');

		$this->db->where('idoutlet', $idoutlet);
		$inv = $this->db->get('outlet')->row()->outlet_code;

		$this->db->where("idoutlet", $idoutlet);
		$this->db->order_by('convert(substring(code, 13), unsigned integer) desc');
		$last = $this->db->get("transaction_group", 1);
		
		if ($last->num_rows() == 0) {
			$num = '0001';
		}
		else {
			$code = $last->row()->code;
			$xcode = explode('/', $code);
			$is_inc = false;
			if ($xcode[0] == Date('Y') && $xcode[1] == Date('m')) {
				$is_inc = true;
			}
			$code_num = 1;
			$is_inc = true;
			if ($is_inc) {
				$code_num = $xcode[3];
				$code_num++;
			}
			
			$num = sprintf('%04d', $code_num);
		}
		
		$transaction_group_code = Date("Y/m").'/'.$inv.'/'.$num;

		return $transaction_group_code;
	}

	function invoice() {
		$this->load->library('Laundry');
		$code = $this->input->get('c');
		$type = $this->input->get('t');
		$code = urldecode($code);
		$return = $this->laundry->invoice($code);
		$err_code = azarr($return, 'err_code');
		if ($err_code == 0) {
			$this->load->helper('az_config');
			if ($type == 'small') {
				$this->load->view('sales_transaction/v_invoice_small', $return);
			}
			else {
				$this->load->view('sales_transaction/v_invoice', $return);
			}
		}
		else {
			redirect(app_url());
		}
	}

	function get_edit() {
		$err_code = 0;
		$err_message = "";
		$data_group = array();
		$data_transaction = array();
		$data_detail = array();

		$idoutlet = $this->session->userdata('idoutlet');

		$id = $this->input->post('id');
		$this->db->select('*, transaction_group.idoutlet, outlet_name as ajax_idoutlet, transaction_group.idcustomer, customer_name as ajax_idcustomer, grand_total as info_total, grand_add_cost as info_add_cost, grand_discount as info_discount, grand_discount_percent as info_discount_percent, grand_tax as info_tax, grand_tax_percent as info_tax_percent, grand_total_final as info_total_final, note as info_note');
		$this->db->join('outlet', 'transaction_group.idoutlet = outlet.idoutlet');
		$this->db->join('customer', 'transaction_group.idcustomer = customer.idcustomer');
		$this->db->where('idtransaction_group', $id);
		$this->db->where('transaction_group.status', 1);
		if (strlen($idoutlet) > 0) {
			$this->db->where('transaction_group.idoutlet', $idoutlet);
		}
		$data = $this->db->get('transaction_group');

		if ($data->num_rows() == 0) {
			$err_code++;
			$err_message = 'Data not found';
		}

		if ($err_code == 0) {
			$data_group = azarr($data->result_array(), 0);
			$this->db->select('*, product_type, product_name');
			$this->db->join('product', 'transaction.idproduct = product.idproduct');
			$this->db->where('idtransaction_group', $id);
			$this->db->where('transaction.status', 1);
			$data_transaction = $this->db->get('transaction')->result_array();

			$data_transaction_lang = array();
			foreach ($data_transaction as $key => $value) {
				$arr_val = array();
				foreach ($value as $vkey => $vvalue) {
					if ($vkey == 'product_type') {
						$arr_val[$vkey] = azlang($vvalue);
					}
					else {
						$arr_val[$vkey] = $vvalue;
					}
				}
				$data_transaction_lang[$key] = $arr_val;
			}

			$this->db->where('idtransaction_group', $id);
			$this->db->where('transaction_detail.status', 1);
			$data_detail = $this->db->get('transaction_detail')->result_array();
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'data' => $data_group,
			'data_transaction' => $data_transaction_lang,
			'data_detail' => $data_detail
		);

		echo json_encode($return);
	}

	function get_invoice() {
		$this->load->helper('az_core');
		$code = $this->input->post('code');
		$this->load->library('Laundry');
		$data = $this->laundry->invoice($code);

		$err_code = azarr($data, 'err_code');
		$err_message = azarr($data, 'err_message');

		$this->load->helper('az_config');
		$view = $this->load->view('sales_transaction/v_invoice', $data, true);

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'data' => $view
		);

		echo json_encode($return);
	}

	function print_report() {
		$date_1 = $this->input->post('date_1');
		$date_2 = $this->input->post('date_2');
		$idoutlet = $this->input->post('idoutlet');
		$status = $this->input->post('status');
		$idcustomer = $this->input->post('idcustomer');
		$pay = $this->input->post('pay');

		$data['date_1'] = $date_1;
		$data['date_2'] = $date_2;

		$this->db->where('transaction_group.status', 1);
		$this->db->where('date >= ', Date('Y-m-d', strtotime($date_1)).' 00:00:00');
		$this->db->where('date <= ', Date('Y-m-d', strtotime($date_2)).' 23:59:59');
		if (strlen($idoutlet) > 0) {
			$this->db->where('transaction_group.idoutlet', $idoutlet);
		}
		if (strlen($idcustomer) > 0) {
			$this->db->where('transaction_group.idcustomer', $idcustomer);
		}
		if (strlen($status) > 0) {
			$this->db->where('transaction_group_status', $status);
		}
		if (strlen($pay) > 0) {
			$this->db->where('pay', $pay);
		}
		$this->db->join('outlet', 'transaction_group.idoutlet = outlet.idoutlet');
		$this->db->join('customer', 'transaction_group.idcustomer = customer.idcustomer', 'left');
		$data['data'] = $this->db->get('transaction_group');
		$this->load->view('sales_transaction/v_print_sales_transaction', $data);
	}

	public function delete() {
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}

	public function save_customer(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'idcustomer');
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

			$response_save = az_crud_save($idpost, 'customer', $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
			$data["idcustomer"] = $insert_id;
			$data["customer_name"] = $this->input->post('customer_name');
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	function process() {
		$idtransaction_group = $this->input->post('idtransaction_group');
		$pay = $this->input->post('pay');
		$pay_date = $this->input->post('pay_date');
		$transaction_group_status = $this->input->post('transaction_group_status');
		$err_code = 0;
		$err_message = '';

		if ($pay == 'PAID') {
			if (strlen($pay_date) == 0) {
				$err_code++;
				$err_message = azlang("Pay Date Required");
			}
		}

		if ($err_code == 0) {
			$arr_data = array(
				'pay' => $pay,
				'pay_date' => az_crud_date($pay_date),
				'transaction_group_status' => $transaction_group_status
			);

			if ($pay == 'NOT PAID YET') {
				$arr_data['pay_date'] = NULL;
			}

			$this->db->where('idtransaction_group', $idtransaction_group);
			$this->db->update('transaction_group', $arr_data);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message
		);

		echo json_encode($return);
	}

	function get_detail() {
		$id = $this->input->post('id');
		$this->db->select('pay, date_format(pay_date, "%d-%m-%Y %H:%i:%s") as pay_date, transaction_group_status');
		$this->db->where('idtransaction_group', $id);
		$data = $this->db->get('transaction_group')->result_array();

		echo json_encode($data[0]);
	}
}
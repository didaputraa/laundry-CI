<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laundry {
	function get_product() {
		$ci =& get_instance();

		$ci->load->library('AZApp');
		$azapp = $ci->azapp;

		$crud = $azapp->add_crud();
		$crud->set_column(array(azlang('No'), azlang('Product Type'), azlang('Product Code'), azlang('Product Name'), azlang('Sell Price'), azlang('Action')));
		$crud->set_id("product");
		$crud->set_url("app_url+'data/get_data_product'");
		$crud->set_url_edit("app_url+'data/edit_data_product'");
		$crud->set_url_save("app_url+'data/save_data_product'");
		$crud->set_url_delete("app_url+'data/delete_data_product'");

		$crud->add_aodata('idoutlet', 'idoutlet');
		$crud->add_aodata('idcustomer', 'idcustomer');
		$crud->set_btn_add(false);

		$crud = $crud->render();

		return $crud;
	}

	function invoice($code) {
		$ci =& get_instance();
		$err_code = 0;
		$err_message = "";
		$arr_data = array();
		$data_transaction = array();
		$data_transaction_detail = array();

		$idoutlet = $ci->session->userdata('idoutlet');

		$ci->db->select('*, customer.address as customer_address, customer.phone as customer_phone');
		if (strlen($idoutlet) > 0) {
			$ci->db->where('transaction_group.idoutlet', $idoutlet);
		}
		$ci->db->where('code', $code);
		$ci->db->join('customer', 'transaction_group.idcustomer = customer.idcustomer', 'left');
		$ci->db->where('transaction_group.status', 1);
		$data = $ci->db->get('transaction_group');

		if ($data->num_rows() == 0) {
			$err_code++;
			$err_message = azlang('Data not found');
		}

		if ($err_code == 0) {
			$ci->db->where('idtransaction_group', $data->row()->idtransaction_group);
			$ci->db->where('transaction.status', 1);
			$ci->db->join('product', 'transaction.idproduct = product.idproduct');
			$transaction = $ci->db->get('transaction');

			$ci->db->where('idtransaction_group', $data->row()->idtransaction_group);
			$ci->db->where('transaction_detail.status', 1);
			$transaction_detail = $ci->db->get('transaction_detail');

			$arr_data = azarr($data->result_array(), 0);
			$data_transaction = $transaction->result_array();
			$data_transaction_detail = $transaction_detail->result_array();
		}

		if ($err_code == 0) {
			$ci->db->where('idoutlet', $data->row()->idoutlet);
			$data_outlet = $ci->db->get('outlet')->result_array();
			$data_outlet = azarr($data_outlet, 0);
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'data' => $arr_data,
			'transaction' => $data_transaction,
			'transaction_detail' => $data_transaction_detail,
			'outlet' => $data_outlet
		);

		return $return;
	}

	function get_customer($class = '', $id = '', $parent = 'idoutlet') {
		$ci =& get_instance();

		$ci->load->library('AZApp');
		$ci->load->helper('az_core');
		$azapp = $ci->azapp;

		$crud = $azapp->add_crud();

		$crud->add_aodata('idoutlet', $parent);

		$crud->set_column(array('No', azlang('Code'), azlang('Name'), azlang('Address'), azlang('Email'), azlang('Phone'), azlang('Action')));
		$crud->set_id("customer");
		$crud->set_url("app_url+'data_customer/get_data_customer'");
		$crud->set_url_edit("app_url+'data_customer/edit_data_customer'");
		$crud->set_url_save("app_url+'data_customer/save_data_customer'");
		$crud->set_url_delete("app_url+'data_customer/delete_data_customer'");
		$crud->set_width(',,,,,80px');

		$crud->set_btn_add(false);

		$crud = $crud->render();

		$modal = $azapp->add_modal();
		$modal->set_id('lcustomer');
		$modal->set_modal_title('Member');
		$modal->set_modal($crud);
		$ret = $modal->render();

		$the_id = 'idcustomer';
		if (strlen($id) > 0) {
			$the_id = $id.'.'.$the_id;
		}

		$ci->load->library('encrypt');

		$data['data_id'] = $ci->encrypt->encode($the_id);
		$data['data_class'] = $class;

		$txt = $ci->load->view('lcustomer/v_lcustomer', $data, true);

		$js = az_add_js('lcustomer/vjs_lcustomer');
		$azapp->add_js($js);

		$ret .= $txt;

		return $ret;
	}

}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data_customer extends CI_Controller {
	function get_data_customer() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$idoutlet = $this->input->get('idoutlet');

		$crud->set_select('idcustomer, customer_code, customer_name, address, email, phone, "" as btn');
		$crud->set_select_table('idcustomer, customer_code, customer_name, address, email, phone, btn');
		$crud->set_filter('customer_code, customer_name, address, email, phone');
		$crud->set_sorting('customer_code, customer_name, address, email, phone');
		$crud->set_id('customer');
		$crud->add_where('idoutlet = "'.$idoutlet.'"');
		$crud->add_where("customer.status > 0");
		$crud->set_table('customer');
		$crud->set_order_by('customer_code');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		if ($key == 'btn') {
			$idcustomer = azarr($data, 'idcustomer');
			$customer_code = azarr($data, 'customer_code');
			$customer_name = azarr($data, 'customer_name');
			$name = $customer_code.' - '.$customer_name;
			$btn = "<button data-name='".$name."' data-id='".$idcustomer."' class='btn btn-info btn-xs btn-choose-lcustomer' type='button'><i class='fa fa-arrow-down'></i> Pilih</button>";
			return $btn;
		}
		return $value;
	}

	function edit_data_product() {}
	function delete_data_product() {}

	function scanning() {
		$type = $this->input->post('type');
		$code = $this->input->post('code');
		$err_code = 0;
		$err_message = '';

		$this->db->where('customer_code', $code);
		$this->db->where('customer.status', 1);
		$data = $this->db->get('customer');

		$res = '';
		$id = '';
		if ($data->num_rows() == 0) {
			$err_code++;
			$err_message = azlang("Customer not found");
		}
		else {
			$code = $data->row()->customer_code;
			$name = $data->row()->customer_name;
			$res = $code.' - '.$name;
			$id = $data->row()->idcustomer;
		}

		$return = array(
			'err_code' => $err_code,
			'err_message' => $err_message,
			'id' => $id,
			'name' => $res
		);

		echo json_encode($return);
	}
}
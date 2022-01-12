<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Data extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }

	public function get_product_unit(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("name");
		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->select("idproduct_unit as id, name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("product_unit", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("product_unit");
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

	public function get_product_category(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("name");
		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->select("idproduct_category as id, name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("product_category", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("product_category");
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

	public function get_outlet(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("outlet_name");
		if (strlen($q) > 0) {
			$this->db->like("outlet_name", $q);
		}
		$this->db->select("idoutlet as id, outlet_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("outlet", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("outlet_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("outlet");
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

	public function get_product(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get('parent');

		$offset = ($page - 1) * $limit;

		$this->db->where('idoutlet', $parent);
		$this->db->order_by("product_code");
		if (strlen($q) > 0) {
			$this->db->like("product_code", $q);
			$this->db->or_like('product_code', $q);
		}
		$this->db->select("idproduct as id, concat(product_code, ' - ', product_name) as text");
		$this->db->where('status', '1');

		$data = $this->db->get("product", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("product_code", $q);
			$this->db->or_like('product_code', $q);
		}
		$this->db->where('idoutlet', $parent);
		$this->db->where('status', '1');
		$cdata = $this->db->get("product");
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

	public function get_supplier(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("supplier_name");
		if (strlen($q) > 0) {
			$this->db->like("supplier_name", $q);
		}
		$this->db->select("idsupplier as id, supplier_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("supplier", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("supplier_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("supplier");
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

	public function get_customer(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$parent = $sess_idoutlet;
		}

		$offset = ($page - 1) * $limit;

		$this->db->order_by("customer_name");
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("customer_name", $q);
			$this->db->or_like("customer.phone", $q);
			$this->db->or_like("customer.customer_code", $q);
			$this->db->group_end();
		}
		$this->db->where('idoutlet', $parent);
		$this->db->select("idcustomer as id, concat(customer_code, ' - ', customer_name) as text");
		$this->db->where('status', '1');

		$data = $this->db->get("customer", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("customer_name", $q);
			$this->db->or_like("customer.phone", $q);
			$this->db->or_like("customer.customer_code", $q);
			$this->db->group_end();
		}
		$this->db->where('idoutlet', $parent);		
		$this->db->where('status', '1');
		$cdata = $this->db->get("customer");
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

	public function get_data_user(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) > 0) {
			$parent = $sess_idoutlet;
		}

		$offset = ($page - 1) * $limit;

		$this->db->order_by("user.name");
		$this->db->where('idoutlet', $parent);		
		if (strlen($q) > 0) {
			$this->db->like("user.name", $q);
		}
		$this->db->select("iduser as id, user.name as text");
		$this->db->where('user.status', '1');
		$this->db->join('role', 'user.idrole = role.idrole');
		$this->db->where('role.name', 'cashier');

		$data = $this->db->get("user", $limit, $offset);
		$this->db->where('idoutlet', $parent);
		if (strlen($q) > 0) {
			$this->db->like("user.name", $q);
		}
		$this->db->join('role', 'user.idrole = role.idrole');
		$this->db->where('role.name', 'cashier');
		$this->db->where('user.status', '1');
		$cdata = $this->db->get("user");
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
	
	function get_data_product () {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();

		$idoutlet = $this->input->get('idoutlet');
		$crud->add_where("idoutlet = '".$idoutlet."'");
		$crud->set_select('idproduct, product_type, product_code, product_name, sell_price');
		$crud->set_filter('product_type,product_code,product_name,sell_price');
		$crud->set_sorting('product_code, product_name, unit_name, category_name, sell_price');
		$crud->set_id('product');
		$crud->add_where("product.status > 0");
		$crud->set_table('product');
		$crud->set_order_by('product.product_code');
		$crud->set_custom_style('custom_style_product');
		$crud->set_edit(false);
		$crud->set_delete(false);
		$crud->set_select_decimal('3');
		$crud->set_select_align(',,,right');
		echo $crud->get_table();
	}

	function custom_style_product($key, $value, $data) {
		$idproduct = azarr($data, 'idproduct');
		$product_type = azarr($data, 'product_type');
		$price = azarr($data, 'sell_price');
		$product_name = azarr($data, 'product_name');
		$product_type = azlang($product_type);

		if ($key == 'product_type') {
			return azlang($value);
		}

		if ($key == 'action') {
			return "<button data-name='".$product_name."' data-type='".$product_type."' data-price='".$price."' data-id='".$idproduct."' class='btn btn-info btn-sm btn-choose-product' type='button'><i class='fa fa-arrow-down'></i> ".azlang('Choose')."</button>";
		}
		return $value;
	}

	function edit_data_product() {}
	function delete_data_product() {}

	public function get_data_outlay_type(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("outlay_type_name");
		if (strlen($q) > 0) {
			$this->db->like("outlay_type_name", $q);
		}
		$this->db->select("idoutlay_type as id, outlay_type_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("outlay_type", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("outlay_type_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("outlay_type");
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

	public function get_data_invoice(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get('parent');

		$offset = ($page - 1) * $limit;

		$this->db->order_by("transaction_group.date", "desc");
		$this->db->join('customer', 'transaction_group.idcustomer = customer.idcustomer', 'left');
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("code", $q);
			$this->db->or_like('customer_name', $q);
			$this->db->group_end();
		}
		$this->db->where('transaction_group_status', 'BELUM LUNAS');
		$this->db->where('transaction_group.idoutlet', $parent);
		$this->db->select("idtransaction_group as id, concat(code, ' - ', ifnull(customer_name, '')) as text");
		$this->db->where('transaction_group.status', '1');

		$data = $this->db->get("transaction_group", $limit, $offset);

		$this->db->join('customer', 'transaction_group.idcustomer = customer.idcustomer', 'left');
		if (strlen($q) > 0) {
			$this->db->group_start();
			$this->db->like("code", $q);
			$this->db->or_like('customer_name', $q);
			$this->db->group_end();
		}
		$this->db->where('transaction_group_status', 'BELUM LUNAS');
		$this->db->where('transaction_group.idoutlet', $parent);
		$this->db->where('transaction_group.status', '1');
		$cdata = $this->db->get("transaction_group");
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

	public function get_data_employee(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("employee_name");
		if (strlen($q) > 0) {
			$this->db->like("employee_name", $q);
		}
		if (isset($parent) > 0) {
			$this->db->where('idoutlet', $parent);
		}
		$this->db->select("idemployee as id, employee_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("employee", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("employee_name", $q);
		}
		if (isset($parent) > 0) {
			$this->db->where('idoutlet', $parent);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("employee");
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

	public function get_nota(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");

		$offset = ($page - 1) * $limit;

		$this->db->order_by("code");
		if (strlen($q) > 0) {
			$this->db->like("code", $q);
		}
		$this->db->select("idtransaction_group as id, code as text");
		$this->db->where('status', '1');
		$this->db->where_in('pay', array('DP PAID', 'NOT PAID YET'));
		$data = $this->db->get("transaction_group", $limit, $offset);

		if (strlen($q) > 0) {
			$this->db->like("code", $q);
		}
		$this->db->where('status', '1');
		$this->db->where_in('pay', array('DP PAID', 'NOT PAID YET'));
		$cdata = $this->db->get("transaction_group");
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

	public function get_data_agent(){
		$limit = 20;
		$q = $this->input->get("term");
		$page = $this->input->get("page");
		$parent = $this->input->get("parent");

		$offset = ($page - 1) * $limit;

		$this->db->where('idoutlet', $parent);
		$this->db->order_by("agent_name");
		if (strlen($q) > 0) {
			$this->db->like("agent_name", $q);
		}
		$this->db->select("idagent as id, agent_name as text");
		$this->db->where('status', '1');

		$data = $this->db->get("agent", $limit, $offset);

		$this->db->where('idoutlet', $parent);
		if (strlen($q) > 0) {
			$this->db->like("agent_name", $q);
		}
		$this->db->where('status', '1');
		$cdata = $this->db->get("agent");
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
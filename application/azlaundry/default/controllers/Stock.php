<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stock extends CI_Controller {
	protected $table;

	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('stock');
        $this->table = 'stock';
        $this->controller = 'stock';
        $this->load->helper('az_crud');
        $this->load->helper('az_core');
    }

    public function out() {
    	$this->index("out");
    }

	public function index($type = ""){
		$this->load->library('AZApp');
		$azapp = $this->azapp;

		$crud = $azapp->add_crud();

		$column = array('#', azlang('Date'), azlang('Outlet'), azlang('Product Name'), azlang('Stock Name'), azlang('Detail'), azlang('Total (Stock)'), azlang('Action'));
		$crud->set_column($column);
		$crud->set_id($this->table);
		
		$crud->set_url("app_url+'stock/get'");
		$crud->set_url_save("app_url+'stock/save'");
		$crud->set_url_edit("app_url+'stock/edit'");
		if ($type == "out") {
			$crud->set_url("app_url+'stock/get/?t=out'");
			$crud->set_url_save("app_url+'stock/save/out'");
			$crud->set_url_edit("app_url+'stock/edit/out'");
		}

		$crud->set_url_delete("app_url+'stock/delete'");
		$crud->set_form('form');
		
		
		$data_product['stock_type'] = $type;

		$datetime = $azapp->add_datetime();
		$datetime->set_id('datetime');
		$datetime->add_class('x-hidden');
		$datetime->set_name('datetime');
		$data_product['datetime'] = $datetime->render();

		$v_modal = $this->load->view('stock/v_stock', $data_product, true);
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang('Stock in'));
		if ($type == "out") {
			$crud->set_modal_title(azlang('Stock out'));
		}
	
		$v_js = $this->load->view('stock/vjs_stock', $data_product, true);
		$v_js = str_replace('<script>', '', $v_js);
		$azapp->add_js($v_js);

		$v_filter = $this->load->view('stock/v_filter_stock', '', true);
		$crud->set_top_filter($v_filter);

		$v_view = $crud->render();
		$v_modal = $crud->generate_modal();
		$v_view .= $v_modal;
		$azapp->add_content($v_view);

		$data_header['title'] = azlang('Stock In');
		$data_header['breadcrumb'] = array('stock', 'stock_in');
		if ($type == 'out') {
			$data_header['title'] = azlang('Stock Out');
			$data_header['breadcrumb'] = array('stock', 'stock_out');
		}
		$azapp->set_data_header($data_header);

		echo $azapp->render();
	}

	public function get() {
		$this->load->library("AZAppCRUD");
		$crud = $this->azappcrud;

		$get_data = $_GET;
		$stock_type = azarr($get_data, "t");

    	$crud->set_select("id".$this->table.", date_format(datetime, '%d-%m-%Y %H:%i:%s') as datetime, outlet_name, concat(product_code, ' - ',  product_name) as product_name, stock_name, stock.description, total");
    	$crud->set_select_table('idstock, datetime, outlet_name, product_name, stock_name, description, total');
    	$crud->set_select_number("5");
    	$crud->set_select_align(',,,,,right');
    	$crud->add_join("product");
    	$crud->add_join('outlet', 'inner', 'product', 'idoutlet');

    	if ($stock_type == "out") {
    		$crud->add_where("type = 'out'");
    	}
    	else {
    		$crud->add_where("type = 'in'");
    	}

    	$crud->set_filter('product_name');
    	$crud->set_table($this->table);
    	$crud->set_sorting('datetime, outlet_name, product_code, product_name, stock_name, description, total');
    	$crud->set_id($this->table);
    	$crud->set_order_by('datetime~desc');

		echo $crud->get_table();
	}

	public function edit() {
		$id = $this->input->post("id");
		$this->db->select('idstock, stock.idproduct, product_name as ajax_idproduct, product.idoutlet, outlet_name as ajax_idoutlet, date_format(datetime, "%d-%m-%Y") as datetime, type, stock_name, stock.description, stock.total, stock.idsupplier, supplier_name as ajax_idsupplier');
		$this->db->join("product", "product.idproduct = stock.idproduct");
		$this->db->join('outlet', 'product.idoutlet = outlet.idoutlet');
		$this->db->join('supplier', 'stock.idsupplier = supplier.idsupplier', 'left');
		$this->db->where("id".$this->table, $id);

		$rdata = $this->db->get($this->table)->result_array();
		echo json_encode($rdata);
	}

	public function save($type = ""){
		$data = array();
		$data["sMessage"] = "";
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$this->form_validation->set_rules('idoutlet', azlang('Outlet Name'), 'required|trim|numeric');
		$this->form_validation->set_rules('idproduct', azlang('Product Name'), 'required|trim|numeric');
		$this->form_validation->set_rules('stock_name', azlang('Stock Name'), 'required|trim');
		$this->form_validation->set_rules('datetime', azlang('Stock Date'), 'required|trim');
		$this->form_validation->set_rules('total', azlang('Total Stock'), 'required|trim');

		$data_post = $this->input->post();
		$err_code = "";
		$err_message = "";

		$total_stock = azarr($data_post, "total");
		$total_stock = str_replace(".", "", $total_stock);

		if ($err_code == 0) {
			if($this->form_validation->run() == TRUE){
				if (!is_numeric($total_stock)) {
					$err_code++;
					$err_message = azlang('Invalid Stock Total');
				}
				if ($err_code == 0) {
					if ($total_stock < 1) {
						$err_code++;
						$err_message = azlang('Stock must more than 0');
					}
				}
				if ($err_code == 0) {
					if (az_check_date(azarr($data_post, 'datetime')) == FALSE) {
						$err_code++;
						$err_message = azlang('Wrong date format');
					}
				}
			}
			else {
				$err_code++;
			}
		}

		if ($err_code == 0) {
			$idpost = $data_post['id'.$this->table];

			$stock_type = "in";
			if ($type == "out") {
				$stock_type = "out";
			}

			$data_save = array(
				"idproduct" => azarr($data_post, "idproduct"),
				"datetime" => Date("Y-m-d H:i:s", strtotime(azarr($data_post, "datetime"))),
				"type" => $stock_type,
				"stock_name" => azarr($data_post, "stock_name"),
				"description" => azarr($data_post, "description"),
				"total" => $total_stock,
				"updated" => Date("Y-m-d H:i:s"),
				"updatedby" => $this->session->userdata("username")
			);

			$idsupplier = azarr($data_post, "idsupplier");
			if (strlen($idsupplier) > 0) {
				$data_save["idsupplier"] = $idsupplier;
			}

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}

		$data["sMessage"] = validation_errors().$err_message;
		echo json_encode($data);
	}

	public function delete() {
		$id = $this->input->post("id");

		if (is_array($id)) {
			$this->db->where_in("id".$this->table, $id);
		}
		else {
			$this->db->where("id".$this->table, $id);
		}

		$this->db->delete($this->table);

		echo json_encode(array("SUCCESS"));
	}
}
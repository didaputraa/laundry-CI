<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Product extends CI_Controller {
	public function __construct() {
        parent::__construct();

        $this->load->helper('az_auth');
        az_check_auth('product');
        $this->table = 'product';
        $this->controller = 'product';
        $this->load->helper('az_crud');
    }

	public function index(){
		$this->load->library('AZApp');
		$azapp = $this->azapp;
		$crud = $azapp->add_crud();

		$crud->set_column(array('#', azlang('Outlet'), azlang('Product Type'), azlang('Code'), azlang('Name'), azlang('Sell Price'), azlang('Action')));
		$crud->set_id($this->controller);
		$crud->set_default_url(true);

		$this->db->where('status', 1);
		$data['outlet'] = $this->db->get('outlet');

		if (strlen($this->session->userdata('idoutlet')) == 0) {
			$v_filter = $this->load->view('product/v_filter_product', '', true);
			$crud->set_top_filter($v_filter);			
		}

		$v_modal = $this->load->view('product/v_product', $data, true);
		$crud->set_form('form');
		$crud->set_modal($v_modal);
		$crud->set_modal_title(azlang("Product"));
		$v_modal = $crud->generate_modal();

		$this->load->helper('az_core');
		$js = az_add_js('product/vjs_product');
		$azapp->add_js($js);
		
		$crud = $crud->render();
		$crud .= $v_modal;	
		$azapp->add_content($crud);

		$data_header['title'] = azlang('Product');
		$data_header['breadcrumb'] = array('master', 'product');
		$azapp->set_data_header($data_header);
		
		echo $azapp->render();	
	}

	public function get() {
		$this->load->library('AZApp');
		$crud = $this->azapp->add_crud();
		$crud->set_select('idproduct, outlet_name, product_type, product_code, product_name, sell_price');
		$crud->set_filter('outlet_name,product_type,product_code,product_name,sell_price');
		$crud->set_sorting('outlet_name, product_type, product_code, product_name, sell_price');
		$idoutlet = $this->session->userdata('idoutlet');
		if (strlen($idoutlet) > 0) {
			$crud->add_where("product.idoutlet = ".$idoutlet);
		}
		$crud->set_id($this->controller);
		$crud->add_where("product.status > 0");
		$crud->set_table($this->table);
		$crud->set_order_by('product_name');
		$crud->add_join('outlet');
		$crud->set_select_align(',,,,right');
		$crud->set_select_decimal('4');
		$crud->set_custom_style('custom_style');
		echo $crud->get_table();
	}

	function custom_style($key, $value, $data) {
		if ($key == 'product_type') {
			return azlang($value);
		}
		return $value;
	}

	public function save(){
		$data = array();
		$data_post = $this->input->post();
		$idpost = azarr($data_post, 'id'.$this->table);
		$data['sMessage'] = '';
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('', '');

		$idoutlet = $this->input->post('idoutlet');
		$sess_idoutlet = $this->session->userdata('idoutlet');
		if (strlen($sess_idoutlet) == 0) {
			$this->form_validation->set_rules('idoutlet', azlang('Outlet'), 'required|trim|max_length[200]');
		}
		else {
			$idoutlet = $sess_idoutlet;
		}

		$this->form_validation->set_rules('product_code', azlang('Code'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('product_name', azlang('Name'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('product_type', azlang('Product Type'), 'required|trim|max_length[200]');
		$this->form_validation->set_rules('sell_price', azlang('Sell Price'), 'required|trim|max_length[200]');

		$err_code = 0;
		$err_message = '';

		if($this->form_validation->run() == TRUE){
			$data_save = array(
				'idoutlet' => $idoutlet,
				'product_code' => $this->input->post('product_code'),
				'product_name' => $this->input->post('product_name'),
				'product_type' => $this->input->post('product_type'),
				'sell_price' => az_crud_number($this->input->post('sell_price')),
				'description' => $this->input->post('description'),
			);

			$response_save = az_crud_save($idpost, $this->table, $data_save);
			$err_code = azarr($response_save, 'err_code');
			$err_message = azarr($response_save, 'err_message');
			$insert_id = azarr($response_save, 'insert_id');
		}
		else {
			$err_code++;
			$err_message = validation_errors();
		}

		$data["sMessage"] = $err_message;
		echo json_encode($data);
	}

	public function edit() {
		$this->db->join('outlet', 'outlet.idoutlet = product.idoutlet');
		az_crud_edit('idproduct, product.idoutlet, product_code, product_name, product_type, sell_price, description');
	}

	public function delete() {
		$id = $this->input->post('id');
		az_crud_delete($this->table, $id);
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');   
    
    if(!function_exists('az_select_user')){
        function az_select_user($id = 'user', $attr='', $parent='idoutlet') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_id($id);
            $select->set_select_parent($parent);
            $select->set_url('data/get_data_user');
            $select->set_placeholder(azlang('Select User'));
            
            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.iduser'));
                $select->add_class('element-top-filter');
                $select->add_attr('data-w', 'true');
            }
            

            return $select->render();
        }
    }

    if(!function_exists('az_select_employee')){
        function az_select_employee($id = '', $attr = '', $parent = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            if (strlen($parent) > 0) {
                $select->set_select_parent($parent);
            }
            if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('employee');
            }

            $select->set_url('data/get_data_employee');
            $select->set_placeholder(azlang('Select Employee'));

            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idemployee'));
                $select->add_class('element-top-filter');
            }
            
            return $select->render();
        }
    } 

    if(!function_exists('az_select_agent')){
        function az_select_agent($id = '', $attr = '', $parent = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            if (strlen($parent) > 0) {
                $select->set_select_parent($parent);
            }
            if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('agent');
            }

            $select->set_url('data/get_data_agent');
            $select->set_placeholder(azlang('Select Agent'));

            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idagent'));
                $select->add_class('element-top-filter');
            }
            
            return $select->render();
        }
    } 

    if(!function_exists('az_select_product_unit')){
        function az_select_product_unit() {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_id('product_unit');
            $select->set_url('data/get_product_unit');
            $select->set_placeholder(azlang('Select Product Unit'));
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_product_category')){
        function az_select_product_category() {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_id('product_category');
            $select->set_url('data/get_product_category');
            $select->set_placeholder(azlang('Select Product Category'));
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_supplier')){
        function az_select_supplier() {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_id('supplier');
            $select->set_url('data/get_supplier');
            $select->set_placeholder(azlang('Select Supplier'));
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_outlet')){
        function az_select_outlet($id = '', $attr = '', $name = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_url('data/get_outlet');
            $select->set_placeholder(azlang('Select Outlet'));
            if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('outlet');
            }

            if (strlen($name) > 0) {
                $select->set_name($name);
            }

            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idoutlet'));
                $select->add_class('element-top-filter');
                $select->add_attr('data-w', true);
            }
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_customer')){
        function az_select_customer($id = '', $attr = '', $parent ='idoutlet') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_select_parent($parent);
            $select->set_url('data/get_customer');
            $select->set_placeholder(azlang('Select Customer'));
            if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('customer');
            }
            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idcustomer'));
                $select->add_attr('data-w', 'true');
                $select->add_class('element-top-filter');
            }
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_product')){
        function az_select_product($id = '', $attr = '', $parent = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_url('data/get_product');
            $select->set_placeholder(azlang('Select Product'));
             if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('product');
            }
            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idproduct'));
                $select->add_class('element-top-filter');
            }
            if (strlen($parent) == 0) {
                $select->set_select_parent('idoutlet');
            }
            else {
                $select->set_select_parent($parent);   
            }
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_product_stock')){
        function az_select_product_stock($id = '', $attr = '', $parent = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_url('data/get_product_stock');
            $select->set_placeholder(azlang('Select Product'));
             if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('product');
            }
            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idproduct'));
                $select->add_class('element-top-filter');
            }
            if (strlen($parent) == 0) {
                $select->set_select_parent('idoutlet');
            }
            else {
                $select->set_select_parent($parent);   
            }
            
            return $select->render();
        }
    }

    if(!function_exists('az_select_supplier')){
        function az_select_supplier() {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            $select->set_id('supplier');
            $select->set_url('data/get_supplier');
            $select->set_placeholder(azlang('Select Supplier'));
            
            return $select->render();
        }
    }

    if (!function_exists('az_get_stock')) {
        function az_get_stock($idproduct) {
            $ci =& get_instance();
            $ci->db->where('idproduct', $idproduct);
            $ci->db->select('type, sum(total) as total_stock');
            $ci->db->group_by('type');
            $data = $ci->db->get('stock');

            $stock['in'] = 0;
            $stock['out'] = 0;
            foreach ($data->result() as $key => $value) {
                $stock[$value->type] = $value->total_stock;
            }

            $total = $stock['in'] - $stock['out'];

            return $total;
        }
    }

    if(!function_exists('az_select_customer_scan')){
        function az_select_customer_scan($class='', $attr='',$parent = 'idoutlet') {
            $ci =& get_instance();
            $ci->load->library('Laundry');
            $customer = $ci->laundry->get_customer($class, $attr, $parent);
            return $customer;
        }
    }

     if(!function_exists('az_select_outlay_type')){
        function az_select_outlay_type($id = '', $attr = '', $parent = '') {
            $ci =& get_instance();
            $azapp = $ci->load->library('AZApp');
            $select = $ci->azapp->add_select2();
            if (strlen($id) > 0) {
                $select->set_id($id);
            }
            else {
                $select->set_id('outlay_type');
            }

            $select->set_url('data/get_data_outlay_type');
            $select->set_placeholder(azlang('Select OutlayType'));

            if (strlen($attr) > 0) {
                $select->add_attr('data-id', $ci->encrypt->encode($attr.'.idoutlay_type'));
                $select->add_class('element-top-filter');
            }
            
            return $select->render();
        }
    } 
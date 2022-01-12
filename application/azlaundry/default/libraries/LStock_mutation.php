<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LStock_mutation {
	function update_stock($data) {
		$ci =& get_instance();
		$idoutlet = azarr($data, 'idoutlet');
		$iditem = azarr($data, 'iditem');
		$stock_type = azarr($data, 'stock_type');
		$stock_description = azarr($data, 'stock_description');
		$the_total = azarr($data, 'total');

		$data_save = array(
			'idoutlet' => $idoutlet,
			'iditem' => $iditem,
			'stock_mutation_date' => Date('Y-m-d H:i:s'),
			'stock_type' => $stock_type,
			'stock_description' => $stock_description,
			'total' => $the_total,
		);
		
		$ci->db->where('idoutlet', $idoutlet);
		$ci->db->where('iditem', $iditem);
		$ci->db->order_by('stock_mutation_date desc, idstock_mutation desc');
		$stock = $ci->db->get('stock_mutation', 1);
		if ($stock->num_rows() == 0) {
			$last_stock = 0;
		}
		else {
			$last_stock = $stock->row()->stock;
		}

		if ($stock_type == 'IN') {
			$new_stock = $last_stock + $the_total;					
		}
		else {
			$new_stock = $last_stock - $the_total;
			$the_total = '-'.$the_total;
		}

		$data_save['total'] = $the_total;
		$data_save['stock'] = $new_stock;
		
		$response_save = az_crud_save('', 'stock_mutation', $data_save);
		$err_code = azarr($response_save, 'err_code');
		$err_message = azarr($response_save, 'err_message');
		$insert_id = azarr($response_save, 'insert_id');

		$arr_code['stock_mutation_code'] = 'ST'.sprintf('%05d', $insert_id);
		az_crud_save($insert_id, 'stock_mutation', $arr_code);

		$ci->db->where('idoutlet', $idoutlet);
		$ci->db->where('iditem', $iditem);
		$db_stock = $ci->db->get('stock');
		if ($db_stock->num_rows() == 0) {
			$arr_stock = array(
				'idoutlet' => $idoutlet,
				'iditem' => $iditem,
				'total_stock' => $new_stock
			);
			az_crud_save('', 'stock', $arr_stock);
		}
		else {
			$arr_stock['total_stock'] = $new_stock;
			az_crud_save($db_stock->row()->idstock, 'stock', $arr_stock);
		}
	}
}
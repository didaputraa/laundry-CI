<?php
defined('BASEPATH') OR exit('No direct script access allowed');   

    if(!function_exists('az_get_config')){
        function az_get_config($key ='', $table = 'config') {
            $ci = &get_instance();

            $ci->db->where("key", $key);
            $data = $ci->db->get($table);

            $value = '';
            if ($data->num_rows() > 0) {
                $value = $data->row()->value;
            }
            return $value;
        }
    }
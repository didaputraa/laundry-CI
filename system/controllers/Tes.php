<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tes extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }

    public function index() {
    	$this->load->library('AZApp');
    	$app = $this->azapp;

    	$this->load->config('menu');
        $menu = $this->config->item('menu');

        foreach ($menu as $key => $value) {
            $role = azarr($value, 'role', array());
            echo '<pre>';
            print_r($role);
        }
die;
        echo '<pre>';print_r($tes);die;


    	echo $app->render();
    }



}
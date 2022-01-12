<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Autobackup extends CI_Controller {
	public function __construct() {
        parent::__construct();
	   ini_set('memory_limit', '-1');
       ini_set('max_execution_time', 0);
    }

    function check($key) {
    	$sess = $this->session->userdata('username');
    	if ($sess != 'superadmin') {
    		redirect(app_url());
    	}

    	if ($key != 'passwordxxx') {
    		redirect(app_url());
    	}
    }

    function backup($key = '') {
    	$this->check($key);
    	$this->load->dbutil();
    	$prefs = array(
	        'ignore'     => array(),
	        // Daftar table yang tidak akan dibackup
	        'format'     => 'gzip',
	        // gzip, zip, txt format filenya
	        'filename'   => 'mybackup.zip',
	        // Nama file
	        'add_drop'   => true, 
	        // Untuk menambahkan drop table di backup
	        'add_insert' => TRUE,
	        // Untuk menambahkan data insert di file backup
	        'newline'    => "\n",
	        // Baris baru yang digunakan dalam file backup
	        'foreign_key_checks' => false,
		);

        $this->db->save_queries = false;

		$backup = $this->dbutil->backup($prefs);

		$this->load->helper('download');
		force_download('Database_'.Date('Y_m_d_H_i_s').'.zip', $backup);
    }
}

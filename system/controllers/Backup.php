<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Backup extends CI_Controller {
	public function __construct() {
        parent::__construct();
    }

    function download($key = '') {
    	$sess = $this->session->userdata('username');
    	if ($sess != 'superadmin') {
    		redirect(app_url());
    	}

    	if ($key != 'passwordxxx') {
    		redirect(app_url());
    	}

    	$this->load->dbutil();
    	$prefs = array(
	        'ignore'     => array(),
	        // Daftar table yang tidak akan dibackup
	        'format'     => 'txt',
	        // gzip, zip, txt format filenya
	        'filename'   => 'mybackup.sql',
	        // Nama file
	        'add_drop'   => true, 
	        // Untuk menambahkan drop table di backup
	        'add_insert' => TRUE,
	        // Untuk menambahkan data insert di file backup
	        'newline'    => "\n",
	        // Baris baru yang digunakan dalam file backup
	        'foreign_key_checks' => false
		);

		$backup = $this->dbutil->backup($prefs);

		$this->load->helper('download');
		force_download('Database_'.Date('Y_m_d_H_i_s').'.sql', $backup);
    }

}
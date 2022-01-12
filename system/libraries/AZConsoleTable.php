<?php
/**
 * AZApp
 * @author	M. Isman Subakti
 * @copyright	28-06-2019
 */
defined('BASEPATH') OR exit('No direct script access allowed');
require_once("Lib/consoletable/src/LucidFrame/Console/ConsoleTable.php");

class CI_AZConsoleTable extends CI_AZ {

	public function __construct() {
		$this->ci =& get_instance();
	}

	public function instance() {
		return new LucidFrame\Console\ConsoleTable();
	}


}
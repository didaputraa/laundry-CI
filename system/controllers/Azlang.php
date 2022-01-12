<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Azlang extends CI_Controller {
	public function __construct() {
        parent::__construct();
        $this->load->helper('az_auth');
        az_check_auth('user_role');
    }

    public function index() {
    	$app = $this->load->library('AZApp');
    	$app = $this->azapp;
    	$data_header['title'] = azlang('AZ Language');
    	$app->set_data_header($data_header);

        $sel_language = $this->input->get('idlanguage');

    	$this->load->helper('array');
    	
    	$this->lang->load('app', $sel_language);
    	$data['data'] = azobj($this->lang, 'language');
        $data['sel_language'] = $sel_language;

        $list_lang = glob(APPPATH.'language' . '/*' , GLOB_ONLYDIR);
        $arr_lang = array();
        foreach ($list_lang as $key => $value) {
            $xlang = explode('/', $value);
            if (count($xlang) > 0) {
                $arr_lang[] = $xlang[(count($xlang) - 1)];
            }
        }
        $data['list_lang'] = $arr_lang;

    	$view = $this->load->view('azlang/v_azlang', $data, true);

    	$vjs = $this->load->view('azlang/vjs_azlang', '', true);
    	$vjs = str_replace('<script>', '', $vjs);
    	$app->add_js($vjs);

    	$app->add_content($view);

    	echo $app->render();
    }

    public function save() {
        $post = $this->input->post();
        $this->load->helper('file');
        $this->load->helper('array');

        $key = azarr($post, 'key');
        $value = azarr($post, 'value');
        $idlanguage = azarr($post, 'idlanguage');

        $return = "<?php \n";
        foreach ($key as $kkey => $kvalue) {
            if (strlen($kvalue) > 0) {
                $return .= '$lang["'.$kvalue.'"] = "'.azarr($value, $kkey).'";'."\n";
            }
        }

        if (!write_file(APPPATH.'language/'.$idlanguage.'/app_lang.php', $return)) {
            $this->session->set_flashdata('msg', azlang('Save data failed'));
        }
        else {
            $this->session->set_flashdata('msg', azlang('Save data success'));
        }
        redirect(app_url().'azlang?idlanguage='.$idlanguage);
    }
}
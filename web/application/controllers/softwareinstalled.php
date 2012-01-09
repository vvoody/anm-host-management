<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SoftwareInstalled extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Software Installed";
        $this->load->view('softwareinstalled');;
    }

}

/* End of file softwareinstalled.php */
/* Location: ./application/controllers/softwareinstalled.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SoftwareRunning extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Software Running";
        $this->load->view('softwarerunning');;
    }

}

/* End of file softwarerunning.php */
/* Location: ./application/controllers/softwarerunning.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class SoftwareRunning extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

}

/* End of file softwarerunning.php */
/* Location: ./application/controllers/softwarerunning.php */

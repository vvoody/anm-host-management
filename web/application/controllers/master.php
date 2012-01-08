<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function logout() {
        ;
    }
}

/* End of file master.php */
/* Location: ./application/controllers/master.php */

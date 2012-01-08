<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storage extends CI_Controller {

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

/* End of file storage.php */
/* Location: ./application/controllers/storage.php */

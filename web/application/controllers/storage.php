<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storage extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Storage";
        $this->load->view('storage');;
    }

}

/* End of file storage.php */
/* Location: ./application/controllers/storage.php */

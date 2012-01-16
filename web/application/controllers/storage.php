<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storage extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Storage_model', '', TRUE);
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Storage of Host $host_id";
        $data['host_id'] = $host_id;
        $data['storage'] = $this->Storage_model->get_storage($host_id);
        $this->load->view('storage', $data);
    }

    public function graph($type="daily", $storage_id) {
        ;
    }

}

/* End of file storage.php */
/* Location: ./application/controllers/storage.php */

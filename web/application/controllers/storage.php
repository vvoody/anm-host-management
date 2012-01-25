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
        $data['title'] = "Storage of Host $host_id";
        $data['host_id'] = $host_id;
        $data['storage'] = $this->Storage_model->get_storage($host_id);
        $this->load->view('storage', $data);
    }

    public function graph($period="daily", $storage_id) {
        $result = $this->Storage_model->get_cmpt_host($storage_id);
        if ($result) {
            $cmpt_descr = $result[0]->descr;
            $cmpt_host = $result[0]->ip_name;
        }
        $data['title'] = "$period graph of storage '$cmpt_descr' of $cmpt_host";
        $data['component'] = 'storage';
        $data['graphs'] = array('used_capacity', 'allocation_failures');
        $data['period'] = $period;
        $data['id_or_name'] = $storage_id;
        $this->load->view('graph', $data);
    }

}

/* End of file storage.php */
/* Location: ./application/controllers/storage.php */

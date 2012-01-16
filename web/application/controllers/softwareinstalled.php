<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Softwareinstalled extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Softwareinstalled_model', '', TRUE);
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Software Installed of Host $host_id";
        $data['host_id'] = $host_id;
        $data['softwareinstalled'] = $this->Softwareinstalled_model->get_softwareinstalled($host_id);
        $this->load->view('softwareinstalled', $data);
    }

    public function graph($type="daily", $softwareinstalled_name) {
        ;
    }

}

/* End of file softwareinstalled.php */
/* Location: ./application/controllers/softwareinstalled.php */

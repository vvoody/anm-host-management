<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Softwarerunning extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Softwarerunning_model', '', TRUE);
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Software Running of Host $host_id";
        $data['host_id'] = $host_id;
        $data['softwarerunning'] = $this->Softwarerunning_model->get_softwarerunning($host_id);
        $this->load->view('softwarerunning', $data);
    }

    public function graph($type="daily", $softwarerunning_name) {
        ;
    }

}

/* End of file softwarerunning.php */
/* Location: ./application/controllers/softwarerunning.php */

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

    public function graph($period="daily", $swrun_id) {
        $result = $this->Softwarerunning_model->get_cmpt_host($swrun_id);
        if ($result) {
            $cmpt_name = $result[0]->name;
            $cmpt_host = $result[0]->ip_name;
        }
        $data['title'] = "$period graph of running software '$cmpt_name' of $cmpt_host";
        $data['component'] = 'softwarerunning';
        $data['graphs'] = array('cpuUsed', 'memAllocated');
        $data['period'] = $period;
        $data['id_or_name'] = $swrun_id;
        $this->load->view('graph_rrd', $data);
    }

}

/* End of file softwarerunning.php */
/* Location: ./application/controllers/softwarerunning.php */

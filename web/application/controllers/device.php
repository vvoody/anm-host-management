<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Device_model', '', TRUE);
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function showall($host_id) {
        $this->load->helper('form');
        $this->load->helper('url');
        $data['title'] = "Devices of Host $host_id";
        $data['host_id'] = $host_id;
        $data['devices'] = $this->Device_model->get_devices($host_id);
        $this->load->view('device', $data);
    }

    public function graph($period="daily", $device_id) {
        $result = $this->Device_model->get_cmpt_host($device_id);
        if ($result) {
            $cmpt_descr = $result[0]->descr;
            $cmpt_host = $result[0]->ip_name;
        }
        $data['title'] = "$period graph of device '$cmpt_descr' of $cmpt_host";
        $data['component'] = 'device';
        $data['graphs'] = array('num_errors');
        $data['period'] = $period;
        $data['id_or_name'] = $device_id;
        $this->load->view('graph', $data);
    }

}

/* End of file device.php */
/* Location: ./application/controllers/device.php */

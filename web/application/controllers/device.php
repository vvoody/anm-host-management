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

    public function graph($type="daily", $device_id) {
        ;
    }

}

/* End of file device.php */
/* Location: ./application/controllers/device.php */

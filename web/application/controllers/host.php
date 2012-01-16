<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Host extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE &&
            $this->session->userdata('isAdmin') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->model('Host_model', '', TRUE);
        }
        else {
            echo "Admin ONLY can access to Hosts page!";
            throw new Exception();
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function show($host='all') {
        if ($host == 'all') {
            $data['title'] = "Hosts";
            $data['hosts'] = $this->Host_model->get_hosts();
            $this->load->view('host', $data);
        }
        else {
            $data['title'] = "Host";
            $data['host_id'] = $host;
            $this->load->view('host_one', $data);
        }
    }

    public function add() {
        $ip = $this->input->post('ip');
        $community = $this->input->post('community');
        if ($ip && $community) {
            $data = array(
                'ip_name' => $ip,
                'community' => $community,
                );
            $this->Host_model->add_host($data);
            redirect("/host/show/all", 'refresh');
        }
        else {
            $data['title'] = "Add a new host";
            $this->load->view('host_add', $data);
        }
    }

    public function del($host_id) {
        if (isset($host_id)) {
            $this->Host_model->del_host($host_id);
        }
        redirect("/host/show/all", "refresh");
    }

    public function graph() {
        ;
    }

}

/* End of file host.php */
/* Location: ./application/controllers/host.php */

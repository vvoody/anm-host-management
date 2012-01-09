<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Host extends CI_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function show($host='all') {
        $this->load->helper('form');
        $this->load->helper('url');
        if ($host == 'all') {
            $data['title'] = "Hosts";
            $this->load->view('host', $data);
        }
        else {
            $data['title'] = "Host";
            $this->load->view('host_one', $data);
        }
    }

    public function add() {
        $this->load->helper('form');
        $this->load->helper('url');
        $ip = $this->input->post('ip');
        $community = $this->input->post('community');
        if ($ip && $community) {
            echo $ip . ", " . $community;
            redirect("/host/show/all", 'refresh');
        }
        else {
            $data['title'] = "Add a new host";
            $this->load->view('host_add', $data);
        }
    }

    public function graph() {
        ;
    }

}

/* End of file host.php */
/* Location: ./application/controllers/host.php */

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Host extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    public function show($host='all') {
        if ($host == 'all') {
            $this->load->database();
            $query = $this->db->get('hosts');
            $data['title'] = "Hosts";
            $data['hosts'] = $query->result();
            $this->load->view('host', $data);
        }
        else {
            $data['title'] = "Host";
            $this->load->view('host_one', $data);
        }
    }

    public function add() {
        $ip = $this->input->post('ip');
        $community = $this->input->post('community');
        if ($ip && $community) {
            $this->load->database();
            $data = array(
                'ip_name' => $ip,
                'community' => $community,
                );
            $this->db->insert('hosts', $data);
            redirect("/host/show/all", 'refresh');
        }
        else {
            $data['title'] = "Add a new host";
            $this->load->view('host_add', $data);
        }
    }

    public function del($host_id) {
        if (isset($host_id)) {
            $this->load->database();
            $this->db->delete('hosts', array('id' => $host_id));
        }
        redirect("/host/show/all", "refresh");
    }

    public function graph() {
        ;
    }

}

/* End of file host.php */
/* Location: ./application/controllers/host.php */

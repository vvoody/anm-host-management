<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

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
            $this->load->view('user', $data);
        }
    }

    public function add() {
        $this->load->helper('form');
        $this->load->helper('url');
        $name = $this->input->post('name');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $usertype = $this->input->post('usertype');
        if ($name) {
            echo $name;
            redirect("/user/show/all", 'refresh');
        }
        else {
            $data['title'] = "Add a new host";
            $this->load->view('user_add', $data);
        }
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

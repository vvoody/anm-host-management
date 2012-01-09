<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

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
            $query = $this->db->get('users');
            $data['title'] = "Users";
            $data['hosts'] = $query->result();
            $this->load->view('host', $data);
        }
    }

    public function add() {
        $name = $this->input->post('name');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $email = $this->input->post('email');
        $usertype = $this->input->post('user');
        if ($name) {
            $this->load->database();
            $data = array(
                'name' => $name,
                'username' => $username,
                'password' => crypt($password),
                'email' => $email,
                'accountype' => $usertype,
                );
            $this->db->insert('users', $data);
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

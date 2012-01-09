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

    public function show($user='all') {
        if ($user == 'all') {
            $this->load->database();
            $query = $this->db->get('users');
            $data['title'] = "Users";
            $data['users'] = $query->result();
            $this->load->view('user', $data);
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

    public function del($user_id) {
        if (isset($user_id)) {
            $this->load->database();
            $this->db->delete('users', array('id' => $user_id));
        }
        redirect("/user/show/all", "refresh");
    }

}

/* End of file user.php */
/* Location: ./application/controllers/user.php */

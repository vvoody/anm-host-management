<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
    }

    public function index() {
        $data['title'] = "Home";
        $this->load->view('home', $data);
    }

    public function login() {
        $data['title'] = "Login";
        $this->load->view('login', $data);
    }

    public function logincheck() {
        echo "logincheck";
    }

    public function logout() {
        echo "logout";
    }
}

/* End of file master.php */
/* Location: ./application/controllers/master.php */

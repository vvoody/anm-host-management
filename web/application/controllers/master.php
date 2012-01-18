<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Master extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('Statistics_model', '', TRUE);
    }

    public function index() {
        if ($this->session->userdata('logged_in') == TRUE) {
            $num_notices = $this->Statistics_model->get_nums("NOTICE");
            $num_warnings = $this->Statistics_model->get_nums("WARNING");
            $num_errors = $this->Statistics_model->get_nums("ERROR");
            $num_criticals = $this->Statistics_model->get_nums("CRITICAL");
            $data['notices'] = $num_notices;
            $data['warnings'] = $num_warnings;
            $data['errors'] = $num_errors;
            $data['criticals'] = $num_criticals;

            $data['title'] = "Home";
            $this->load->view('home', $data);
        }
        else {
            $this->my_redirect('login');
        }
    }

    public function login() {
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->my_redirect();
        }
        else {
            $data['title'] = "Login";
            $this->load->view('login', $data);
        }
    }

    public function logincheck() {
        if ($this->session->userdata('logged_in') == TRUE) {
            return;
        }
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $account_type = $this->input->post('account_type');
        if ($username && $password) {
            $this->load->model('User_model', '', TRUE);
            $user = $this->User_model->get_user_by_username($username);
            if ($user) {
                if (crypt($password, $user->password) == $user->password) {
                    $this->session->set_userdata('logged_in', TRUE);
                    if ($user->account_type == "Admin")
                        $this->session->set_userdata('isAdmin', TRUE);
                    $this->my_redirect();
                    return;
                }
            }
        }
        $data['title'] = "Login";
        $data['warning'] = "Wrong username and password!";
        $this->load->view('login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        $this->my_redirect();
    }

    // default redirect to index
    private function my_redirect($page='/') {
        redirect($page);
    }

}

/* End of file master.php */
/* Location: ./application/controllers/master.php */

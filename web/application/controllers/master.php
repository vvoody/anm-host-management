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
            $data['username'] = $this->session->userdata('username');
            $data['password_url'] = site_url("master/password");
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
                    $this->session->set_userdata('username', $username);
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

    public function password() {
        $action = $this->uri->segment(3);  // master/password/change
        if (isset($action) && $action == 'change') {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $username = $this->session->userdata('username');
            $this->load->model('User_model', '', TRUE);
            $user = $this->User_model->get_user_by_username($username);
            if ($user) {
                if (crypt($old_password, $user->password) == $user->password) {
                    if (trim($new_password)) {
                        $userdata = array('password' => crypt($new_password));
                        $this->User_model->update_user($username, $userdata);
                        $this->my_redirect();
                    }
                    else
                        $data['warning'] = 'New password is not accepted!';
                }
                else
                    $data['warning'] = 'Old password is not accepted!';
            }
            else
                $data['warning'] = 'You do not exist in the database!';
        }
        else
            $data['title'] = 'Change Password';

        $this->load->view('password', $data);
    }

    // default redirect to index
    private function my_redirect($page='/') {
        redirect($page);
    }

}

/* End of file master.php */
/* Location: ./application/controllers/master.php */

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
            $username = $this->session->userdata('username');

            $data = array('notices' => $num_notices,
                          'warnings' => $num_warnings,
                          'errors' => $num_errors,
                          'criticals' => $num_criticals,
                          'title' => 'Home',
                          'username' => $username,
                          'password_url' => site_url("master/password"),
                          'this_system_version' => $this->config->item('anm_host_management_version'),
                          'sys_load' => sys_getloadavg(),
                          'web_server' => $_SERVER['SERVER_SOFTWARE'],
                          'admin_email' => $_SERVER['SERVER_ADMIN'],
                          'user_activities' => $this->Statistics_model->user_last_activities($username, 5),
                );

            $alarm_level = 'only_notsolved';
            $data['results'] = $this->Statistics_model->get_stats(strtoupper($alarm_level),
                                                                  10,
                                                                  0,
                                                                  TRUE)->result();
            $data['alarm_level'] = $alarm_level;

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

                    $this->Statistics_model->add(array('component' => $username,
                                                       'event' => 'logged in',
                                                       'solved' => 1));

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
                        $this->Statistics_model->add(array('component' => $username,
                                                           'event' => 'changed password',
                                                           'solved' => 1));
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

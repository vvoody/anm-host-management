<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Stats extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('form');
        $this->load->helper('url');
        $this->load->model('Statistics_model', '', TRUE);
        $this->tname = 'statistics';
    }

    public function index() {
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->library('pagination');

            $only_notsolved = FALSE;
            // stats/index/LEVEL/PAGE
            if ($this->uri->segment(3))
                $level = $this->uri->segment(3);
            else {
                $level = "only_notsolved";
                $only_notsolved = TRUE;
            }

            $config['base_url'] = site_url("stats/index/$level");
            $config['total_rows'] = $this->db->count_all($this->tname);
            $config['per_page'] = 20;
            $config['uri_segment'] = 4;
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';

            $this->pagination->initialize($config);

            $data['results'] = $this->Statistics_model->get_stats(strtoupper($level),
                                                                  $config['per_page'],
                                                                  $this->uri->segment(4),
                                                                  $only_notsolved)->result();
            $data['alarm_level'] = $level;
            $data['title'] = "Statistics of $level";
//            $data['debug_info'] = "lv: ". $level . ", per_page: " . $config['per_page'] . ", offset: " . $this->uri->segment(4);
            $this->load->view('statistics', $data);
        }
        else {
            redirect('login');
        }
    }

    public function solve($alarm_id) {
        if ((int)$alarm_id > 0){
            $this->Statistics_model->set_solved($alarm_id);
            redirect('stats');
        }
    }

}

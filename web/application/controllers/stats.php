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

            // stats/index/LEVEL/PAGE
            $level =  $this->uri->segment(3) ? $this->uri->segment(3) : "info";

            $config['base_url'] = site_url("stats/index/$level");
            $config['total_rows'] = $this->db->count_all($this->tname);
            $config['per_page'] = 20;
            $config['uri_segment'] = 4;
            $config['full_tag_open'] = '<p>';
            $config['full_tag_close'] = '</p>';

            $this->pagination->initialize($config);

            $data['results'] = $this->Statistics_model->get_stats(strtoupper($level),
                                                                  $config['per_page'],
                                                                  $this->uri->segment(4));
            $this->load->library('table');
            $tmpl = array('table_open' => '<table border="0" width="100%" class="norm" cellpadding="0" cellspacing="0">');
            $this->table->set_template($tmpl);
            $this->table->set_heading('id', 'component', 'event', 'pre_value', 'now_value', 'comment', 'stamp', 'level', 'tid', 'cmpt_idx', 'host_id');
            $data['title'] = "Statistics of $level";
            $data['debug_info'] = "lv: ". $level . ", per_page: " . $config['per_page'] . ", offset: " . $this->uri->segment(4);
            $this->load->view('statistics', $data);
        }
        else {
            $this->my_redirect('login');
        }
    }

}

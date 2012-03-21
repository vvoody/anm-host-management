<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rrd extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }

    // rrd/graph/device/numErrors/daily/101
    public function graph($cmpt, $dsname, $period, $cmpt_id) {
        $RRD_DIR = "/tmp/anm-host-management/rrd";
        $rrdfile = "$RRD_DIR/$cmpt/$dsname/$cmpt_id.rrd";
        $cmd = "rrdtool graph - --start 1332308100 DEF:$dsname=$rrdfile:$dsname:AVERAGE LINE1:$dsname#FF0000:\"$dsname\"";
        header("Content-Type: image/jpeg");
        passthru($cmd);
    }
}

/* End of file rrdgraph.php */
/* Location: ./application/controllers/rrdgraph.php */

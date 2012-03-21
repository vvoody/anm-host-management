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

        $line = $dsname;  // if no CDEF
        $cdef = "";
        $opts = "";

        // some are just numbers, no need CDEF
        switch($cmpt) {
        case 'host':
            break;
        case 'device':
            break;
        case 'storage':
            if ($dsname == "usedCapacity") {
                $line = "used_capacity";
                $cdef = "'CDEF:$line=$dsname,4096,*'";    // 4096 bytpes per unit
            }
            break;
        case 'softwarerunning':
            if ($dsname == "cpuUsed") {
                $line = "cpu_used";
                $cdef = "'CDEF:$line=$dsname,10,*'";    // 10 millisencds equal 1 centi-seconds
                $opts = "-v Millisencds";
            }
            break;
        default:
            return;
        }

        switch($period) {
        case 'daily':
            $start = "'-1 day'";
            break;
        case 'weekly':
            $start = "'-1 week'";
            break;
        case 'monthly':
            $start = "'-1 month'";
            break;
        default:
            return;
        }
        $cmd = "rrdtool graph - --start $start $opts DEF:$dsname=$rrdfile:$dsname:AVERAGE $cdef LINE1:$line#FF0000:\"$dsname\"";
//        echo $cmd;
        header("Content-Type: image/jpeg");
        passthru($cmd);
    }
}

/* End of file rrdgraph.php */
/* Location: ./application/controllers/rrdgraph.php */

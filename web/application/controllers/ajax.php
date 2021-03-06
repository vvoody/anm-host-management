<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if ($this->session->userdata('logged_in') == TRUE) {
            $this->load->helper('form');
            $this->load->helper('url');
            $this->load->database();
        }
    }

    public function index() {
        $this->load->view('welcome_message');
    }


    private function get_date_range($period, $basetime=FALSE) {
        date_default_timezone_set('Europe/Stockholm');
        // mysql timestamp format: '2012-01-22 11:22:33'
        // [$dateStart, $dateEnd)
        $secs_oneday = 86400;
        $days_oneweek = 7;
        $now = $basetime ? $basetime : time();
        $day_of_week = date('w', $now);
        $day_of_month = date('d', $now);
        $month = date('m', $now);
        $year = date('Y', $now);
        switch ($period) {
        case 'daily':
            $dateStart = date('Y-m-d', $now);
            $dateEnd = date('Y-m-d', $now + $secs_oneday);  // next day
            break;
        case 'weekly':
            $dateStart = date('Y-m-d', $now - $secs_oneday * $day_of_week);
            $dateEnd = date('Y-m-d', $now + $secs_oneday * ($days_oneweek - $day_of_week));
            break;
        case 'monthly':
            // via http://www.justin-cook.com/wp/2009/04/18/get-the-first-last-day-of-the-month-with-php/
            $month_start = strtotime($month.'/01/'.$year.' 00:00:00');  // '02/01/2012 00:00:00'
            $month_end = strtotime('+1 month',$month_start);
            $dateStart = date("Y-m-d", $month_start);
            $dateEnd = date("Y-m-d", $month_end);
            break;
        default:
            return array();
        }
        return array('dateStart' => $dateStart,
                     'dateEnd' => $dateEnd);
    }


    // select id, sum(used_capacity), UNIX_TIMESTAMP(DATE(stamp)) from storage_log where storage_id = 81 group by DATE(stamp);
    private function getdb($tabname, $col, $cmpt_col, $cmpt_id, $period, $basetime=FALSE) {
        if ($period == "daily")
            $select = "$col, UNIX_TIMESTAMP(stamp) stamp";
        else
            $select = "AVG($col) $col, UNIX_TIMESTAMP(DATE(stamp)) stamp";
        $date_range = $this->get_date_range($period, $basetime);
        $this->db->select($select);
        $where = "$cmpt_col = $cmpt_id and (stamp >= DATE '" . $date_range['dateStart'] . "' and stamp < DATE '" . $date_range['dateEnd'] . "')";
        $this->db->where($where);
        if ($period == "weekly" || $period == "monthly")
            $this->db->group_by('DATE(stamp)');
        $query = $this->db->get($tabname);
        return $query->result();
    }

    // ajax/json/storage/used_capacity/daily/81/$label
    public function json($cmpt, $col, $period, $cmpt_id, $label=FALSE, $basetime=FALSE) {
        // $arr = array(
        //     "label" => "storage $cmpt_id",
        //     "data" => array(array(1999, 4.4), array(2000, 3.7), array(2001, 0.8), array(2002, 1.6), array(2003, 2.5))
        //     );
        // header("Content-Type: application/json");
        // echo json_encode($arr);

        switch ($cmpt) {
        case "storage":
            $q = $this->getdb('storage_log', $col, 'storage_id', $cmpt_id, $period, $basetime);
            break;
        case "device":
            $q = $this->getdb('devices_log', $col, 'device_id', $cmpt_id, $period, $basetime);
            break;
        case "softwarerunning":
            $q = $this->getdb('software_running_log', $col, 'software_running_id', $cmpt_id, $period, $basetime);
            break;
        case "host":
            $q = $this->getdb('hosts_log', $col, 'host_id', $cmpt_id, $period, $basetime);
            break;
        default:
            $q = array();
        }

        $meta = array();
        foreach ($q as $r) {
            array_push($meta, array((int)$r->stamp*1000, $r->$col ? (int)$r->$col : $r->$col));
            // NULL should not be shown as 0.
        }

        $arr = array(
            "label" => $label ? $label : $col,
            "data" => $meta);

        header("Content-Type: application/json");
        echo json_encode($arr);
    }

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */

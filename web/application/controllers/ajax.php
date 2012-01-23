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


    private function get_date_range($period) {
        date_default_timezone_set('Europe/Stockholm');
        // mysql timestamp format: '2012-01-22 11:22:33'
        // [$dateStart, $dateEnd)
        $secs_oneday = 86400;
        $days_oneweek = 7;
        $now = time();
        $day_of_week = date('w', $now);
        $day_of_month = date('d', $now);
        switch ($period) {
        case 'daily':
            $dateStart = date('Y-m-d', $now);
            $dateEnd = date('Y-m-d', $now + $secs_oneday);
            break;
        case 'weekly':
            $dateStart = date('Y-m-d', $now - $secs_oneday * $day_of_week);
            $dateEnd = date('Y-m-d', $now + $secs_oneday * ($days_oneweek - $day_of_week));
            break;
        case 'monthly':
            // via http://www.justin-cook.com/wp/2009/04/18/get-the-first-last-day-of-the-month-with-php/
            $dateStart = date("Y-m-d", strtotime(date('m').'/01/'.date('Y').' 00:00:00'));
            $dateEnd = date("Y-m-d", strtotime('-1 second',strtotime('+1 month',strtotime(date('m').'/01/'.date('Y').' 00:00:00'))) + $secs_oneday);
            break;
        default:
            return array();
        }
        return array('dateStart' => $dateStart,
                     'dateEnd' => $dateEnd);
    }


    // select id, sum(used_capacity), UNIX_TIMESTAMP(DATE(stamp)) from storage_log where storage_id = 81 group by DATE(stamp);
    private function getdb($tabname, $col, $cmpt_col, $cmpt_id, $period) {
        if ($period == "daily")
            $select = "$col, UNIX_TIMESTAMP(stamp) stamp";
        else
            $select = "sum($col) $col, UNIX_TIMESTAMP(DATE(stamp)) stamp";
        $date_range = $this->get_date_range($period);
        $this->db->select($select);
        $where = "$cmpt_col = $cmpt_id and (stamp >= DATE '" . $date_range['dateStart'] . "' and stamp < DATE '" . $date_range['dateEnd'] . "')";
        $this->db->where($where);
        if ($period == "weekly" || $period == "monthly")
            $this->db->group_by('DATE(stamp)');
        $query = $this->db->get($tabname);
        return $query->result();
    }

    // ajax/json/storage/used_capacity/daily/81
    public function json($cmpt, $col, $period, $cmpt_id) {
        // $arr = array(
        //     "label" => "storage $cmpt_id",
        //     "data" => array(array(1999, 4.4), array(2000, 3.7), array(2001, 0.8), array(2002, 1.6), array(2003, 2.5))
        //     );
        // header("Content-Type: application/json");
        // echo json_encode($arr);

        switch ($cmpt) {
        case "storage":
            $q = $this->getdb('storage_log', $col, 'storage_id', $cmpt_id, $period);
            break;
        case "device":
            $q = $this->getdb('devices_log', $col, 'device_id', $cmpt_id, $period);
            break;
        case "softwarerunning":
            echo "softwarerunning";
            break;
        }

//        var_dump($q);

        $meta = array();
        foreach ($q as $r) {
            array_push($meta, array((int)$r->stamp*1000, $r->$col ? (int)$r->$col : $r->$col));
            // NULL should not be shown as 0.
        }

        $arr = array(
            "label" => $col,
            "data" => $meta);

        header("Content-Type: application/json");
        echo json_encode($arr);
    }

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */

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


    private function getdb($tabname, $col, $cmpt_id) {
        $this->db->select('used_capacity, UNIX_TIMESTAMP(stamp) stamp');
        $this->db->where($col, $cmpt_id);
        $query = $this->db->get($tabname);
        return $query->result();
    }

    // ajax/json/storage/daily/81
    public function json($cmpt, $type, $cmpt_id) {
        // $arr = array(
        //     "label" => "storage $cmpt_id",
        //     "data" => array(array(1999, 4.4), array(2000, 3.7), array(2001, 0.8), array(2002, 1.6), array(2003, 2.5))
        //     );
        // header("Content-Type: application/json");
        // echo json_encode($arr);

        switch ($cmpt) {
        case "storage":
            $q = $this->getdb('storage_log', 'storage_id', $cmpt_id);
            break;
        case "device":
            echo "device";
            break;
        case "softwarerunning":
            echo "softwarerunning";
            break;
        }

//        var_dump($q);

        $meta = array();
        foreach ($q as $r) {
            array_push($meta, array((int)$r->stamp*1000, (int)$r->used_capacity));
        }

        $arr = array(
            "label" => "$cmpt $cmpt_id",
            "data" => $meta);

        header("Content-Type: application/json");
        echo json_encode($arr);
    }

}

/* End of file ajax.php */
/* Location: ./application/controllers/ajax.php */

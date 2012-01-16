<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'statistics';    // table name
        $this->level = array('DEBUG'=>0, "INFO"=>1, "WARNING"=>2, "ERROR"=>3, "CRITICAL"=>4);
    }

    public function get_stats($level, $num, $offset) {
        $this->db->where('level', $this->level[$level]);
        $query = $this->db->get($this->tname, $num, $offset);
        return $query;
    }

    public function get_nums($level) {
        $this->db->where('level', $this->level[$level]);
        $query = $this->db->get($this->tname);
        return $query->num_rows();
    }

}

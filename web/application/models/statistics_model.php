<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'statistics';    // table name
        $this->level = array('LOG'=>0, "NOTICE"=>1, "WARNING"=>2, "ERROR"=>3, "CRITICAL"=>4);
    }

    public function get_stats($level, $num, $offset) {
        $this->db->where('level', $this->level[$level]);
        $query = $this->db->get($this->tname, $num, $offset);
        return $query;
    }

    public function get_nums($level) {
        $this->db->where('level', $this->level[$level]);
        $this->db->where('solved', 0);  // 0 means not solved yes, 1 means solved.
        $query = $this->db->get($this->tname);
        return $query->num_rows();
    }

}

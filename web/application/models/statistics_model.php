<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Statistics_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'statistics';    // table name
        $this->level = array('LOG'=>0, "NOTICE"=>1, "WARNING"=>2, "ERROR"=>3, "CRITICAL"=>4);
    }

    public function get_stats($level, $num, $offset, $only_notsolved=FALSE) {
        if (strtolower($level) == 'only_notsolved' && $only_notsolved)
            $this->db->where('solved', 0);
        else {
            $this->db->where('level', $this->level[$level]);
            if ($only_notsolved) $this->db->where('solved', 0);
        }
        $query = $this->db->get($this->tname, $num, $offset);
        return $query;
    }

    public function get_nums($level) {
        $this->db->where('level', $this->level[$level]);
        $this->db->where('solved', 0);  // 0 means not solved yes, 1 means solved.
        $query = $this->db->get($this->tname);
        return $query->num_rows();
    }

    public function add($data) {
        $this->db->insert($this->tname, $data);
    }

    public function user_last_activities($username, $limit) {
        $this->db->where('component', $username);
        $this->db->order_by("stamp", "desc");
        $this->db->limit($limit);
        $query = $this->db->get($this->tname);
        return $query->result();
    }

}

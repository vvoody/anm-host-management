<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Storage_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'storage';    // table name
    }

    public function get_storage($host_id) {
        $this->db->where('host_id', $host_id);
        $query = $this->db->get($this->tname);
        return $query->result();
    }

}

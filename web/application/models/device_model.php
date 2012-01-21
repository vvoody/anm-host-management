<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Device_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'devices';    // table name
    }

    public function get_devices($host_id=FALSE) {
        if ($host_id)
            $this->db->where('host_id', $host_id);
        $query = $this->db->get($this->tname);
        return $query->result();
    }

}

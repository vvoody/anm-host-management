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

    public function get_cmpt_host($cmpt_id) {
        $this->db->select('devices.descr, hosts.ip_name');
        $this->db->from($this->tname);
        $this->db->join('hosts', 'devices.host_id = hosts.id');
        $this->db->where('devices.id', $cmpt_id);
        $query = $this->db->get();
        return $query->result();
    }

}

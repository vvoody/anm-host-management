<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Softwarerunning_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'software_running_log';    // table name
    }

    public function get_softwarerunning($host_id) {
        $this->db->where('host_id', $host_id);
        $this->db->group_by('name');
        $query = $this->db->get($this->tname);
        return $query->result();
    }

    public function get_cmpt_host($cmpt_id) {
        $this->db->select('software_running.name, hosts.ip_name');
        $this->db->from('software_running');
        $this->db->join('hosts', 'software_running.host_id = hosts.id');
        $this->db->where('software_running.id', $cmpt_id);
        $query = $this->db->get();
        return $query->result();
    }

}

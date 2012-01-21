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

}

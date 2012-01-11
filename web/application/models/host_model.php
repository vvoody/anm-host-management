<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Host_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'hosts';    // table name
    }

    public function get_hosts() {
        $query = $this->db->get($this->tname);
        return $query->result();
    }

    public function add_host($data) {
        $this->db->insert($this->tname, $data);
    }

    public function del_host($host_id) {
        $this->db->delete($this->tname, array('id' => $host_id));
    }

}

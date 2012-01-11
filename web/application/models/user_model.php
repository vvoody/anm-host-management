<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->tname = 'users';    // table name
    }

    public function get_users() {
        $query = $this->db->get($this->tname);
        return $query->result();
    }

    public function add_user($data) {
        $this->db->insert($this->tname, $data);
    }

    public function del_user($user_id) {
        $this->db->delete($this->tname, array('id' => $user_id));
    }

}

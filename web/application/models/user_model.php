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

    public function get_user_by_username($username) {
        $this->db->where('username', $username);
        $query = $this->db->get($this->tname);
        if ($query->num_rows() > 0)
            return $query->row();
        else
            return NULL;
    }

    public function add_user($data) {
        $this->db->insert($this->tname, $data);
    }

    public function del_user($user_id) {
        $this->db->delete($this->tname, array('id' => $user_id));
    }

}

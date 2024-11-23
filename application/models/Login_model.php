<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model
{

    public function ceklogin($email)
    {
        $this->db->select('*');
        $this->db->where('email', $email);
        $aksi = $this->db->get('user')->result_array();
        return $aksi;
    }
}

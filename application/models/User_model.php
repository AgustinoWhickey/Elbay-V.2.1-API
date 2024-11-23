<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function getUsers()
    {
        $this->db->select('*, user.id as userid');
		$this->db->where('role_id !=', 1);
        $this->db->join('user_role','user_role.id = user.role_id');
        return $this->db->get('user')->result();
    }

    public function insertuser($data)
    {
        $aksi = $this->db->insert('user', $data);
		return $this->db->insert_id();
    }
	
	public function deleteUser($id)
    {
		$aksi = $this->db->where('id', $id)->delete('user');
		return $this->db->affected_rows();
    }
	
	public function updateuser($data)
	{
		$this->db->update('user', $data, ['id' => $data['id']]);
		return $this->db->affected_rows() == 1;
	}
	
	
	public function getUser($iduser)
    {
        $this->db->select('*');
        $this->db->where('id', $iduser);
        $aksi = $this->db->get('user')->row();
        return $aksi;
    }

}

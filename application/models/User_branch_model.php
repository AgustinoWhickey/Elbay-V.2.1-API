<?php defined('BASEPATH') OR exit('No direct script access allowed');

class User_branch_model extends CI_Model
{
    public function getUserBranches()
    {
        $this->db->select('*, user.name as username, user_branch.id as userbranchid');
        $this->db->join('user','user.id = user_branch.user_id');
        $this->db->join('branch','branch.id = user_branch.branch_id');
        return $this->db->get('user_branch')->result();
    }

    public function insertuserbranch($data)
    {
        $aksi = $this->db->insert('user_branch', $data);
		return $this->db->affected_rows();
    }

    public function deleteUserbranch($id)
    {
		$aksi = $this->db->where('id', $id)->delete('user_branch');
		return $this->db->affected_rows();
    }

    public function getUserBranch($id)
    {
        $this->db->select('*, user.name as username, user_branch.id as userbranchid');
        $this->db->join('user','user.id = user_branch.user_id');
        $this->db->join('branch','branch.id = user_branch.branch_id');
        $this->db->where('user_branch.id', $id);
        $aksi = $this->db->get('user_branch')->row();
        return $aksi;
    }

    public function updateuserbranch($data)
	{
		$this->db->update('user_branch', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

}

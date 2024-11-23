<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Branch_model extends CI_Model
{
    public function getBranches()
    {
        return $this->db->get('branch')->result();
    }

    public function insertbranch($data)
    {
        $aksi = $this->db->insert('branch', $data);
		return $this->db->affected_rows();
    }

    public function deleteBranch($id)
    {
		$aksi = $this->db->where('id', $id)->delete('branch');
		return $this->db->affected_rows();
    }

    public function getBranch($idcategory)
    {
        $this->db->select('*');
        $this->db->where('id', $idcategory);
        $aksi = $this->db->get('branch')->row();
        return $aksi;
    }

    public function updatebranch($data)
	{
		$this->db->update('branch', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

}

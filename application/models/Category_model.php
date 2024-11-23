<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends CI_Model
{
    public function getCategories()
    {
        return $this->db->get('product_category')->result();
    }

    public function insertcategory($data)
    {
        $aksi = $this->db->insert('product_category', $data);
		return $this->db->affected_rows();
    }

    public function deleteCategory($id)
    {
		$aksi = $this->db->where('id', $id)->delete('product_category');
		return $this->db->affected_rows();
    }

    public function getCategory($idcategory)
    {
        $this->db->select('*');
        $this->db->where('id', $idcategory);
        $aksi = $this->db->get('product_category')->row();
        return $aksi;
    }

    public function updatecategory($data)
	{
		$arr = [
			'nama' => $data['nama'],
		];

		$this->db->update('product_category', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

}

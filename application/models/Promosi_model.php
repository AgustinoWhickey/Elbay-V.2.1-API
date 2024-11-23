<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Promosi_model extends CI_Model
{
    public function getPromoses()
    {
        $this->db->select('*, product_item.id as product_id');
        $this->db->join('product_item','product_item.id = promosi.id_product');
        return $this->db->get('promosi')->result();
    }

    public function insertPromo($data)
    {
        $aksi = $this->db->insert('promosi', $data);
		return $this->db->affected_rows();
    }

    public function deletePromo($id)
    {
		$aksi = $this->db->where('id', $id)->delete('product_category');
		return $this->db->affected_rows();
    }

    public function getPromo($idcategory)
    {
        $this->db->select('*');
        $this->db->where('id', $idcategory);
        $aksi = $this->db->get('product_category')->row();
        return $aksi;
    }

    public function updatePromo($data)
	{
		$arr = [
			'nama' => $data['nama'],
		];

		$this->db->update('product_category', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

}

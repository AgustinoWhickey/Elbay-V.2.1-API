<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_model extends CI_Model
{
    public function getItems()
    {
        $this->db->select('item.*');
        $this->db->from('item');
        return $this->db->get()->result();
    }

    public function insertitem($data)
    {
        $aksi = $this->db->insert('item', $data);
		return $this->db->affected_rows();
    }

    public function deleteItem($id)
    {
		$aksi = $this->db->where('id', $id)->delete('item');
		return $this->db->affected_rows();
    }

    public function getMenuItems($idproduct) 
    {
        $this->db->select('item.name, item.id as item_id, menu_item.qty, menu_item.id, menu_item.product_id');
        $this->db->where('menu_item.product_id', $idproduct);
        $this->db->join('item', 'menu_item.item_id = item.id');
        $aksi = $this->db->get('menu_item')->result();
        return $aksi;
    }

    public function getItem($iditem)
    {
        $this->db->select('*');
        $this->db->where('id', $iditem);
        $aksi = $this->db->get('item')->row();
        return $aksi;
    }

    public function updateitem($data)
	{
		$this->db->update('item', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

    public function updateitemstockout($data)
	{
        $qty = $data['qty'];
        $id = $data['item_id'];
        $updated = time();

        $sql = "UPDATE item SET stock = stock - '$qty', updated = '$updated' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

    public function updatestockout($data)
	{
        $qty = $data['qty'];
        $id = $data['item_id'];
        $updated = time();

        $sql = "UPDATE product_item SET stock = stock - '$qty', updated = '$updated' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

    public function updatestockin($data)
	{
        $qty = $data['qty'];
        $id = $data['item_id'];
        $updated = time();

        $sql = "UPDATE product_item SET stock = stock + '$qty', updated = '$updated' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

}

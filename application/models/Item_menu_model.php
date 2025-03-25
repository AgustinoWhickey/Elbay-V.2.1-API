<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Item_menu_model extends CI_Model
{
    public function getMenuItems()
    {
        $this->db->select('product_item.*,product_category.name as category_name');
        $this->db->from('product_item');
        $this->db->join('product_category', 'product_item.category_id = product_category.id');
        return $this->db->get()->result();
    }

    public function insertmenuitem($data)
    {
        $aksi = $this->db->insert('menu_item', $data);
		return $this->db->affected_rows();
    }

    public function insertproductitem($data)
    {
        $aksi = $this->db->insert('product_item', $data);
        return $this->db->insert_id();
    }

    public function updateproductitem($data)
	{
		$this->db->update('product_item', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

    public function deleteMenuItem($id)
    {
		$aksi = $this->db->where('product_id', $id)->delete('menu_item');
		return $this->db->affected_rows();
    }

    public function getMenuItem($idproduct, $item = null)
    {
        $this->db->select('*');
        $this->db->where('product_id', $idproduct);
        if($item != null){
            $this->db->where('item_id', $item);
        }
        $this->db->join('item', 'item.id = menu_item.item_id');
        $aksi = $this->db->get('menu_item')->result();
        return $aksi;
    }

    public function updatemenuitem($data)
	{
		$this->db->update('menu_item', $data, ['id' => $data['id']]);

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

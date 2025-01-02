<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Stock_model extends CI_Model
{

    public function getStocks()
    {
        $this->db->select('stock.*, user.name as user_name, item.name as item_name, item.id as item_id, supplier.name as supplier_name, supplier.id as supplier_id');
        $this->db->from('stock');
        $this->db->join('item','item.id = stock.item_id');
        $this->db->join('supplier','supplier.id = stock.supplier_id', 'left');
        $this->db->join('user','user.id = stock.user_id');

        return $this->db->get()->result();
    }

    public function getStockIns()
    {
        $this->db->select('stock.*, product_item.id as product_id, product_item.name as product_name, product_item.barcode as product_barcode, supplier.name as supplier_name, user.name as user_name');
        $this->db->from('stock');
        $this->db->join('product_item','product_item.id = stock.item_id');
        $this->db->join('supplier','supplier.id = stock.supplier_id','left');
        $this->db->join('user','user.id = stock.user_id');
        $this->db->where('type','in');

        return $this->db->get()->result();
    }

    public function getStockItemIns()
    {
        $this->db->select('stock_item.*, item.id as item_id, item.unit as item_unit, item.name as product_name, supplier.name as supplier_name, user.name as user_name');
        $this->db->from('stock_item');
        $this->db->join('item','item.id = stock_item.item_id');
        $this->db->join('supplier','supplier.id = stock_item.supplier_id','left');
        $this->db->join('user','user.id = stock_item.user_id');
        $this->db->where('type','in');

        return $this->db->get()->result();
    }

    public function getStockOuts()
    {
        $this->db->select('stock.*, product_item.id as product_id, product_item.name as product_name, product_item.barcode as product_barcode, supplier.name as supplier_name, user.name as user_name');
        $this->db->from('stock');
        $this->db->join('product_item','product_item.id = stock.item_id');
        $this->db->join('supplier','supplier.id = stock.supplier_id','left');
        $this->db->join('user','user.id = stock.user_id');
        $this->db->where('type','out');

        return $this->db->get()->result();
    }

    public function insertstock($data)
    {
        $aksi = $this->db->insert('stock', $data);
		return $this->db->insert_id();
    }

    public function insertstockitem($data)
    {
        $aksi = $this->db->insert('stock_item', $data);
		return $this->db->insert_id();
    }

    public function deleteStockItem($id)
    {
		$aksi = $this->db->where('id', $id)->delete('stock_item');
		return $this->db->affected_rows();
    }

    public function deleteStock($id)
    {
		$aksi = $this->db->where('id', $id)->delete('stock');
		return $this->db->affected_rows();
    }

    public function getStock($idstock)
    {
        $this->db->select('*');
        $this->db->where('id', $idstock);
        $aksi = $this->db->get('stock')->row();
        return $aksi;
    }

    public function getStockItem($idstock)
    {
        $this->db->select('*');
        $this->db->where('id', $idstock);
        $aksi = $this->db->get('stock_item')->row();
        return $aksi;
    }

    public function addstockitem($data)
	{
        $unitqty = $data['unit_qty'];
        $itemqty = $data['item_qty'];
        $qty = $unitqty * $itemqty;
        $id = $data['item_id'];

        $sql = "UPDATE item SET stock = stock + '$qty' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

    public function updatestockoutproduct($data)
	{
        $qty = $data['qty'];
        $id = $data['item_id'];

        $sql = "UPDATE product_item SET stock = stock - '$qty' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

    public function updatestock($data)
	{
		$this->db->update('stock', $data, ['id' => $data['id']]);

		return $this->db->affected_rows() == 1;
	
	}

    public function updatestockoutitem($data)
	{
        $qty = $data['qty'];
        $id = $data['item_id'];

        $sql = "UPDATE item SET stock = stock - '$qty' WHERE id = '$id'";

        $this->db->query($sql);

		return $this->db->affected_rows() == 1;
	
	}

}

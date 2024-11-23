<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Product_Item_model extends CI_Model
{
    public function getItems()
    {
        $this->db->select('product_item.*, product_category.nama as category_name');
        $this->db->from('product_item');
        $this->db->join('product_category','product_category.id = product_item.category_id');
        return $this->db->get()->result();
    }

    // start datatables
    var $column_order = array(null, 'barcode', 'product_item.name', 'category_name', 'unit_name', 'price', 'stock'); 
    var $column_search = array('barcode', 'product_item.name', 'price', 'product_category.nama'); 
    var $order = array('id' => 'asc'); 
 
    private function _get_datatables_query() {
        $this->db->select('product_item.*, product_category.nama as category_name');
		$this->db->from('product_item');
		$this->db->join('product_category', 'product_item.category_id = product_category.id');
        $i = 0;
        foreach ($this->column_search as $item) { 
            if(@$_POST['search']['value']) { 
                if($i===0) { 
                    $this->db->group_start(); 
                    $this->db->like($item, $_POST['search']['value']);
                } else {
                    $this->db->or_like($item, $_POST['search']['value']);
                }
                if(count($this->column_search) - 1 == $i) 
                    $this->db->group_end(); 
            }
            $i++;
        }
         
        if(isset($_POST['order'])) { 
            $this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        }  else if(isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    function get_datatables() {
        $this->_get_datatables_query();
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered() {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all() {
        $this->db->from('product_item');
        return $this->db->count_all_results();
	}
	// end datatables

    public function insertitem($data)
    {
        $aksi = $this->db->insert('product_item', $data);
		return $this->db->insert_id();
    }

    public function deleteItem($id)
    {
		$aksi = $this->db->where('id', $id)->delete('product_item');
		return $this->db->affected_rows();
    }

    public function getItem($iditem)
    {
        $this->db->select('*');
        $this->db->where('id', $iditem);
        $aksi = $this->db->get('product_item')->row();
        return $aksi;
    }

    public function getItemByCategory($idcat)
    {
        $this->db->select('*');
        $this->db->where('category_id', $idcat);
        $aksi = $this->db->get('product_item')->result();
        return $aksi;
    }

    public function updateitem($data)
	{
		$this->db->update('product_item', $data, ['id' => $data['id']]);

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

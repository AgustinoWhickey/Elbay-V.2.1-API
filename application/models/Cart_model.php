<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Cart_model extends CI_Model
{

    public function getCart($id){
        $this->db->select('cart.*, product_item.name as item_name, cart.price as cart_price');
        $this->db->from('cart');
        $this->db->join('product_item', 'cart.item_id = product_item.id');
        $this->db->where('user_id', $id);
        $query = $this->db->get()->result();
        return $query;
    }

    public function addCart($data)
    {
        $query = $this->db->query("SELECT MAX(id) AS cart_no FROM cart");
        if($query->num_rows() > 0){
            $row = $query->row();
            $cart_no = ((int)$row->cart_no) + 1;
        } else {
            $cart_no = "1";
        }

        $params = [
			'id' => $cart_no,
			'item_id' => (int)$data['item_id'],
			'price' => $data['price'],
			'qty' => $data['qty'],
			'total' => ($data['qty'] * $data['price']),
			'user_id' => $data['user_id'],
			'created' => time()
		];

        $aksi = $this->db->insert('cart', $params); 
        return $this->db->insert_id();
    }

    public function deleteCart($id)
    {
		$aksi = $this->db->where('id', $id)->delete('cart');
		if ($aksi) {
			echo 1;
		} else {
			echo 0;
		}
    }

    public function deleteCartbyUser($id_user)
    {
		$aksi = $this->db->where('user_id', $id_user)->delete('cart');
		if ($aksi) {
			return 1;
		} else {
			return 0;
		}
    }

    function updateCartQty($data){
        $sql = "UPDATE cart SET price = '$data[price]', qty = qty + '$data[qty]', total = '$data[price]' * qty WHERE item_id = '$data[item_id]'";
        $aksi = $this->db->query($sql);
        if ($aksi) {
			echo 1;
		} else {
			echo 0;
		}
    }

    public function updatecart($data)
	{
        $id = $data['id'];
        $qty = $data['qty'];
        $total = $data['total'];
        $discount = $data['discount'];
        $updated = $data['updated'];

        $sql = "UPDATE cart SET qty = '$qty', discount_item = '$discount', total = '$total', updated = '$updated' WHERE id = '$id'";

        $aksi = $this->db->query($sql);

		if ($aksi) {
			echo 1;
		} else {
			echo 0;
		}
	
	}


}

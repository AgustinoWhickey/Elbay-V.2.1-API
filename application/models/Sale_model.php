<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Sale_model extends CI_Model
{

    public function getInvoice()
    {
        $query = $this->db->query("SELECT MAX(MID(invoice,9,4)) AS invoice_no FROM sale WHERE MID(invoice,3,6) = DATE_FORMAT(CURDATE(), '%y%m%d')");
        if($query->num_rows() > 0){
            $row = $query->row();
            $n = ((int)$row->invoice_no) + 1;
            $no = sprintf("%'.04d", $n);
        } else {
            $no = "0001";
        }
        $invoice = "MP".date('ymd').$no;
        return $invoice;
    }

    public function add_sale($data)
    {
        $params = array(
            'invoice' => $this->getInvoice(),
            'total_price' => $data['grandtotal'],
            'discount' => $data['discount'],
            'final_price' => ((int)$data['grandtotal'] - (int)$data['discount']),
            'cash' => $data['cash'],
            'remaining' => $data['change'],
            'note' => $data['note'],
            'user_id' => $data['user_id'],
            'date' => time(),
            'created' => time(),
        );

        $aksi = $this->db->insert('sale', $params); 
        
		return $this->db->insert_id();
		
    }

    public function add_sale_detail($data)
    {
        $cartDetail = [
            'sale_id' => $data['sale_id'],
            'item_id' => $data['item_id'],
            'price' => $data['price'],
            'qty' => $data['qty'],
            'discount_item' => $data['discount_item'],
            'total' => $data['total'],
            'user_id' => $data['user_id'],
            'created' => time()
        ];

        $aksi = $this->db->insert('sale_detail', $data);
        return $this->db->insert_id();
    }

    // start datatables
    var $column_order = array(null, 'invoice', 'created', 'total_price', 'discount', 'cash'); 
    var $column_search = array('invoice', 'total_price', 'discount', 'cash'); 
    var $order = array('id' => 'asc'); 
 
    private function _get_datatables_query() {
        $this->db->select('sale.*, user.name as user_name, sale.created as sale_created');
        $this->db->from('sale');
        $this->db->join('user', 'sale.user_id = user.id');
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
        $this->db->from('sale');
        return $this->db->count_all_results();
	}
	// end datatables

    // start datatables
 
    private function _get_datatables_range_date_query($todate, $fromdate) {
        $this->db->select('sale.*, user.name as user_name, sale.created as sale_created');
        $this->db->from('sale');
        $this->db->join('user', 'sale.user_id = user.id');
        $this->db->where('sale.created <=', (int)$todate);
        $this->db->where('sale.created >=', (int)$fromdate);
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
    
    function get_datatables_range_date($todate, $fromdate) {
        $this->_get_datatables_range_date_query($todate, $fromdate);
        if(@$_POST['length'] != -1)
        $this->db->limit(@$_POST['length'], @$_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }
    function count_filtered_range_date($todate, $fromdate) {
        $this->_get_datatables_range_date_query($todate, $fromdate);
        $query = $this->db->get();
        return $query->num_rows();
    }
    function count_all_range_date() {
        $this->db->from('sale');
        return $this->db->count_all_results();
	}
	// end datatables

    public function getSale($id = null)
    {
        $this->db->select('sale.*, user.name as user_name, user.email, sale.created as sale_created');
        $this->db->from('sale');
        $this->db->join('user', 'sale.user_id = user.id');
        if($id != null){
            $this->db->where('sale.id', $id);
        }
        $query = $this->db->get();
        return $query;
    }

    public function get_sale_detail($sale_id = null)
    {
        $this->db->select('sale_detail.*, product_item.name as name');
        $this->db->from('sale_detail');
        $this->db->join('product_item', 'sale_detail.item_id = product_item.id');
        if($sale_id != null){
            $this->db->where('sale_detail.sale_id', $sale_id);
        }
        $query = $this->db->get();
        return $query;
    }


}

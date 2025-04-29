<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Sale extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_item_model');
		$this->load->model('Item_model');
		$this->load->model('Item_menu_model');
		$this->load->model('Auth_model');
		$this->load->model('Category_model');
		$this->load->model('Sale_model');
		$this->load->model('Cart_model');
    }

    public function index_get()
	{
		$id = $this->get('id');
		if($id != null){
			$data['sale'] 	= $this->Sale_model->getSale($id)->row();
			$data['cart'] 	= $this->Cart_model->getCart($id);
		}
		$data['invoice'] 	= $this->Sale_model->getInvoice();
		$data['sales'] 		= $this->Sale_model->getSale()->result();
		$data['user'] 		= $this->Auth_model->ceklogin($this->get('email'));
		$data['items'] 		= $this->Product_item_model->getItems();
		$data['category'] 	= $this->Category_model->getCategories();

		if($data){
			$this->response( [
                'status' => true,
                'data' => $data
            ], RestController::HTTP_OK );
		} else{
			$this->response( [
                'status' => false,
                'message' => 'Data not found!'
            ], RestController::HTTP_NOT_FOUND );
		}
	
	}

    public function index_post()
	{
		if ($this->post('cash') != null) {
			$data = [
				'discount' => $this->post('discount'),
				'grandtotal' => $this->post('grandtotal',true),
				'cash' => $this->post('cash',true),
				'change' => $this->post('change',true),
				'note' => $this->post('note',true),
				'user_id' => $this->post('user_id',true),
			];

			$result = $this->Sale_model->add_sale($data);
			$this->response( [
                'status' => true,
                'data' => $result
            ], RestController::HTTP_OK );
		} else {
			$this->response( [
                'status' => false,
                'message' => 'Input Data Failed!'
            ], RestController::HTTP_NOT_FOUND );
		}
    }

}

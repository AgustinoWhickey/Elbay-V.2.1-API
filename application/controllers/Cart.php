<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Cart extends RestController
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
			$data['cart'] 	= $this->Cart_model->getCart($id);
		}
		$data['invoice'] 	= $this->Sale_model->getInvoice();
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
		if($this->post('item_id') != null) {
			$data = [
				'item_id' => $this->post('item_id',true),
				'price' => $this->post('price',true),
				'qty' => $this->post('qty',true),
				'user_id' => $this->post('user_id',true),
			];

			$result = $this->Cart_model->addCart($data);
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

    public function index_delete()
	{
		$id = $this->delete('id');
		if($id === null){
			$this->response( [
                'status' => false,
                'message' => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST );
		} else {
			if($this->Cart_model->deleteCartbyUser($id)){
				$this->response( [
	                'status' => true,
	                'id' => $id,
	                'message' => 'Deleted!'
	            ], RestController::HTTP_NOT_FOUND );
			} else {
				$this->response( [
	                'status' => false,
	                'message' => 'Id not found!'
	            ], RestController::HTTP_BAD_REQUEST );
			}
		}
	}

    public function index_put()
	{
		$data = [
			'id' => $this->put('id',true),
			'barcode' => $this->put('barcode',true),
			'name' => $this->put('nama',true),
			'category_id' => (int)$this->put('kategori',true),
			'unit_id' => null,
			'price' => (int)$this->put('harga',true),
			'stock' => (int)$this->put('stock'),
			'image' => $this->put('image'),
			'updated' => time()
		];

    	if($this->Product_item_model->updateitem($data)){
    		$this->response( [
                'status' => true,
                'message' => 'Data has been updated!'
            ], RestController::HTTP_OK );
        } else {
        	$this->response( [
                'status' => false,
                'message' => 'Update Failed!'
            ], RestController::HTTP_NOT_FOUND );
        }
	}
}

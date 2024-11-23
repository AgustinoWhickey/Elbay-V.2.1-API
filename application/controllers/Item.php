<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Item extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_item_model');
		$this->load->model('item_model');
		$this->load->model('item_menu_model');
		$this->load->model('login_model');
		$this->load->model('category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['oneitem'] 		= $this->product_item_model->getItem($id);
			$data['onemenuitem'] 	= $this->item_menu_model->getMenuItem($id);
		}
		$data['unititems'] 	= $this->item_model->getItems();
		$data['user'] 		= $this->login_model->ceklogin($this->get('email'));
		$data['items'] 		= $this->product_item_model->getItems();
		$data['category'] 	= $this->category_model->getCategories();

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
        if($this->post('nama') != ''){
			$data = [
				'barcode' => $this->post('barcode',true),
				'name' => $this->post('nama',true),
				'category_id' => (int)$this->post('kategori',true),
				'unit_id' => null,
				'price' => (int)$this->post('harga',true),
				'stock' => (int)$this->post('stock'),
				'image' => $this->post('image'),
				'created' => time()
			];
            $result = $this->product_item_model->insertitem($data);
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
			if($this->product_item_model->deleteItem($id)){
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
		if($this->put('item_id') == null){
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

			if($this->product_item_model->updateitem($data)){
				$this->response( [
					'status' => true,
					'message' => 'Data has been updated!'
				], RestController::HTTP_OK );
			} 
		} else if($this->put('item_id') != null) {
			$data = [
				'item_id' => $this->put('item_id',true),
				'qty' => $this->put('qty',true),
				'updated' => time()
			];

			if($this->product_item_model->updatestockout($data)){
				$this->response( [
					'status' => true,
					'message' => 'Data has been updated!'
				], RestController::HTTP_OK );
			} 
		} else {
        	$this->response( [
                'status' => false,
                'message' => 'Update Failed!'
            ], RestController::HTTP_NOT_FOUND );
        }
	}
}

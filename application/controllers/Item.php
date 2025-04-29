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
        $this->load->model('Product_item_model');
		$this->load->model('Item_model');
		$this->load->model('Item_menu_model');
		$this->load->model('Auth_model');
		$this->load->model('Category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['oneitem'] 	= $this->Item_model->getItem($id);
			$data['item'] 		= $this->Product_item_model->getItem($id);
		}
		$data['unititems'] 	= $this->Item_model->getItems();
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
        if($this->post('name') != ''){
			$data = [
				'name' => $this->post('name',true),
				'unit' => $this->post('satuan',true),
				'unit_price' => $this->post('harga_satuan',true),
				'image' => $this->post('image'),
				'expired_date ' => $this->post('expired'),
				'created' => time()
			];
            $result = $this->Item_model->insertitem($data);
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
			if($this->Item_model->deleteItem($id)){
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
		if($this->put('item_id') != ''){
			
			$data = [
				'id' => $this->put('item_id',true),
				'name' => $this->put('name',true),
				'unit' => $this->put('satuan',true),
				'unit_price' => $this->put('harga_satuan',true),
				'image' => $this->put('image'),
				'expired_date' => $this->put('expired'),
				'updated' => time()
			];

			if($this->Item_model->updateitem($data)){
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

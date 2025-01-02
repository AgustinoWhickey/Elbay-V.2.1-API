<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Stock extends RestController
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('Stock_model');
		$this->load->model('Item_model');
		$this->load->model('Supplier_model');
		$this->load->model('product_item_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['stocks'] = $this->Stock_model->getStocks();
		} else {
			$data['stock'] = $this->Stock_model->getStock($id);
		}
		$data['unititems'] 	= $this->Item_model->getItems();
		$data['suppliers'] 	= $this->Supplier_model->getSuppliers();
		$data['user'] 		= $this->Auth_model->ceklogin($this->get('email'));

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
        if($this->post('item_id') != ''){
			$data = [
				'item_id' => $this->post('item_id',true),
				'type' => 'in',
				'detail' => $this->post('detail',true),
				'supplier_id' => $this->post('supplier_id',true),
				'qty' => $this->post('qty',true),
				'user_id' => $this->post('user_id',true),
				'date' => $this->post('date',true),
				'created' => time()
			];
            $result = $this->Stock_model->insertstock($data);
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

	public function index_put()
	{
		$data = [
			'id' => $this->put('stock_id',true),
			'item_id' => $this->put('item_id',true),
			'type' => 'in',
			'detail' => $this->put('detail',true),
			'supplier_id' => $this->put('supplier_id',true),
			'qty' => $this->put('qty',true),
			'user_id' => $this->put('user_id',true),
			'date' => $this->put('date',true),
			'updated' => time()
		];

    	if($this->Stock_model->updatestock($data)){
    		$this->response( [
                'status' => true,
                'message' => 'Data has been updated!'
            ], RestController::HTTP_OK );
        } else {
        	$this->response( [
                'status' => true,
                'message' => 'Update Failed!'
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
			if($this->Stock_model->deleteStock($id)){
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
}

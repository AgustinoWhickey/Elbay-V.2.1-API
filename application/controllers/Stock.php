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
		$this->load->model('login_model');
		$this->load->model('stock_model');
		$this->load->model('item_model');
		$this->load->model('supplier_model');
		$this->load->model('product_item_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['supplier'] 		= $this->supplier_model->getSupplier($id);
		}
		$data['items'] 	    = $this->product_item_model->getItems();
		$data['stocks'] 	= $this->stock_model->getStockItemIns();
		$data['unititems'] 	= $this->item_model->getItems();
		$data['suppliers'] 	= $this->supplier_model->getSuppliers();
		$data['user'] 		= $this->login_model->ceklogin($this->get('email'));

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
				'unit' => $this->post('unit',true),
				'unit_qty' => $this->post('unit_qty',true),
				'unit_price' => $this->post('unit_price',true),
				'item_qty' => $this->post('item_qty',true),
				'user_id' => $this->post('user_id',true),
				'date' => $this->post('date',true),
				'created' => time()
			];
            $result = $this->stock_model->insertstockitem($data);
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
			if($this->stock_model->deleteStockItem($id)){
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

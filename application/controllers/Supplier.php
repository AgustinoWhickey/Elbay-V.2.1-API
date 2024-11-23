<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Supplier extends RestController
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('login_model');
		$this->load->model('supplier_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['supplier'] 		= $this->supplier_model->getSupplier($id);
		}
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
        if($this->post('name') != ''){
			$data = [
				'name' => $this->post('name',true),
				'phone' => $this->post('phone',true),
				'address' => $this->post('address',true),
				'description' => $this->post('description'),
			];
            $result = $this->supplier_model->insertsupplier($data);
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
			if($this->supplier_model->deleteSupplier($id)){
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
		if($this->put('supplier_id') != null) {
			$data = [
				'id' => $this->put('supplier_id',true),
				'name' => $this->put('name',true),
				'phone' => $this->put('phone',true),
				'address' => $this->put('address',true),
				'description' => $this->put('description',true),
				'updated' => time()
			];

			if($this->supplier_model->updatesupplier($data)){
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

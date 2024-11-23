<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class ItemMenu extends RestController
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('item_model');
		$this->load->model('item_menu_model');
		$this->load->model('login_model');
		$this->load->model('category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['items'] = $this->item_model->getItems();
		} else {
			$data['items'] = $this->item_model->getItem($id);
		}
        $data['user'] = $this->login_model->ceklogin($this->get('email'));

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
				'unit' => $this->post('unit',true),
				'unit_price' => $this->post('unit_price',true),
				'stock' => $this->post('stock',true),
				'image' => $this->post('image',true),
				'created' => time()
			];
            $this->item_model->insertitem($data);
			$this->response( [
                'status' => true,
                'data' => $data
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
			if($this->item_model->deleteItem($id)){
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
            'id' => $this->put('id'),
            'name' => $this->put('name',true),
			'unit' => $this->put('unit',true),
			'unit_price' => $this->put('unit_price',true),
			'stock' => $this->put('stock',true),
			'image' => $this->put('image',true),
            'updated' => time()
        ];

    	if($this->item_model->updateitem($data)){
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

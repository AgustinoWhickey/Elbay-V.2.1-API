<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class MenuItem extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_item_model');
		$this->load->model('Item_model');
		$this->load->model('Item_menu_model');
		$this->load->model('Auth_model');
		$this->load->model('Stock_model');
		$this->load->model('Category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['menus'] = $this->Item_menu_model->getMenuItem($id);
			$data['item_stock'] = $this->Product_item_model->getItem($id);
		} else {
			$data['menus'] = $this->Item_menu_model->getMenuItems();
		}
		$data['stocks'] = $this->Stock_model->getStocks();
		$data['categories'] = $this->Category_model->getCategories();
		$data['items'] = $this->Item_model->getItems();

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
				'name' => $this->post('name'),
				'code' => $this->post('code'),
				'category_id' => $this->post('category_id'),
				'unit_id' => $this->post('unit_id'),
				'price' => $this->post('price'),
				'use_item' => $this->post('use_item'),
				'stock' => $this->post('stock'),
				'image' => $this->post('image'),
				'expired_date' => $this->post('expired_date'),
				'created' => time()
			];
            $result = $this->Item_menu_model->insertproductitem($data);
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
		$id = $this->delete('product_id');
		if($id === null){
			$this->response( [
                'status' => false,
                'message' => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST );
		} else {
			if($this->Product_item_model->deleteItem($id)){
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
		if($this->put('type') == 'stockout'){
			$data = [
				'item_id' => $this->put('id'),
				'qty' => htmlspecialchars($this->put('stock')),
				'updated' => time(),
			];

			if($this->Item_menu_model->updatestockout($data)){
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
		} else if($this->put('type') == 'update_stock'){
			$data = [
				'item_id' => $this->put('id'),
				'qty' => htmlspecialchars($this->put('stock')),
				'updated' => time(),
			];

			if($this->Item_menu_model->updatestock($data)){
				$this->response( [
					'status' => true,
					'message' => 'Stock Data has been updated!'
				], RestController::HTTP_OK );
			} else {
				$this->response( [
					'status' => true,
					'message' => 'Update Failed!'
				], RestController::HTTP_NOT_FOUND );
			}
		} else {
			$data = [
				'id' => $this->put('menu_id'),
				'name' => htmlspecialchars($this->put('name')),
				'code' => htmlspecialchars($this->put('code')),
				'category_id' => htmlspecialchars($this->put('category_id')),
				'unit_id' => '',
				'price' => htmlspecialchars($this->put('price')),
				'use_item' => htmlspecialchars($this->put('use_item')),
				'stock' => htmlspecialchars($this->put('stock')),
				'image' => htmlspecialchars($this->put('image')),
				'expired_date' => htmlspecialchars($this->put('expired_date')),
				'updated' => time(),
			];
	
			if($this->Item_menu_model->updateproductitem($data)){
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
	}
}

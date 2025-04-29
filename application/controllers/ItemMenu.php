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
		$this->load->model('Item_model');
		$this->load->model('Item_menu_model');
		$this->load->model('Auth_model');
		$this->load->model('Stock_model');
		$this->load->model('Category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['item_menus'] = $this->Item_menu_model->getMenuItems();
		} else {
			$data['item_menus'] = $this->Item_menu_model->getMenuItem($id);
		}
		$iditem = $this->get('iditem');
		if($iditem !== null){
			$data['item_by_itemid'] = $this->Item_menu_model->getItemMenu($iditem);
		}
		$data['stocks'] = $this->Stock_model->getStocks();
		$data['items'] = $this->Item_model->getItems();
        $data['user'] = $this->Auth_model->ceklogin($this->get('email'));

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
        if($this->post('product_id') != ''){
			$data = [
				'product_id' => $this->post('product_id',true),
				'item_id' => $this->post('item_id',true),
				'qty' => $this->post('qty',true),
				'created' => time()
			];
            $this->Item_menu_model->insertmenuitem($data);
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
		$id = $this->delete('product_id');
		if($id === null){
			$this->response( [
                'status' => false,
                'message' => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST );
		} else {
			if($this->Item_menu_model->deleteMenuItem($id)){
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
			'product_id' => $this->put('product_id',true),
			'item_id' => $this->put('item_id',true),
			'qty' => $this->put('qty',true),
			'updated' => time()
		];

    	if($this->Item_menu_model->updatemenuitem($data)){
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

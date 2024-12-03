<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Product extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_item_model');
		$this->load->model('item_model');
		$this->load->model('item_menu_model');
		$this->load->model('Auth_model');
		$this->load->model('category_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['items'] 	= $this->product_item_model->getItemByCategory($id);
		} else {
			$data['items'] 	= $this->product_item_model->getItems();
		} 

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
}

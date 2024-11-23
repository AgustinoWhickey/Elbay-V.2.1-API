<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class SaleDetail extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('product_item_model');
		$this->load->model('item_model');
		$this->load->model('item_menu_model');
		$this->load->model('login_model');
		$this->load->model('category_model');
		$this->load->model('sale_model');
		$this->load->model('cart_model');
    }

    public function index_get()
	{
		$id = $this->get('id');
		if($id != null){
			$data['saledetail'] 	= $this->sale_model->get_sale_detail($id)->result();
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

    public function index_post()
	{
		if ($this->post('sale_id') != null) {
			$data = [
				'sale_id' => $this->post('sale_id'),
				'item_id' => $this->post('item_id',true),
				'price' => $this->post('price',true),
				'qty' => $this->post('qty',true),
				'discount_item' => $this->post('discount_item',true),
				'total' => $this->post('total',true),
				'user_id' => $this->post('user_id',true),
			];

			$result = $this->sale_model->add_sale_detail($data);
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

}

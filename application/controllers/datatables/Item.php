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
    }

    public function index_get()
	{
        $data['data'] 			= $this->product_item_model->get_datatables();
		$data['count'] 			= $this->product_item_model->count_all();
		$data['count_filtered'] = $this->product_item_model->count_filtered();

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

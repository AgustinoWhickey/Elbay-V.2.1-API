<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class UnitItem extends RestController
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('item_model');
    }

    public function index_put()
	{
		$data = [
			'item_id' => $this->put('item_id',true),
			'qty' => $this->put('qty',true),
		];

    	if($this->item_model->updateitemstockout($data)){
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

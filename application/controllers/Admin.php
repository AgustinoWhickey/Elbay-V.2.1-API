<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Admin extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("admin_model");
        $this->load->model("Auth_model");
    }

    public function index_get()
	{
        $status = $this->get('status');
        
        $detail['sales'] = $this->admin_model->detailSales($status);
        $detail['user'] = $this->Auth_model->ceklogin($this->get('email'));

		if($status){
			$this->response( [
                'status' => true,
                'data' => $detail
            ], RestController::HTTP_OK );
		} else{
			$this->response( [
                'status' => false,
                'message' => 'Data not found!'
            ], RestController::HTTP_NOT_FOUND );
		}
	
	}
}

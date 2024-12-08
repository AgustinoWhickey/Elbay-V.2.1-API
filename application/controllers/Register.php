<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Register extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Auth_model");
    }

    public function index_post()
	{
        $aksi = $this->Auth_model->ceklogin($this->post('email',true));
        if(!$aksi){
			$data = [
				'email' => htmlspecialchars($this->post('email',true)),
				'phone_number' => htmlspecialchars($this->post('phone_number',true)),
				'username' => htmlspecialchars($this->post('username',true)),
				'company' => htmlspecialchars($this->post('company',true)),
				'image' => htmlspecialchars($this->post('image',true)),
				'password' => htmlspecialchars($this->post('password',true)),
			];

            $result = $this->Auth_model->insertRegister($data);
			$this->response( [
                'status' => true,
                'data' => $result
            ], RestController::HTTP_OK );
		} else {
			$this->response( [
                'status' => false,
                'data' => $aksi
            ], RestController::HTTP_NOT_FOUND );
		}

    }

    public function index_get()
	{
        $aksi = $this->Auth_model->updateRegister($this->get('token'));
		if($aksi){
			$this->response( [
                'status' => true,
                'data' => $aksi
            ], RestController::HTTP_OK );
		} else{
			$this->response( [
                'status' => false,
                'message' => 'Data Not Found!'
            ], RestController::HTTP_NOT_FOUND );
		}
	
	}
}

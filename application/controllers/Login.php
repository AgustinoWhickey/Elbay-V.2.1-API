<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Login extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("login_model");
    }

    public function index_post()
	{
		$aksi = $this->login_model->ceklogin($this->post('email'));
        if(password_verify( $this->post('password'),$aksi[0]["password"])){
			$data = [
				'id' => $aksi[0]['id'],
				'email' => $aksi[0]['email'],
				'role' => $aksi[0]['role_id'],
				'logged_in' => TRUE
			];
			$this->response( [
                'status' => true,
                'data' => $data
            ], RestController::HTTP_OK );
		} else {
			$this->response( [
                'status' => false,
                'message' => 'Data not found!'
            ], RestController::HTTP_NOT_FOUND );
		}
    }

    public function index_get()
	{
        $aksi = $this->login_model->ceklogin($this->get('email'));
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

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class User extends RestController
{
    public function __construct()
    {
        parent::__construct();
		$this->load->model('Auth_model');
		$this->load->model('user_model');
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id != null){
			$data['oneuser'] 		= $this->user_model->getUser($id);
		}
		$data['users'] 		= $this->user_model->getUsers();
		$data['user'] 		= $this->Auth_model->ceklogin($this->get('email'));

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
				'email' => $this->post('email',true),
				'image' => $this->post('image',true),
				'password' => $this->post('password',true),
				'role_id' =>  $this->post('role_id',true),
				'is_active' => $this->post('is_active',true), 
				'date_created' => time()
			];

            $result = $this->user_model->insertuser($data);
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
		$id = $this->delete('id');
		if($id === null){
			$this->response( [
                'status' => false,
                'message' => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST );
		} else {
			if($this->user_model->deleteUser($id)){
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
		if($this->put('user_id') != null) {
			$data = [
				'id' => $this->put('user_id',true),
				'name' => $this->put('name',true),
				'email' => $this->put('email',true),
				'image' => $this->put('image',true),
				'password' => $this->put('password',true),
				'role_id' =>  $this->put('role_id',true),
				'is_active' => $this->put('is_active',true),
			];

			if($this->user_model->updateuser($data)){
				$this->response( [
					'status' => true,
					'message' => 'Data has been updated!'
				], RestController::HTTP_OK );
			} 
		} else {
        	$this->response( [
                'status' => false,
                'message' => 'Update Failed!'
            ], RestController::HTTP_NOT_FOUND );
        }
	}
}

<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class UserBranch extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("user_branch_model");
		$this->load->model("branch_model");
		$this->load->model("user_model");
        $this->load->model("Auth_model");
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['branches'] = $this->branch_model->getBranches();
			$data['users'] = $this->user_model->getUsers();
			$data['userbranches'] = $this->user_branch_model->getUserBranches();
		} else {
			$data['userbranch'] = $this->user_branch_model->getUserBranch($id);
		}
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
		$data = [
			'user_id' => htmlspecialchars($this->post('user',true)),
			'branch_id' => htmlspecialchars($this->post('cabang',true)),
			'created' => time()
		];
		$this->user_branch_model->insertuserbranch($data);
		$this->response( [
			'status' => true,
			'data' => $data
		], RestController::HTTP_OK );
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
			if($this->user_branch_model->deleteUserbranch($id)){
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
            'id' => $this->put('userbranch_id'),
            'user_id' => htmlspecialchars($this->put('user',true)),
			'branch_id' => htmlspecialchars($this->put('cabang',true)),
            'updated' => time()
        ];

    	if($this->user_branch_model->updateuserbranch($data)){
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

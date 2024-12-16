<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Branch extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("Branch_model");
        $this->load->model("Auth_model");
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['branches'] = $this->Branch_model->getBranches();
		} else {
			$data['branch'] = $this->Branch_model->getBranch($id);
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
        if($this->post('name') != ''){
			$data = [
				'company_id' => htmlspecialchars($this->post('company_id',true)),
				'name' => htmlspecialchars($this->post('name',true)),
				'phone' => htmlspecialchars($this->post('phone',true)),
				'address' => htmlspecialchars($this->post('address',true)),
				'description' => htmlspecialchars($this->post('description',true)),
				'created' => time()
			];
            $this->Branch_model->insertbranch($data);
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
		$id = $this->delete('id');
		if($id === null){
			$this->response( [
                'status' => false,
                'message' => 'Provide an id!'
            ], RestController::HTTP_BAD_REQUEST );
		} else {
			if($this->Branch_model->deleteBranch($id)){
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
            'id' => $this->put('branch_id'),
            'name' => htmlspecialchars($this->put('name',true)),
			'phone' => htmlspecialchars($this->put('phone',true)),
			'address' => htmlspecialchars($this->put('address',true)),
			'description' => htmlspecialchars($this->put('description',true)),
            'updated' => time()
        ];

    	if($this->Branch_model->updatebranch($data)){
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

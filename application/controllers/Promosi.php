<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Access-Control-Allow-Origin, Accept");

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Promosi extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model("promosi_model");
        $this->load->model("product_item_model");
        $this->load->model("login_model");
    }

    public function index_get()
	{
        $id = $this->get('id');
		if($id === null){
			$data['promoses'] 	= $this->promosi_model->getPromoses();
			$data['items'] 	 	= $this->product_item_model->getItems();
			// $data['userbranches'] = $this->user_branch_model->getUserBranches();
		} else {
			$data['promo'] = $this->promosi_model->getPromo($id);
		}
        $data['user'] = $this->login_model->ceklogin($this->get('email'));

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
			'nama' => htmlspecialchars($this->post('nama',true)),
			'tipe' => htmlspecialchars($this->post('tipe',true)),
			'total_promo' => htmlspecialchars($this->post('total_promo',true)),
			'start_date' => htmlspecialchars($this->post('start_date',true)),
			'end_date' => htmlspecialchars($this->post('end_date',true)),
			'deskripsi' => htmlspecialchars($this->post('deskripsi',true)),
			'id_product' => htmlspecialchars($this->post('id_product',true)),
			'created' => time()
		];
		$this->promosi_model->insertPromo($data);
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

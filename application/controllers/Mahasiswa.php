<?php

defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Mahasiswa extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('Mahasiswa_model');

		$this->methods['index_get']['limit'] = 2; //per hour
	}
	
	public function index_get()
	{
		$id = $this->get('id');
		if($id === null){
			$mahasiswa = $this->Mahasiswa_model->getAllMahasiswa();
		} else {
			$mahasiswa = $this->Mahasiswa_model->getMahasiswaById($id);
		}

		if($mahasiswa){
			$this->response( [
                'status' => true,
                'data' => $mahasiswa
            ], RestController::HTTP_OK );
		} else{
			$this->response( [
                'status' => false,
                'message' => 'Data not found!'
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
			if($this->Mahasiswa_model->hapusDataMahasiswa($id)>0){
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

	public function index_post()
	{
		$data = [
    		'nama'            => $this->input->post('nama'),
    		'nim'             => $this->input->post('nrp'),
            'email'           => $this->input->post('email'),
    		'tanggal_lahir'   => $this->input->post('tgllahir'),
    		'jurusan'         => $this->input->post('jurusan'),
    	];

    	if($this->Mahasiswa_model->tambahDataMahasiswa($data)>0){
    		$this->response( [
                'status' => true,
                'message' => 'New Mahasiswa has been created!'
            ], RestController::HTTP_CREATED );
        } else {
        	$this->response( [
                'status' => true,
                'message' => 'Add Failed!'
            ], RestController::HTTP_NOT_FOUND );
        }
	}

	public function index_put()
	{
		$id = $this->put('nrp');
		$data = [
    		'nama'            => $this->put('nama'),
            'email'           => $this->put('email'),
    		'tanggal_lahir'   => $this->put('tgllahir'),
    		'jurusan'         => $this->put('jurusan'),
    	];

    	if($this->Mahasiswa_model->ubahDataMahasiswa($data, $id)>0){
    		$this->response( [
                'status' => true,
                'message' => 'Mahasiswa has been updated!'
            ], RestController::HTTP_NOT_FOUND );
        } else {
        	$this->response( [
                'status' => true,
                'message' => 'Update Failed!'
            ], RestController::HTTP_NOT_FOUND );
        }
	}

}

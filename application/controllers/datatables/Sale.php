<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/RestController.php';
require APPPATH . 'libraries/Format.php';
use chriskacerguis\RestServer\RestController;

class Sale extends RestController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('sale_model');
    }

    public function index_get()
	{
        $fromDate = $this->get('fromdate');
        $toDate = $this->get('todate');
		if($fromDate != null && $toDate != null){
			$data['data_range_date'] 	= $this->sale_model->get_datatables_range_date($fromDate, $toDate);
			$data['count_all_date'] 	= $this->sale_model->count_all_range_date();
			$data['count_filtered_date'] 	= $this->sale_model->count_filtered_range_date($fromDate, $toDate);;
		}
        $data['data'] 			= $this->sale_model->get_datatables();
		$data['count'] 			= $this->sale_model->count_all();
		$data['count_filtered'] = $this->sale_model->count_filtered();

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

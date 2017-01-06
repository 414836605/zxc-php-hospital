<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Facility extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('facility_model');
	}

	public function get_facility_by_id($id)
	{
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$result = $this->facility_model->get_facility_by_id($id);
		echo json_encode($result);
		return;
	}


}
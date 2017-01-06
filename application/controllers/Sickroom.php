<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Sickroom extends CI_Controller {

	public function __construct(){
		parent::__construct();
		// $this->load->model('sickroom_model');
	}

	public function get_sickroom()
	{

		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$patient = $this->patient_model->get_patient_and_page($this->input->get('start', true), $this->input->get('length', true));
		$total = $this->patient_model->get_patient_num();

		$result['draw'] = $this->input->get('draw', true);
		$result['recordsTotal'] = $total;
		$result['recordsFiltered'] = $total;
		$result['data'] = $patient;
		$result['error'] = '';
		echo json_encode($result);
		return;
	}


}

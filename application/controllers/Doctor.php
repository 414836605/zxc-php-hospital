<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Doctor extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('doctor_model');
	}

	public function index()
	{
		$this->load->view('header.inc.html');
		$this->load->view('doctor.html');
		$this->load->view('footer.inc.html');
	}

	public function get_doctor()
	{

		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$doctors = $this->doctor_model->get_doctors_and_page($this->input->get('start', true), $this->input->get('length', true));
		if ($doctors) {
			for ($i=0; $i < count($doctors); $i++) { 
				$this->_handle_doctor($doctors[$i]);
			}
			//用foreach无法改变$doctors（$doctor是复制的）
			// foreach ($doctors as $doctor) {
			// 	$this->_handle_doctor($doctor);
			// 	var_dump($doctor);
			// }
		}
		$total = $this->doctor_model->get_doctor_num();

		$result['draw'] = $this->input->get('draw', true);
		$result['recordsTotal'] = $total;
		$result['recordsFiltered'] = $total;
		$result['data'] = $doctors;
		$result['error'] = '';
		echo json_encode($result);
		return;
	}

	public function get_doctor_by_id($id)
	{
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$doctor = $this->doctor_model->get_doctor_by_id($id);
		if ($doctor) {
			$this->_handle_doctor($doctor);
			echo json_encode($doctor);
		}
		return;
	}

	private function _handle_doctor(&$doctor){
		switch ($doctor['doc_level']) {
				case '1':
					$doctor['doc_level'] = '住院医师';
					break;
				case '2':
					$doctor['doc_level'] = '主治医师';
					break;
				case '3':
					$doctor['doc_level'] = '副主任医师';
					break;
				case '4':
					$doctor['doc_level'] = '主任医师';
					break;
				default:
					$doctor['doc_level'] = '其他';
					break;
			}
		$doctor['is_special'] = $doctor['is_special'] ? '是' : '否';
		$doctor['entry_date'] = date("Y-m-d ", $doctor['entry_date']);
	}

}

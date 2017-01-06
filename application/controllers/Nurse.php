<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nurse extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('nurse_model');
	}

	public function index()
	{
		$this->load->view('header.inc.html');
		$this->load->view('nurse.html');
		$this->load->view('footer.inc.html');
	}

	public function get_nurse()
	{
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$nurses = $this->nurse_model->get_nurse_and_page($this->input->get('start', true), $this->input->get('length', true));
		if ($nurses) {
			for ($i=0; $i < count($nurses); $i++) { 
				$this->_handle_nurse($nurses[$i]);
			}
		}
		
		$total = $this->nurse_model->get_nurse_num();

		$result['draw'] = $this->input->get('draw', true);
		$result['recordsTotal'] = $total;
		$result['recordsFiltered'] = $total;
		$result['data'] = $nurses;
		$result['error'] = '';
		echo json_encode($result);
		return;
	}

	public function get_nurse_by_id($id)
	{
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$nurse = $this->nurse_model->get_nurse_by_id($id);
		if ($nurse) {
			$this->_handle_nurse($nurse);
			echo json_encode($nurse);
		}
		return;
	}

	private function _handle_nurse(&$nurse){
		switch ($nurse['nur_level']) {
			case '1':
				$nurse['nur_level'] = '普通护士';
				break;
			case '2':
				$nurse['nur_level'] = '护士长';
				break;
			default:
				$nurse['nur_level'] = '其他';
				break;
		}
		$nurse['entry_date'] = date('Y-m-d',$nurse['entry_date']);
	}

}

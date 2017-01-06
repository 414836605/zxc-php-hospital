<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('orders_model');
	}
	// 添加临时医嘱
	public function add_temporary_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$data['to_content'] = $this->input->post_get('to_content');
		$data['doctor_name'] = $this->input->post_get('doctor_name');
		$data['pat_id'] = $this->input->post_get('pat_id');
		$data['create_time'] = time();
		
		$return['state'] = $this->orders_model->add_temporary_orders($data) ? '1' : '0';

		echo json_encode($return);
		return;
	}
	// 执行临时医嘱（通过医嘱id添加执行护士，执行时间）
	public function execute_temporary_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$data['to_id'] = $this->input->post_get('to_id');
		$data['nurse_name'] = $this->input->post_get('nurse_name');
		$data['execute_time'] = time();
		
		$return['state'] = $this->orders_model->execute_temporary_orders($data) ? '1' : '0';
		var_dump(json_encode($return));
		echo json_encode($return);
		return;
	}
	// 删除临时医嘱
	public function delete_temporary_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$id = $this->input->post_get('to_id');
		
		$return['state'] = $this->orders_model->delete_temporary_orders($id) ? '1' : '0';
		echo json_encode($return);
		return;
	}


	// 添加长期医嘱
	public function add_standing_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$data['so_content'] = $this->input->post_get('so_content');
		$data['start_doctor_name'] = $this->input->post_get('start_doctor_name');
		$data['pat_id'] = $this->input->post_get('pat_id');
		$data['start_create_time'] = time();
		
		$return['state'] = $this->orders_model->add_standing_orders($data) ? '1' : '0';

		echo json_encode($return);
		return;
	}
	// 停止长期医嘱
	public function stop_standing_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$data['so_id'] = $this->input->post_get('so_id');
		$data['stop_doctor_name'] = $this->input->post_get('stop_doctor_name');
		$data['stop_create_time'] = time();
		
		$return['state'] = $this->orders_model->stop_standing_orders($data) ? '1' : '0';
		echo json_encode($return);
		return;
	}
}
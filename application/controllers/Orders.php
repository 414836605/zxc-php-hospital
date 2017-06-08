<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Orders extends Base_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('orders_model');
	}
	// 添加临时医嘱
	public function add_temporary_orders(){
		$data['to_content'] = $this->input->post_get('to_content');
		$data['doctor_name'] = $this->input->post_get('doctor_name');
		$data['pat_id'] = $this->input->post_get('pat_id');
		$data['create_time'] = time();
		$data['doctor_sign'] = explode(",", $this->input->post_get('doctor_sign'));
		if (!$data['doctor_name']) {
			$data['doctor_name'] = $_SESSION['user']['name'];
		}
		if ($data['doctor_sign'] && $data['doctor_sign'][0] === 'data:image/png;base64') {
			$encoded_image = $data['doctor_sign'][1];
			$decoded_image = base64_decode($encoded_image);
			$img_path = 'upload/temporary_orders/doctor_sign/'.uniqID($_SESSION['user']['id'], true).'.png';
			if(!is_dir('upload/temporary_orders/doctor_sign/')) {//检测是否存在
				//不存在
				mkdir('upload/temporary_orders/doctor_sign/', 0777, true);
			}
			file_put_contents($img_path, $decoded_image);
			$data['doctor_sign'] = $img_path;
		}else{
			$data['doctor_sign'] = 'upload/temporary_orders/doctor_sign/none_sign.png';
		}
		if ($this->orders_model->add_temporary_orders($data)) {
			$result['state'] = 1;
			$result['status'] = true;
			$result['msg'] = '临时医嘱添加成功';
		}else{
			$result['state'] = 0;
			$result['status'] = false;
			$result['msg'] = '服务器错误，临时医嘱添加失败';
		}
		$this->returnResult($result);
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
		$data['start_time'] = $this->input->post_get('start_time');
		$data['start_create_time'] = time();
		$return['state'] = $this->orders_model->add_standing_orders($data) ? '1' : '0';

		$data['start_doctor_sign'] = explode(",", $this->input->post_get('doctor_sign'));
		if (!$data['start_doctor_name']) {
			$data['start_doctor_name'] = $_SESSION['user']['name'];
		}
		if (!is_numeric($data['start_time'])) {//不是时间戳
			$data['start_time'] = strtotime($data['start_time']);
		}
		if ($data['start_doctor_sign'] && $data['start_doctor_sign'][0] === 'data:image/png;base64') {
			$encoded_image = $data['start_doctor_sign'][1];
			$decoded_image = base64_decode($encoded_image);
			$img_path = 'upload/standing_orders/doctor_sign/'.uniqID($_SESSION['user']['id'], true).'.png';
			if(!is_dir('upload/standing_orders/doctor_sign/')) {//检测是否存在
				//不存在
				mkdir('upload/standing_orders/doctor_sign/', 0777, true);
			}
			file_put_contents($img_path, $decoded_image);
			$data['start_doctor_sign'] = $img_path;
		}else{
			$data['start_doctor_sign'] = 'upload/standing_orders/doctor_sign/none_sign.png';
		}
		if ($this->orders_model->add_standing_orders($data)) {
			$result['state'] = 1;
			$result['status'] = true;
			$result['msg'] = '长期医嘱添加成功';
		}else{
			$result['state'] = 0;
			$result['status'] = false;
			$result['msg'] = '服务器错误，长期医嘱添加失败';
		}
		$this->returnResult($result);
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
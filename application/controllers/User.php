<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->model('user_model');
		$this->load->model('doctor_model');
		$this->load->model('nurse_model');
		$this->load->model('patient_model');
	}
	//登录页面
	public function index(){
		$this->load->view('login.html');
	}

	//登录
	public function login(){
		//$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$user_id = $this->input->post('user_id');
		$user_pwd = md5($this->input->post('user_pwd'));

		if ($user = $this->user_model->get_user_by_id_and_pwd($user_id, $user_pwd)) {
			$user_session['id'] = $user_id;
			switch ($user['user_type']) {
				case '1':
					$user_detail = $this->doctor_model->get_doctor_by_id($user['person_id']);
					$user_session['name'] = $user_detail['doc_name'];
					$user_session['role'] = '医生';
					break;
				case '2':
					$user_detail = $this->nurse_model->get_nurse_by_id($user['person_id']);
					$user_session['name'] = $user_detail['nur_name'];
					$user_session['role'] = '护士';
					break;
				default:
					$user_detail = null;
					$user_session['name'] = $user['user_id'];
					$user_session['role'] = '管理员';
					break;
			}
			
			//添加session
			$_SESSION['user'] = $user_session;

			$result['user_detail'] = $user_detail;
			$result['state'] = 1;
			$result['role'] = $user['user_type'];
		}else{
			$result['state'] = 0;
		}
		echo json_encode($result);
	}
	//app登录
	public function login_for_app(){
		//$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$user_id = $this->input->post('user_id');
		$user_pwd = md5($this->input->post('user_pwd'));

		if ($user = $this->user_model->get_user_by_id_and_pwd($user_id, $user_pwd)) {
			$user_session['id'] = $user_id;
			switch ($user['user_type']) {
				case '1':
					$user_detail = $this->doctor_model->get_doctor_by_id($user['person_id']);
					$user_session['name'] = $user_detail['doc_name'];
					$user_session['role'] = '医生';
					break;
				case '2':
					$user_detail = $this->nurse_model->get_nurse_by_id($user['person_id']);
					$user_detail['doc_name'] = $user_detail['nur_name'];
					$user_session['name'] = $user_detail['nur_name'];
					$user_session['role'] = '护士';
					break;
				default:
					$user_detail = null;
					$user_session['name'] = $user['user_id'];
					$user_session['role'] = '管理员';
					break;
			}
			
			//添加session
			$_SESSION['user'] = $user_session;

			$result['user_detail'] = $user_detail;
			$result['state'] = 1;
			$result['role'] = $user['user_type'];
		}else{
			$result['state'] = 0;
		}
		echo json_encode($result);
	}


	//注销
	public function logout(){
		session_unset();
		redirect('user');
	}


	//用户获取病人信息
	public function get_patient_info(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$patient_id = $this->input->post_get('patient_id');

		if ($patient = $this->patient_model->get_patient_by_id($patient_id)) {
			$result['patient'] = $patient;
			$result['state'] = 1;
		}else{
			$result['state'] = 0;
		}
		echo json_encode($result);
	}

	
}
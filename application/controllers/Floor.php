<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Floor extends Home_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('room_model');
		$this->load->model('doctor_model');
		$this->load->model('nurse_model');
		$this->load->model('patient_model');
		$this->load->model('facility_model');
	}

	public function index($department = 'cardiology')
	{
		//没有其他楼,先定为心内科楼cardiology
		$department = 'cardiology';
		$this->load->view('header.inc.html');
		$this->load->view($department.'/floor.html');
		$this->load->view('footer.inc.html');
	}

	public function show_floor($department = 'cardiology', $floot_num = 0)
	{

		$floot_num += 0;
		//没有其他楼层，先定为5
		$floot_num = 5;
		//没有其他楼,先定为心内科楼cardiology
		$department = 'cardiology';
		$page = $department . "/floor{$floot_num}.html";

		$this->load->view('header.inc.html');
		$this->load->view($page);
		$this->load->view('footer.inc.html');
	}

	public function get_facility_info($fac_id){
		$this->output->set_header('Content-Type: text/html; charset=utf-8');
		$result = $this->facility_model->get_facility_by_id($fac_id);
		if ($result) {
			$class = $result[0]['fac_state'] == "正常" ? "text-success" : "text-danger";
			$html = "<span>";
			$html .= "状态：<span class='$class'>{$result[0]['fac_state']}</span>";
			$html .= "</span>";
			echo $html;
		} else {
			echo '未录入';
		}
		return;
	}

	public function get_room_info($room_id){
		$this->output->set_header('Content-Type: text/html; charset=utf-8');
		$room = $this->room_model->get_room_by_id($room_id);
		if ($room) {
			switch ($room['rt_id']) {
				case '1':
					$html = $this->_get_director_room_info($room_id);
					break;
				case '2':
					$html = $this->_get_head_nurse_dutyroom_info($room_id);
					break;
				case '3':
					$html = $this->_get_nurse_dutyroom_info($room_id);
					break;
				case '4':
					$html = $this->_get_doctor_dutyroom_info($room_id);
					break;
				case '5':
					$html = $this->_get_nurse_station_info($room_id);
					break;
				case '6':
					$html = $this->_get_medicine_room_info($room_id);
					break;
				case '7':
					$html = $this->_get_ccu_room_info($room_id);
					break;
				case '8':
					$html = $this->_get_sickroom_info($room_id);
					break;
				default:
					$html = '未定义房间类型';
					break;
			}
		}else{
			$html = '没有该房间';
		}
		echo $html;
		return;
	}

	private function _get_director_room_info($room_id){
		$doctors = $this->doctor_model->get_doctor_by_roomid($room_id);
		if ($doctors) {
			$i = 1;
			$html = "<span class='role-span'>主任：</span><div class='each-div'>";
			foreach ($doctors as $doctor) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_doctor_info({$doctor['doc_id']})'>{$doctor['doc_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_head_nurse_dutyroom_info($room_id){
		$nurses = $this->nurse_model->get_nurse_by_roomid($room_id);
		if ($nurses) {
			$i = 1;
			$html = "<span class='role-span'>值班护士：</span><div class='each-div'>";
			foreach ($nurses as $nurse) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_nurse_info({$nurse['nur_id']})'>{$nurse['nur_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_nurse_dutyroom_info($room_id){
		$nurses = $this->nurse_model->get_nurse_by_roomid($room_id);
		if ($nurses) {
			$i = 1;
			$html = "<span class='role-span'>值班护士：</span><div class='each-div'>";
			foreach ($nurses as $nurse) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_nurse_info({$nurse['nur_id']})'>{$nurse['nur_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_doctor_dutyroom_info($room_id){
		$doctors = $this->doctor_model->get_doctor_by_roomid($room_id);
		if ($doctors) {
			$i = 1;
			$html = "<span class='role-span'>值班医生：</span><div class='each-div'>";
			foreach ($doctors as $doctor) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_doctor_info({$doctor['doc_id']})'>{$doctor['doc_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_nurse_station_info($room_id){
		$nurses = $this->nurse_model->get_nurse_by_roomid($room_id);
		if ($nurses) {
			$i = 1;
			$html = "<span class='role-span'>护士：</span><div class='each-div'>";
			foreach ($nurses as $nurse) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_nurse_info({$nurse['nur_id']})'>{$nurse['nur_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_medicine_room_info($room_id){
		$doctors = $this->doctor_model->get_doctor_by_roomid($room_id);
		
		if ($doctors) {
			$i = 1;
			$html = "<span class='role-span'>配药师：</span><div class='each-div'>";
			foreach ($doctors as $doctor) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_doctor_info({$doctor['doc_id']})'>{$doctor['doc_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂时无人";
		}
		
	}
	private function _get_ccu_room_info($room_id){
		$patients = $this->patient_model->get_patients_by_roomid($room_id);
		if ($patients) {
			$i = 1;
			$html = "<span class='role-span'>患者：</span><div class='each-div'>";
			foreach ($patients as $patient) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_patient_info({$patient['pat_id']})'>{$patient['pat_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂无患者";
		}
		
	}
	private function _get_sickroom_info($room_id){
		$patients = $this->patient_model->get_patients_by_roomid($room_id);
		if ($patients) {
			$html = "<span class='role-span'>患者：</span><div class='each-div'>";
			$i = 1;
			foreach ($patients as $patient) {
				$html .= "<button class='btn btn-xs btn-success' onclick='show_patient_info({$patient['pat_id']})'>{$patient['pat_name']}</button>";
				if ($i%3 == 0) {
					$html .= "<br>";
				}
				$i++;
			}
			$html .= "</div>";
			return $html;
		}else{
			return "暂无患者";
		}
		
	}



}

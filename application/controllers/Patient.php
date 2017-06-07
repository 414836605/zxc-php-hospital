<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient extends Base_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model('patient_model');
		$this->load->model('orders_model');
	}

	public function index(){
		$this->load->view('header.inc.html');
		$this->load->view('patient.html');
		$this->load->view('footer.inc.html');
	}

	public function add_patient(){
		$this->load->view('header.inc.html');
		$this->load->view('patient/add_patient.html');
		$this->load->view('footer.inc.html');
	}

	

	public function add_medical_record(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$data['pat_id'] = $this->input->post_get('pat_id');
		$data['content'] = $this->input->post_get('content');
		$data['doctor'] = $this->input->post_get('doctor');
		$data['time'] = time();

		$return['state'] = $this->patient_model->add_medical_record($data) ? '1' : '0';

		echo json_encode($return);
		return;
	}

	public function pay(){
		$pat_id = $this->input->post('pat_id',true);
		$payment_amount = $this->input->post('payment_amount',true);
		if (!$pat_id) {
			$result['status'] = false;
			$result['msg'] = '未选择患者';
			$this->returnResult($result);
		}
		if (!is_numeric($payment_amount)) {
			$result['status'] = false;
			$result['msg'] = '金额必须是数字';
			$this->returnResult($result);
		}
		if($this->patient_model->pay($pat_id,$payment_amount)){
			$result['status'] = true;
			$result['msg'] = '缴费成功';
			$this->returnResult($result);
		}else{
			$result['status'] = false;
			$result['msg'] = '后台发生错误，缴费失败';
			$this->returnResult($result);
		}
	}

	//datatables获取患者
	public function get_patient(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$patients = $this->patient_model->get_patient_and_page($this->input->get('start', true), $this->input->get('length', true));
		$total = $this->patient_model->get_patient_num();

		$result['draw'] = $this->input->get('draw', true);
		$result['recordsTotal'] = $total;
		$result['recordsFiltered'] = $total;
		$result['data'] = $patients;
		$result['error'] = '';
		echo json_encode($result);
		return;
	}
	//添加患者
	public function do_add_patient(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$patient = $this->_process_post_patient($this->input->post());
		$return['state'] = $this->patient_model->add_patient($patient) ? '1' : '0';
		$return['patient'] = $patient;
		echo json_encode($return);
		return;
	}

	//通过id获取病人信息
	public function get_patient_by_id($id){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$patient = $this->patient_model->get_patient_by_id($id);
		$patient['temporary_orders'] = $this->_handle_temporary_orders($id);
		$patient['standing_orders'] = $this->_handle_standing_orders($id);
		$patient['medical_history'] = $this->_handle_medical_historys($patient['medical_history']);
		$patient['medical_record'] = $this->_handle_medical_records($patient['medical_record']);
		echo json_encode($patient);
		return;
	}

	
	//处理住院病历
	private function _handle_medical_historys($mhs){
	    $simple_mhs = simplexml_load_string($mhs, null, LIBXML_NOCDATA);

	    $html = "<ul class='list-group'><li class='list-group-item active'>住院病历</li>";
	    foreach ($simple_mhs as $mh) {
		    $html .= "<li class='list-group-item'>";
	    	$html .= "<h4 class='list-group-item-heading my-list-group-item-heading'>{$mh->name}</h4>";
	    	$content = null;
		    if ($mh->content->section->count() != 0) {
			    $sections = $mh->content->section;
			    foreach ($sections as $section){
			    	$content .= "&nbsp;&nbsp;&nbsp;&nbsp;<b>" . trim((string)$section->name) . "：</b>" . trim((string)$section->content) . "<br>";
			    }
		    }else{
			    $content = trim((string)$mh->content);
		    }
		    $html .= "<p class='list-group-item-text'>{$content}</p>";
		    $html .= "</li>";
	    }
	    
		$html .= "</ul>";
		return $html;

	}
	//处理病程记录
	private function _handle_medical_records($mrs){
	    $simple_mrs = simplexml_load_string($mrs, null, LIBXML_NOCDATA);
	    $html = "<ul class='list-group'><li class='list-group-item active'>病程记录</li>";

	    foreach ($simple_mrs as $mr) {
	    	$time = date('Y-m-d H:i:s', (string)$mr->time);
		    $html .= "<li class='list-group-item'>";
	    	$html .= "<h4 class='list-group-item-heading my-list-group-item-heading'>{$time}</h4>";
		    $html .= "<p class='list-group-item-text'>{$mr->content}</p>";
		    $html .= "<p class='list-group-item-text'>医生：{$mr->doctor}</p>";
		    $html .= "</li>";
	    }
	    
		$html .= "</ul>";
		return $html;
	}
	//处理临时医嘱
	private function _handle_temporary_orders($id){
		$temporary_orders = $this->orders_model->get_temporary_orders_by_patid($id);
		
		$html = "<ul class='list-group'><li class='list-group-item active'>临时医嘱</li><li class='list-group-item'><table class='table table-bordered table-condensed'>";
		$html .= "<tr>";
		$html .= "<th>临时医嘱内容</th>";
		$html .= "<th>创建时间</th>";
		$html .= "<th>执行时间</th>";
		$html .= "<th>医生姓名</th>";
		$html .= "<th>护士姓名</th>";
		$html .= "</tr>";

		foreach ($temporary_orders as $order) {
			$order['create_time'] = date('Y-m-d H:i:s', $order['create_time']);
			$order['execute_time'] = $order['execute_time'] == 0 ? null : date('Y-m-d H:i:s', $order['execute_time']);

			$html .= "<tr>";
			$html .= "<td>{$order['to_content']}</td>";
			$html .= "<td>{$order['create_time']}</td>";
			$html .= "<td>{$order['execute_time']}</td>";
			$html .= "<td>{$order['doctor_name']}</td>";
			$html .= "<td>{$order['nurse_name']}</td>";
			$html .= "</tr>";
		}

		$html .= "</table></li></ul>";
		return $html;
	}

	//处理长期医嘱
	private function _handle_standing_orders($id){
		$standing_orders = $this->orders_model->get_standing_orders_by_patid($id);
		
		$html = "<ul class='list-group'><li class='list-group-item active'>长期医嘱</li><li class='list-group-item'><table class='table table-bordered table-condensed'>";
		$html .= "<tr><th rowspan='2'>长期医嘱内容</th><th colspan='4' class='text-center'>开始</th><th colspan='4' class='text-center'>停止</th></tr>";
		$html .= "<tr>";
		$html .= "<th style='width:8%;'>创建</th>";
		$html .= "<th style='width:8%;'>执行</th>";
		$html .= "<th style='width:8%;'>医生</th>";
		$html .= "<th style='width:8%;'>护士</th>";
		$html .= "<th style='width:8%;'>创建</th>";
		$html .= "<th style='width:8%;'>执行</th>";
		$html .= "<th style='width:8%;'>医生</th>";
		$html .= "<th style='width:8%;'>护士</th>";
		$html .= "</tr>";

		foreach ($standing_orders as $order) {
			$order['start_create_time'] = date('Y-m-d H:i:s', $order['start_create_time']);
			$order['start_time'] = $order['start_time'] == 0 ? null : date('Y-m-d H:i:s', $order['start_time']);
			$order['stop_create_time'] = $order['stop_create_time'] == 0 ? null : date('Y-m-d H:i:s', $order['stop_create_time']);
			$order['stop_time'] = $order['stop_time'] == 0 ? null : date('Y-m-d H:i:s', $order['stop_time']);
			
			$html .= "<tr>";
			$html .= "<td>{$order['so_content']}</td>";
			$html .= "<td>{$order['start_create_time']}</td>";
			$html .= "<td>{$order['start_time']}</td>";
			$html .= "<td>{$order['start_doctor_name']}</td>";
			$html .= "<td>{$order['start_nurse_name']}</td>";
			$html .= "<td>{$order['stop_create_time']}</td>";
			$html .= "<td>{$order['stop_time']}</td>";
			$html .= "<td>{$order['stop_doctor_name']}</td>";
			$html .= "<td>{$order['stop_nurse_name']}</td>";
			$html .= "</tr>";
		}
		$html .= "</table></li></ul>";
		return $html;
	}

	
	//app获取住院病历
	public function get_medical_history(){
		//$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$pat_id = $this->input->post_get('pat_id', true);
		//如果没患者或没病历
		$patient = $this->patient_model->get_patient_by_id($pat_id);
		$simple_mhs = simplexml_load_string($patient['medical_history'], null, LIBXML_NOCDATA);
		if (! ($patient && property_exists($simple_mhs, 'mh'))) {
			$result['state'] = 0;
			echo json_encode($result);
			return;
		}
		$result['medical_history'] = $this->_forapp_handle_medical_history($simple_mhs);
		$result['state'] = 1;
		echo json_encode($result);
		return;
	}

	//app获取病程记录
	public function get_medical_record(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');
		$pat_id = $this->input->post_get('pat_id', true);
		//如果没患者或病历
		$patient = $this->patient_model->get_patient_by_id($pat_id);
		$simple_mrs = simplexml_load_string($patient['medical_record'], null, LIBXML_NOCDATA);
		if (! ($patient && property_exists($simple_mrs, 'mr'))) {
			$result['state'] = 0;
			echo json_encode($result);
			return;
		}
		$result['medical_record'] = $this->_forapp_handle_medical_record($simple_mrs);
		$result['state'] = 1;
		echo json_encode($result);
		return;
	}

	//app获取临时医嘱
	public function get_temporary_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$pat_id = $this->input->post_get('pat_id', true);

		if (! $temporary_orders = $this->orders_model->get_temporary_orders_by_patid($pat_id)){
			$result['state'] = 0;
			echo json_encode($result);
			return;
		}
		for ($i=0; $i < count($temporary_orders); $i++) { 
			$temporary_orders[$i]['execute_time'] = $temporary_orders[$i]['execute_time'] == 0 ? '' : $temporary_orders[$i]['execute_time'];
		}

		$result['orders'] = $temporary_orders;
		$result['state'] = 1;
		echo json_encode($result);
		return;
	}

	//app获取长期医嘱
	public function get_standing_orders(){
		$this->output->set_header('Content-Type: application/json; charset=utf-8');

		$pat_id = $this->input->post_get('pat_id', true);
		if (! $standing_orders = $this->orders_model->get_standing_orders_by_patid($pat_id)){
			$result['state'] = 0;
			echo json_encode($result);
			return;
		}
		$result['orders'] = $standing_orders;
		$result['state'] = 1;
		echo json_encode($result);
		return;
	}

	//为app处理住院病历
	private function _forapp_handle_medical_history($simple_mhs){
	    
	    $result = '';
    	foreach ($simple_mhs as $mh) {
		    $content = null;
		    $ename =  trim((string)$mh->ename);
		    if ($mh->content->section->count() != 0) {
			    $sections = $mh->content->section;
			    foreach ($sections as $section){
				    $content .= "<b>&nbsp;&nbsp;&nbsp;&nbsp;" . trim((string)$section->name) . "：</b>" . trim((string)$section->content) . "<br/>";
			    }
		    }else{
			    $content = trim((string)$mh->content);
		    }

		    $result[$ename] = $content;
	    }
	    return $result;
	}

	//为app处理病程记录
	private function _forapp_handle_medical_record($simple_mrs){
		$result = null;
		$i = 0;
		foreach ($simple_mrs as $mr) {
			$result[$i]['time'] =  date('Y年m月d日 H:i', trim((string)$mr->time));

			$result[$i]['content'] = trim((string)$mr->content);
			$i++;
		}
		return $result;
	}

	/**
	 * 处理post传来的patient
	 * @param  [array] $patient [患者信息]
	 * @return [array]          [处理后患者信息]
	 */
	private function _process_post_patient($patient){
		// 生成pat_id
		$file_name = 'hospital_info.json';
		/*!以后要加文件锁，防止重复读*/
		$hinfo_json = file_get_contents($file_name);
		$hinfo = json_decode($hinfo_json);
		// $today = date('ymd',time());
		$today = date('d',time());
		if ($hinfo->today == $today) {
			//患者数大于9999提示'患者数超限'，退出
			$hinfo->today_patient > 9999 AND exit('患者数超限');
			$patient['pat_id'] = $today . sprintf("%04d", $hinfo->today_patient);
			$hinfo->today_patient++;
		}else{
			$hinfo->today = $today;
			$hinfo->today_patient = 0;
			$patient['pat_id'] = $today . sprintf("%04d", $hinfo->today_patient);
			$hinfo->today_patient++;
		}
		file_put_contents($file_name, json_encode($hinfo));

		// 生成admission_date
		$patient['admission_date'] = time();

		// 处理birthplace
		$patient['birthplace'] = implode('-', $patient['birthplace']);
		
		//默认medical_history和medical_record
		$patient['medical_history'] = '<?xml version="1.0" encoding="UTF-8"?><medical_historys></medical_historys>';
		$patient['medical_record'] = '<?xml version="1.0" encoding="UTF-8"?><medical_record></medical_record>';

		return $patient;
	}
	
}

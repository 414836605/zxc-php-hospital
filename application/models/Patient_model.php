<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Patient_model extends CI_Model {
	const TBL_PATIENT = 'patient';
	const TBL_ROOM = 'room';
	/*
	*查询患者数
	*/
	public function get_patient_num(){
		return $this->db->count_all(self::TBL_PATIENT);
	}

	/*
	*通过id查询患者
	*/
	public function get_patient_by_id($id){
		return $this->db->where('pat_id', $id)->get(self::TBL_PATIENT)->row_array();
	}

	/*
	*分页查询患者
	*/
	public function get_patient_and_page($offset, $limit){
		$this->db->select('*');
		$this->db->from(self::TBL_PATIENT);
		$this->db->join(self::TBL_ROOM, self::TBL_PATIENT . ".room_id = " . self::TBL_ROOM . ".room_id", 'left');
		$this->db->limit($limit, $offset);
		return $this->db->get()->result_array();
	}

	/*
	*通过roomid查询患者
	*/
	public function get_patients_by_roomid($roomid){
		return $this->db->where('room_id', $roomid)->get(self::TBL_PATIENT)->result_array();
	}

	public function add_patient($patient){
		return $this->db->insert(self::TBL_PATIENT, $patient);
	}

	public function add_medical_record($data){
		$result = $this->get_patient_by_id($data['pat_id']);
		$mrs = $result['medical_record'];
		$simple_mrs = simplexml_load_string($mrs, null, LIBXML_NOCDATA);
		$new_mr = $simple_mrs->addChild('mr');
		$new_mr->addChild('time', $data['time']);
		$new_mr->addChild('content', $data['content']);
		$new_mr->addChild('doctor', $data['doctor']);
		$new_xml = $simple_mrs->asXML();
		return $this->db->update(self::TBL_PATIENT, array('medical_record' => $new_xml), array('pat_id' => $data['pat_id']));
	}
}
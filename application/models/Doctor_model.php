<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Doctor_model extends CI_Model {
	const TBL_DOCTOR = 'doctor';

	/*
	*查询医生数
	*/
	public function get_doctor_num(){
		return $this->db->count_all(self::TBL_DOCTOR);
	}

	/*
	*通过id查询医生
	*/
	public function get_doctor_by_id($id){
		return $this->db->where('doc_id', $id)->get(self::TBL_DOCTOR)->row_array();
	}

	/*
	*通过房间id查询医生
	*/
	public function get_doctor_by_roomid($roomid){
		return $this->db->where('room_id', $roomid)->get(self::TBL_DOCTOR)->result_array();
	}

	/*
	*分页查询医生
	*/
	public function get_doctors_and_page($offset, $limit){
		return $this->db->limit($limit, $offset)->get(self::TBL_DOCTOR)->result_array();
	} 
}
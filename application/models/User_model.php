<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model {
	const TBL_USER = 'user';

	/*
	*查询医生数
	*/
	// public function get_doctor_num(){
	// 	return $this->db->count_all(self::TBL_DOCTOR);
	// }

	/*
	*通过id和pwd查询用户
	*/
	public function get_user_by_id_and_pwd($id,$pwd){
		return $this->db->where(array('user_id' => $id, 'user_pwd' => $pwd))->get(self::TBL_USER)->row_array();
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
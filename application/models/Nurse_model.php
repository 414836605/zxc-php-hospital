<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Nurse_model extends CI_Model {
	const TBL_NURSE = 'nurse';

	/*
	*查询护士数
	*/
	public function get_nurse_num(){
		return $this->db->count_all(self::TBL_NURSE);
	}

	/*
	*通过id查询护士
	*/
	public function get_nurse_by_id($id){
		return $this->db->where('nur_id', $id)->get(self::TBL_NURSE)->row_array();
	}

	/*
	*通过房间id查询护士
	*/
	public function get_nurse_by_roomid($roomid){
		return $this->db->where('room_id', $roomid)->get(self::TBL_NURSE)->result_array();
	}

	/*
	*分页查询护士
	*/
	public function get_nurse_and_page($offset, $limit){
		return $this->db->limit($limit, $offset)->get(self::TBL_NURSE)->result_array();
	} 
}
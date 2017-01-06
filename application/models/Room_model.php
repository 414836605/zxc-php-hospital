<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Room_model extends CI_Model {
	const TBL_ROOM = 'room';

	/*
	*通过id查房间的信息
	*/
	public function get_room_by_id($id){
		return $this->db->where('room_id', $id)->get(self::TBL_ROOM)->row_array();
	}
	/*
	*通过房间id查房间的信息
	*/
	public function get_doctor_by_roomid($roomid){

	}
	/*
	*通过id查房间的信息
	*/
	
}
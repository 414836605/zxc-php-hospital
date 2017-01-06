<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Orders_model extends CI_Model {
	const TBL_TEMPORARY_ORDERS = 'temporary_orders';
	const TBL_STANDING_ORDERS = 'standing_orders';

	/*
	*查询临时医嘱通过患者id
	*/
	public function get_temporary_orders_by_patid($pat_id){
		return $this->db->where('pat_id', $pat_id)->get(self::TBL_TEMPORARY_ORDERS)->result_array();
	}

	/*
	*查询长期医嘱通过患者id
	*/
	public function get_standing_orders_by_patid($pat_id){
		return $this->db->where('pat_id', $pat_id)->get(self::TBL_STANDING_ORDERS)->result_array();
	}

	/*
	*添加临时医嘱
	*/
	public function add_temporary_orders($data){
		return $this->db->insert(self::TBL_TEMPORARY_ORDERS, $data);
	}

	/*
	*执行临时医嘱
	*/
	public function execute_temporary_orders($data){
		return $this->db->where('to_id', $data['to_id'])->update(self::TBL_TEMPORARY_ORDERS, $data);
	}

	/*
	*删除临时医嘱
	*/
	public function delete_temporary_orders($id){
		return $this->db->delete(self::TBL_TEMPORARY_ORDERS, array('to_id' => $id));
	}

	/*
	*添加长期医嘱
	*/
	public function add_standing_orders($data){
		return $this->db->insert(self::TBL_STANDING_ORDERS, $data);
	}
	/*
	*停止长期医嘱
	*/
	public function stop_standing_orders($data){
		return $this->db->where('so_id', $data['so_id'])->update(self::TBL_STANDING_ORDERS, $data);
	}
	
}
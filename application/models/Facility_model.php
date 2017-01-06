<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Facility_model extends CI_Model {
	const TBL_FACILITY = 'facility';

	/*
	*通过id查设施
	*/
	public function get_facility_by_id($id){
		return $this->db->where('fac_id', $id)->get(self::TBL_FACILITY)->result_array();
	}

	
}
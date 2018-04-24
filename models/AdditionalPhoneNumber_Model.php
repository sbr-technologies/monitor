<?php

class AdditionalPhoneNumber_Model extends CI_Model { 

	/**
	 * Added for fetching additional phone 
	 * number
	 * @param int $business_id  //Target business id of the phone numbers
	 * @param array $result     //all the list of additional phone numbers
	 */
	public function fetchAdditionalPhoneNumber($business_id){
	 	$this->db->select('additional_phone_number');
        $this->db->from('additional_phone_number');
        $this->db->where('business_id', $business_id);
        $result = $this->db->get()->result_array();
        return $result;
	}
	
}
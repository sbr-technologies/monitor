<?php

class AdminLogin_model extends CI_Model {

	public $last_login;

	/**
	 * Added for updating admin
	 * login time
	 * @param  datetime $last_login_time //Last login time of the admin
	 */
	public function update_login_time() {
		$this->last_login = date('Y-m-d H:i:s');
		$this->db->update('admin_login', $this, array('id' => 1));
	}

	/**
	 * Added for fetching admin details
	 * @return array //Containing admin details
	 */
	public function check_admin() {
		$result = $this->db->get_where('admin_login', array('id' => 1));
		return $result->row_array();
	}

	/**
	 * Added for getting
	 * admin details
	 * @param  int $id //Added for admin id, by default the id is 1
	 * @return array   //The details of the admin
	 */
	public function get_details_admin($id = 1) {
		$query = $this->db->get_where('admin_login', array('id' => $id));
		return $query->row_array();
	}

	/**
	 * Added for updating admin
	 * data information
	 * @param  array $post_data
	 * @param  int $id
	 */
	public function update_admin($post_data, $id) {
		$this->db->where('id', $id);
		$this->db->update('admin_login', $post_data);
	}
}
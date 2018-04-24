<?php

class AvailabilityMaster_model extends CI_Model {

    /**
     * Added for inserting
     * new availability master
     * @param array $post_data
     * @return int
     */
    public function add_availability_master($post_data) {
        $this->db->insert('availability_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating availability
     * master information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_availability_master($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('availability_master', $post_data);
    }

    /**
     * Added for getting the datils of a availability master based on
     * the $result_type parameter
     * @param  string $result_type        //If number it will return the number record for a name
     * @param  string $availability_master_name  //Name of the target availability master
     * @return array/int                  //Array for the result or count the number of records
     */
    public function get_details_availability_master_name($result_type = "", $availability_master_name) {
        if ($result_type === "number") {
            $this->db->select('count(id) AS availability_master_count');
        } else {
            $this->db->select('*');
        }
        $this->db->where('status !=', "3");
        $this->db->where('name', $availability_master_name);
        $this->db->from('availability_master');
        $result = $this->db->get()->result_array();
        if ($result_type === "number") {
            return $result[0]['availability_master_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all active
     * availability master
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchAvailabilityList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('AM.id,AM.name,AM.vendor_count,AM.status,AM.updated_at');
        $this->db->from('availability_master AM');
        $this->db->where('AM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['availability_name']) && $search_array['availability_name'] !== "") {
                $this->db->like('AM.name', $search_array['availability_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('AM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('AM.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'availability_name') {
            $this->db->order_by('AM.name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('AM.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('AM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching availabilities
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $availability_id   //Added for availability id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchAvailabilityInfo($flag, $availability_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(AM.id) AS total_availability');
        } else {
            $this->db->select('AM.*');
        }
        if ($availability_id !== 0) {
            $this->db->where('AM.id', $availability_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['availability_name']) && $search_array['availability_name'] !== "") {
                $this->db->like('AM.name', $search_array['availability_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('AM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('AM.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('AM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('AM.updated_at', 'desc');
        }

        $this->db->from('availability_master AM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['total_availability'];
        } else {
            return $result;
        }
    }

    /**
     * Get number of availability manager for 
     * a availability name,Mainly added for checking the 
     * availability name exist or not
     * @param  string $name     //Name of the availability
     * @return int              //Number of results
     */
    public function get_availability_manager_name($name) {
        $this->db->select('count(id) AS availability_count');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('availability_master');
        $result = $this->db->get()->result_array();
        return $result[0]['availability_count'];
    }

    /**
     * Added for selecting id of availability manager
     * with respect to name
     * @param string $name
     * @return array
     */
    public function get_availability_manager_name_edit($name) {
        $this->db->select('id');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('availability_master');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating availability
     * information with business id
     * @param array $availability_business_info
     * @param int $business_id
     */
    public function update_availability_with_business($availability_business_info = array(), $business_id = 0) {
        /* ---Updating existing availability information start--- */
        $this->db->select('availability_master_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('availability_business');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $availability_master_info = $this->db->get_where('availability_master', array('id' => $value["availability_master_id"]))->result_array();
                if ($availability_master_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $availability_master_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["availability_master_id"]);
                $this->db->update('availability_master', $update_data);
            }
        }
        /* ---Updating existing availability information ends--- */
        $this->db->delete('availability_business', array('business_id' => $business_id));
        $this->db->insert_batch('availability_business', $availability_business_info);
        foreach ($availability_business_info as $value) {
            $availability_master_info = $this->db->get_where('availability_master', array('id' => $value["availability_master_id"]))->result_array();
            $update_data["vendor_count"] = $availability_master_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["availability_master_id"]);
            $this->db->update('availability_master', $update_data);
        }
    }

    /**
     * Added for fetching all availability 
     * based on business id
     * @param int $business_id
     * @return array
     */
    public function availability_info_business($business_id) {
        $this->db->select('availability_master_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('availability_business');
        $result = $this->db->get()->result_array();
        return $result;
    }
}

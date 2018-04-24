<?php

class BusinessCharacteristics_Model extends CI_Model {
 
    /**
     * Added for inserting
     * new business characteristics
     * @param array $post_data
     * @return int
     */
    public function add_business_characterstics($post_data) {
        $this->db->insert('business_characteristics_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating business
     * characteristics master information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_business_characterstics($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('business_characteristics_master', $post_data);
    }

    /**
     * Added for getting the details of a business characteristics based on
     * the $result_type parameter
     * @param  string $result_type        //If number it will return the number record for a name
     * @param  string $business_char  //Name of the target business characteristics  
     * @return array/int                  //Array for the result or count the number of records
     */
    public function get_details_business_characterstics_name($result_type = "", $business_char) {
        if ($result_type === "number") {
            $this->db->select('count(id) AS business_characteristics_master_count');
        } else {  
            $this->db->select('*');
        }
        $this->db->where('status !=', "3");
        $this->db->where('name', $business_char);
        $this->db->from('business_characteristics_master');
        $result = $this->db->get()->result_array();
        if ($result_type === "number") {
            return $result[0]['business_characteristics_master_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all active
     * business characteristics
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query  
     */
    public function fetchBusinessCharacteristicsList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('BCM.id,BCM.name,BCM.vendor_count,BCM.status,BCM.updated_at');
        $this->db->from('business_characteristics_master BCM');
        $this->db->where('BCM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['business_characterstics_name']) && $search_array['business_characterstics_name'] !== "") {
                $this->db->like('BCM.name', $search_array['business_characterstics_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BCM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('BCM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('BCM.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('BCM.name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('BCM.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('BCM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching business 
     * characteristics area
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $business_characterstics_id   //Added for business characteristics id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchBusinessCharacteristicsInfo($flag, $business_characterstics_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(BCM.id) AS business_characterstics_count');
        } else {
            $this->db->select('BCM.*');
        }
        if ($business_characterstics_id !== 0) {
            $this->db->where('BCM.id', $business_characterstics_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['business_characterstics_name']) && $search_array['business_characterstics_name'] !== "") {
                $this->db->like('BCM.name', $search_array['business_characterstics_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BCM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('BCM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('BCM.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('BCM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('BCM.updated_at', 'desc');
        }

        $this->db->from('business_characteristics_master BCM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['business_characterstics_count'];
        } else {
            return $result;
        }
    }

    /**
     * Get number of business characteristics for 
     * a business characteristics name,Mainly added for checking the 
     * business characteristics name exist or not
     * @param  string $name     //Name of the business characteristics
     * @return int              //Number of results
     */
    public function get_business_characteristics_name($name) {
        $this->db->select('count(id) AS total_result');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('business_characteristics_master');
        $result = $this->db->get()->result_array();
        return $result[0]['total_result'];
    }

    /**
     * Added for fetching business characteristics details
     * details by name
     * @param  string $name     //Name of the business characteristic
     * @return array            //Fetching the details of the business characteristic
     */
    public function get_business_characteristics_details_name($name) {
        $this->db->select('*');
        $this->db->from('business_characteristics_master');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating business characteristics
     * information with business id
     * @param array $business_characterstics_business_info
     * @param int $business_id
     */
    public function update_business_characterstics_business($business_characterstics_business_info = array(), $business_id = 0) {
        /* ---Updating existing business characteristics information start--- */
        $this->db->select('business_characteristics_master_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('business_characterstics_business');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $business_characteristics_master_info = $this->db->get_where('business_characteristics_master', array('id' => $value["business_characteristics_master_id"]))->result_array();
                if ($business_characteristics_master_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $business_characteristics_master_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["business_characteristics_master_id"]);
                $this->db->update('business_characteristics_master', $update_data);
            }
        }
        /* ---Updating existing business characteristics information ends--- */
        $this->db->delete('business_characterstics_business', array('business_id' => $business_id));
        $this->db->insert_batch('business_characterstics_business', $business_characterstics_business_info);
        foreach ($business_characterstics_business_info as $value) {
            $business_characteristics_master_info = $this->db->get_where('business_characteristics_master', array('id' => $value["business_characteristics_master_id"]))->result_array();
            $update_data["vendor_count"] = $business_characteristics_master_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["business_characteristics_master_id"]);
            $this->db->update('business_characteristics_master', $update_data);
        }
    }

    /**
     * Added for fetching all business 
     * characteristics information based on
     * business id
     * @param int $business_id
     * @return array
     */
    public function business_characterstics_info_business($business_id) {
        $this->db->select('business_characterstics_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('business_characterstics_business');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

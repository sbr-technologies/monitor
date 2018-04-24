<?php

class ExpertiseArea_Model extends CI_Model {

    /**
     * Added for inserting
     * new area of expertise
     * @param array $post_data
     * @return int
     */
    public function add_expertise_area($post_data) {
        $this->db->insert('areas_of_expertise_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating area
     * of expertise master information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_expertise_area($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('areas_of_expertise_master', $post_data);
    }

    /**
     * Added for listing all active
     * areas of expertise
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query  
     */
    public function fetchExpertiseAreaList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('EAM.id,EAM.name,EAM.vendor_count,EAM.status,EAM.updated_at');
        $this->db->from('areas_of_expertise_master EAM');
        $this->db->where('EAM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['area_of_expertise_name']) && $search_array['area_of_expertise_name'] !== "") {
                $this->db->like('EAM.name', $search_array['area_of_expertise_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('EAM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('EAM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('EAM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('EAM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('EAM.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('EAM.name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('EAM.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('EAM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching area 
     * of expertise
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $expertise_area_id   //Added for areas of expertise id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchExpertiseAreaInfo($flag, $expertise_area_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(EAM.id) AS expertise_area_count');
        } else {
            $this->db->select('EAM.*');
        }
        if ($expertise_area_id !== 0) {
            $this->db->where('EAM.id', $expertise_area_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['area_of_expertise_name']) && $search_array['area_of_expertise_name'] !== "") {
                $this->db->like('EAM.name', $search_array['area_of_expertise_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('EAM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('EAM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('EAM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('EAM.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('EAM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('EAM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('EAM.updated_at', 'desc');
        }

        $this->db->from('areas_of_expertise_master EAM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['expertise_area_count'];
        } else {
            return $result;
        }
    }

    /**
     * Get number of area of expertise for 
     * a expertise area name,Mainly added for checking the 
     * expertise area name exist or not
     * @param  string $name     //Name of the expertise area
     * @return int              //Number of results
     */
    public function get_expertise_area_name($name) {
        $this->db->select('count(id) AS total_result');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('areas_of_expertise_master');
        $result = $this->db->get()->result_array();
        return $result[0]['total_result'];
    }

    /**
     * Added for fetching area of expertise details
     * details by name
     * @param  string $name     //Name of the area of expertise
     * @return array            //Fetching the details of the area of expertise
     */
    public function get_expertise_area_details_name($name) {
        $this->db->select('*');
        $this->db->from('areas_of_expertise_master');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating areas of expertise
     * information with business id
     * @param array $area_of_expertise_business_info
     * @param int $business_id
     */
    public function update_areas_of_expertise_business($area_of_expertise_business_info = array(), $business_id = 0) {
        /* ---Updating existing areas of expertise information start--- */
        $this->db->select('areas_of_expertise_master_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('areas_of_expertise_business');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $areas_of_expertise_master_info = $this->db->get_where('areas_of_expertise_master', array('id' => $value["areas_of_expertise_master_id"]))->result_array();
                if ($areas_of_expertise_master_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $areas_of_expertise_master_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["areas_of_expertise_master_id"]);
                $this->db->update('areas_of_expertise_master', $update_data);
            }
        }
        /* ---Updating existing areas of expertise information ends--- */
        $this->db->delete('areas_of_expertise_business', array('business_id' => $business_id));
        $this->db->insert_batch('areas_of_expertise_business', $area_of_expertise_business_info);
        foreach ($area_of_expertise_business_info as $value) {
            $areas_of_expertise_master_info = $this->db->get_where('areas_of_expertise_master', array('id' => $value["areas_of_expertise_master_id"]))->result_array();
            $update_data["vendor_count"] = $areas_of_expertise_master_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["areas_of_expertise_master_id"]);
            $this->db->update('areas_of_expertise_master', $update_data);
        }
    }
    
    /**
     * Added for fetching all areas of 
     * expertise information based on
     * business id
     * @param int $business_id
     * @return array
     */
    public function areas_of_expertise_info_business($business_id) {
        $this->db->select('areas_of_expertise_master_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('areas_of_expertise_business');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

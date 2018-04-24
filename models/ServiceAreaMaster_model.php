<?php

class ServiceAreaMaster_model extends CI_Model {

    /**
     * Added for inserting
     * new service area
     * @param array $post_data
     * @return int
     */
    public function add_service_area($post_data) {
        $this->db->insert('service_area_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating service
     * area information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_service_area($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('service_area_master', $post_data);
    }

    /**
     * Added for getting the datils of a service area based on
     * the $result_type parameter
     * @param  string $result_type        //If number it will return the number record for a name
     * @param  string $service_area_name  //Name of the target service area
     * @return array/int                  //Array for the result or count the number of records
     */
    public function get_details_service_area_name($result_type = "", $service_area_name) {
        if ($result_type === "number") {
            $this->db->select('count(id) AS service_area_count');
        } else {
            $this->db->select('*');
        }
        $this->db->where('status !=', "3");
        $this->db->where('name', $service_area_name);
        $this->db->from('service_area_master');
        $result = $this->db->get()->result_array();
        if ($result_type === "number") {
            return $result[0]['service_area_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all active
     * service area
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchServiceAreaList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('SAM.id,SAM.name,SAM.vendor_count,SAM.status,SAM.updated_at');
        $this->db->from('service_area_master SAM');
        $this->db->where('SAM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['service_area_name']) && $search_array['service_area_name'] !== "") {
                $this->db->like('SAM.name', $search_array['service_area_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('SAM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('SAM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('SAM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('SAM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('SAM.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('SAM.name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('SAM.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('SAM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching service areas
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $service_area_id   //Added for service area id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchServiceAreaInfo($flag, $service_area_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(SAM.id) AS total_service_area');
        } else {
            $this->db->select('SAM.*');
        }
        if ($service_area_id !== 0) {
            $this->db->where('SAM.id', $service_area_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['service_area_name']) && $search_array['service_area_name'] !== "") {
                $this->db->like('SAM.name', $search_array['service_area_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('SAM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('SAM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('SAM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('SAM.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('SAM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('SAM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('SAM.updated_at', 'desc');
        }

        $this->db->from('service_area_master SAM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['total_service_area'];
        } else {
            return $result;
        }
    }

    /**
     * Get number of service areas for 
     * a service name,Mainly added for checking the 
     * service name exist or not
     * @param  string $name     //Name of the Service
     * @return int              //Number of results
     */
    public function get_service_area_name($name) {
        $this->db->select('count(id) AS service_count');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('service_area_master');
        $result = $this->db->get()->result_array();
        return $result[0]['service_count'];
    }

    /**
     * Added for fetching service area 
     * details by name
     * @param  string $name     //Added for service area name
     * @return array            //Fetching the details of the service area
     */
    public function get_service_area_details_name($name) {
        $this->db->select('*');
        $this->db->from('service_area_master');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating service area
     * information with business id
     * @param array $service_area_business_info
     * @param int $business_id
     */
    public function update_service_area_business($service_area_business_info = array(), $business_id = 0) {
        /* ---Updating existing service area information start--- */
        $this->db->select('service_area_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('service_area_business');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $service_area_master_info = $this->db->get_where('service_area_master', array('id' => $value["service_area_id"]))->result_array();
                if ($service_area_master_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $service_area_master_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["service_area_id"]);
                $this->db->update('service_area_master', $update_data);
            }
        }
        /* ---Updating existing service area information ends--- */
        $this->db->delete('service_area_business', array('business_id' => $business_id));
        $this->db->insert_batch('service_area_business', $service_area_business_info);
        foreach ($service_area_business_info as $value) {
            $service_area_master_info = $this->db->get_where('service_area_master', array('id' => $value["service_area_id"]))->result_array();
            $update_data["vendor_count"] = $service_area_master_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["service_area_id"]);
            $this->db->update('service_area_master', $update_data);
        }
    }

    /**
     * Added for fetching all availability 
     * based on business id
     * @param int $business_id
     * @return array
     */
    public function service_area_info_business($business_id) {
        $this->db->select('service_area_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('service_area_business');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

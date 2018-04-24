<?php

class AdditionalLanguageMaster_model extends CI_Model {

    /**
     * Added for inserting
     * new language master
     * @param array $post_data
     * @return int
     */
    public function add_language_master($post_data) {
        $this->db->insert('additional_language_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating language
     * master information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_language_master($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('additional_language_master', $post_data);
    }

    /**
     * Added for getting the details of a language master based on
     * the $result_type parameter
     * @param  string $result_type        //If number it will return the number record for a name
     * @param  string $language_master_name  //Name of the target language master
     * @return array/int                  //Array for the result or count the number of records
     */
    public function get_details_language_master_name($result_type = "", $language_master_name) {
        if ($result_type === "number") {
            $this->db->select('count(id) AS language_master_count');
        } else {
            $this->db->select('*');
        }
        $this->db->where('status !=', "3");
        $this->db->where('name', $language_master_name);
        $this->db->from('additional_language_master');
        $result = $this->db->get()->result_array();
        if ($result_type === "number") {
            return $result[0]['additional_language_master_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all active
     * language master
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchAdditionalLanguageList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('ALM.id,ALM.name,ALM.vendor_count,ALM.status,ALM.updated_at');
        $this->db->from('additional_language_master ALM');
        $this->db->where('ALM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['additional_language_name']) && $search_array['additional_language_name'] !== "") {
                $this->db->like('ALM.name', $search_array['additional_language_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('ALM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('ALM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('ALM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('ALM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('ALM.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('ALM.name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('ALM.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('ALM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching additional
     * language area
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $additional_language_id   //Added for additional language id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchAdditionalLanguageInfo($flag, $additional_language_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(ALM.id) AS additional_language_count');
        } else {
            $this->db->select('ALM.*');
        }
        if ($additional_language_id !== 0) {
            $this->db->where('ALM.id', $additional_language_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['additional_language_name']) && $search_array['additional_language_name'] !== "") {
                $this->db->like('ALM.name', $search_array['additional_language_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('ALM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('ALM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('ALM.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('ALM.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('ALM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('ALM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('ALM.updated_at', 'desc');
        }

        $this->db->from('additional_language_master ALM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['additional_language_count'];
        } else {
            return $result;
        }
    }

    /**
     * Get number of additional language for 
     * a language name,Mainly added for checking the 
     * language name exist or not
     * @param  string $name     //Name of the additional language
     * @return int              //Number of results
     */
    public function get_additional_language_name($name) {
        $this->db->select('count(id) AS total_result');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $this->db->from('additional_language_master');
        $result = $this->db->get()->result_array();
        return $result[0]['total_result'];
    }

    /**
     * Added for fetching additional language details
     * details by name
     * @param  string $name     //Name of the additional language
     * @return array            //Fetching the details of the language
     */
    public function get_additional_language_details_name($name) {
        $this->db->select('*');
        $this->db->from('additional_language_master');
        $this->db->where('status !=', "3");
        $this->db->where('name', $name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating additional language
     * information with business id
     * @param array $additional_language_business_info
     * @param int $business_id
     */
    public function update_additional_language_business($additional_language_business_info = array(), $business_id = 0) {
        /* ---Updating existing additional language information start--- */
        $this->db->select('additional_language_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('additional_language_business');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $additional_language_master_info = $this->db->get_where('additional_language_master', array('id' => $value["additional_language_id"]))->result_array();
                if ($additional_language_master_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $additional_language_master_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["additional_language_id"]);
                $this->db->update('additional_language_master', $update_data);
            }
        }
        /* ---Updating existing additional language information ends--- */
        $this->db->delete('additional_language_business', array('business_id' => $business_id));
        $this->db->insert_batch('additional_language_business', $additional_language_business_info);
        foreach ($additional_language_business_info as $value) {
            $additional_language_master_info = $this->db->get_where('additional_language_master', array('id' => $value["additional_language_id"]))->result_array();
            $update_data["vendor_count"] = $additional_language_master_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["additional_language_id"]);
            $this->db->update('additional_language_master', $update_data);
        }
    }

    /**
     * Added for fetching all additional
     * language information based on
     * business id
     * @param int $business_id
     * @return array
     */
    public function additional_language_info_business($business_id) {
        $this->db->select('additional_language_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('additional_language_business');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

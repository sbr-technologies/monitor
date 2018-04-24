<?php

class AdvertisementMaster_model extends CI_Model {

    /**
     * Added for inserting
     * new advertisement type
     * @param array $post_data
     * @return int
     */
    public function add_advertisement_type($post_data) {
        $this->db->insert('advertisement_type', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating advertisement 
     * type
     * @param  array $post_data
     * @param  int $id
     */
    public function update_advertisement_type($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('advertisement_type', $post_data);
    }

    /**
     * Added for fetching advertisement
     * type
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $advertisement_type_id   //Added for additional language id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchAdvertisementTypeInfo($flag, $advertisement_type_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(AT.id) AS advertisement_type_count');
        } else {
            $this->db->select('AT.*');
        }
        if ($advertisement_type_id !== 0) {
            $this->db->where('AT.id', $advertisement_type_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['advertisement_type_name']) && $search_array['advertisement_type_name'] !== "") {
                $this->db->like('AT.name', $search_array['advertisement_type_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AT.status', $search_array['status']);
            }
            if (isset($search_array['depend_on_category']) && $search_array['depend_on_category'] !== "") {
                $this->db->where('AT.depend_on_category', $search_array['depend_on_category']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AT.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_number_adds_from']) && $search_array['tot_number_adds_from'] !== "") {
                $this->db->where('AT.number_of_adds >=', $search_array['tot_number_adds_from']);
            }
            if (isset($search_array['tot_number_adds_to']) && $search_array['tot_number_adds_to'] !== "") {
                $this->db->where('AT.number_of_adds <=', $search_array['tot_number_adds_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AT.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('AT.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('AT.updated_at', 'desc');
        }

        $this->db->from('advertisement_type AT');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['advertisement_type_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all the advertisement
     * type
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchAdvertisementList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('AT.*');
        $this->db->from('advertisement_type AT');
        $this->db->where('AT.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['advertisement_type_name']) && $search_array['advertisement_type_name'] !== "") {
                $this->db->like('AT.name', $search_array['advertisement_type_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AT.status', $search_array['status']);
            }
            if (isset($search_array['depend_on_category']) && $search_array['depend_on_category'] !== "") {
                $this->db->where('AT.depend_on_category', $search_array['depend_on_category']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AT.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['tot_number_adds_from']) && $search_array['tot_number_adds_from'] !== "") {
                $this->db->where('AT.number_of_adds >=', $search_array['tot_number_adds_from']);
            }
            if (isset($search_array['tot_number_adds_to']) && $search_array['tot_number_adds_to'] !== "") {
                $this->db->where('AT.number_of_adds <=', $search_array['tot_number_adds_to']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AT.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        if ($orderByData === 'advertisement_type_name_order') {
            $this->db->order_by('AT.name', $orderByType);
        }
        if ($orderByData === 'total_number_of_adds') {
            $this->db->order_by('AT.number_of_adds', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('AT.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching advertisement type
     * details advertisement type name
     * @param string $advertisement_type_name
     * @return array
     */
    public function fetch_advertisement_details_by_name($advertisement_type_name) {
        $this->db->select('AT.*');
        $this->db->from('advertisement_type AT');
        $this->db->where('AT.name', $advertisement_type_name);
        $this->db->where('AT.status !=', '3');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

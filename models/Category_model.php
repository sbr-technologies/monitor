<?php

class Category_model extends CI_Model {

    /**
     * Added for fetching categories
     * @param  string $flag         //Added for fetching what king of result required
     * @param  int    $cat_id       //Added for category id
     * @param  array  $search_array //Searing criteria array to list the table
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchCategoryInfo($flag, $cat_id = 0, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(id) AS parent_category');
        } else {
            $this->db->select('*');
        }
        if ($flag !== 'number') {
            $this->db->order_by('updated_at', 'desc');
        }
        $this->db->where('status !=', "3");
        if ($cat_id !== 0) {
            $this->db->where('id', $cat_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['cat_name']) && $search_array['cat_name'] !== "") {
                $this->db->like('cat_name', $search_array['cat_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('status', $search_array['status']);
            }
            if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
                $this->db->where('is_featured', $search_array['is_featured']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('vendor_count <=', $search_array['tot_vendor_to']);
            }
        }
        $this->db->from('category_master');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['parent_category'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all main criteria
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchCategoryResultMain($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('CM_FIRST.id,CM_FIRST.cat_name,CM_FIRST.cat_image,CM_FIRST.cat_icon,CM_FIRST.vendor_count,CM_FIRST.is_featured,CM_FIRST.status,CM_FIRST.updated_at');
        $this->db->from('category_master CM_FIRST');
        $this->db->where('CM_FIRST.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['cat_name']) && $search_array['cat_name'] !== "") {
                $this->db->like('CM_FIRST.cat_name', $search_array['cat_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('CM_FIRST.status', $search_array['status']);
            }
            if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
                $this->db->where('CM_FIRST.is_featured', $search_array['is_featured']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('CM_FIRST.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('CM_FIRST.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('CM_FIRST.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('CM_FIRST.vendor_count <=', $search_array['tot_vendor_to']);
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('CM_FIRST.cat_name', $orderByType);
        }
        if ($orderByData === 'total_vendor') {
            $this->db->order_by('CM_FIRST.vendor_count', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('CM_FIRST.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Get number of categories for
     * each type
     * @param  string $cat_name //Name of the category
     * @return int              //Number of results
     */
    public function get_count_by_name($cat_name) {
        $this->db->select('count(id) AS category_number');
        $this->db->where('status !=', "3");
        $this->db->where('cat_name', $cat_name);
        $this->db->from('category_master');
        $result = $this->db->get()->result_array();
        return $result[0]['category_number'];
    }

    /**
     * Added for fetching category details by name
     * @param  string $cat_name //Added for category name
     * @return array            //Fetching the details of the category name
     */
    public function get_category_details_name($cat_name) {
        $this->db->select('id,cat_name,vendor_count,status,updated_at');
        $this->db->from('category_master');
        $this->db->where('status !=', "3");
        $this->db->where('cat_name', $cat_name);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for adding
     * new data for the category
     * @param array $post_data 
     * @return int
     */
    public function add_category($post_data) {
        $this->db->insert('category_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating category
     * data information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_category($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('category_master', $post_data);
    }

    /**
     * Added for fetching 
     * categories related to a business
     * @param  int $business_id //Target business id
     * @return array            //Get the result of the details of the categories
     */
    public function associated_business_categories($business_id){
        $this->db->select('CM.*');
        $this->db->where('CB.business_id', $business_id);
        $this->db->where('CM.status !=', "3");
        $this->db->where('BM.status !=', "3");
        $this->db->from('category_business CB');
        $this->db->join('category_master CM', 'CM.id = CB.category_id', 'left');
        $this->db->join('business_master BM', 'BM.id = CB.business_id', 'left');
        $result = $this->db->get()->result_array();
        return $result;
    }
}

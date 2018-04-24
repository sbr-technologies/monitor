<?php

class BusinessFeatureMaster_model extends CI_Model {

    /**
     * Added for adding
     * new data for the business feature
     * @param array $post_data 
     * @return int
     */
    public function add_feature($post_data) {
        $this->db->insert('business_feature_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Get number of features for
     * each type
     * @param  array $search_array    //Name of the feature & category_id
     * @return int                    //Number of results
     */
    public function get_count_by_feature_name($search_array = array()) {
        $this->db->select('count(id) AS total_feature');
        $this->db->where('status !=', "3");
        if (count($search_array) > 0) {
            if (isset($search_array['feature_name']) && $search_array['feature_name'] !== '') {
                $this->db->where('feature_name', $search_array['feature_name']);
            }
            if (isset($search_array['cat_id']) && $search_array['cat_id'] !== '') {
                $this->db->where('category_id', $search_array['cat_id']);
            }
        }
        $this->db->from('business_feature_master');
        $result = $this->db->get()->result_array();
        return $result[0]['total_feature'];
    }

    /**
     * Added for fetching features
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $feature_id   //Added for feature id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchFeatureInfo($flag, $feature_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(DISTINCT AFM.id) AS total_feature');
        } else {
            $this->db->distinct();
            $this->db->select('AFM.*');
        }
        if ($feature_id !== 0) {
            $this->db->where('AFM.id', $feature_id);
        }
        $this->db->from('business_feature_master AFM');
        $this->db->join('category_master CM_FIRST', 'AFM.category_id = CM_FIRST.id', 'left');
        $this->db->join('business_feature_attribute BFA', 'AFM.id = BFA.bfm_id AND BFA.status != 3 ', 'left');

        if (count($search_array) > 0) {
            if (isset($search_array['feature_name']) && $search_array['feature_name'] !== "") {
                $this->db->like('AFM.feature_name', $search_array['feature_name']);
            }
            if (isset($search_array['cat_name']) && $search_array['cat_name'] !== "") {
                $this->db->where('AFM.category_id', $search_array['cat_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AFM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AFM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AFM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('AFM.status !=', "3");
        $this->db->where('CM_FIRST.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('AFM.updated_at', 'desc');
        }
        /* === New code for attribute count starts==== */
        if ((isset($search_array['tot_attribute_to']) && $search_array['tot_attribute_to'] !== "") || (isset($search_array['tot_attribute_from']) && $search_array['tot_attribute_from'] !== "")) {
            $this->db->group_by('AFM.id');
            if (isset($search_array['tot_attribute_from']) && $search_array['tot_attribute_from'] !== "") {
                $this->db->having('count(BFA.id) >=', $search_array['tot_attribute_from']);
            }
            if (isset($search_array['tot_attribute_to']) && $search_array['tot_attribute_to'] !== "") {
                $this->db->having('count(BFA.id) <=', $search_array['tot_attribute_to']);
            }
        }

        if ($flag === 'number') {
            $subQuery = $this->db->get_compiled_select();
            $result = $this->db->query("select sum(total_feature) as sum_of_feature_attribute from ($subQuery) business_feature_master")->result();
        } else {
            $result = $this->db->get()->result_array();
        }

        if ($flag === 'number') {
            return $result[0]->sum_of_feature_attribute;
        } else {
            return $result;
        }
        /* === New code for attribute count ends==== */
    }

    /**
     * Added for fetching
     * feature attribute info
     * @param  string $flag            //Added for fetching what kind of result required
     * @param  int    $attribute_id    //Added for attribute id
     * @param  array  $search_array    //Added for search array
     * @return int/array               //Depending upon search criteria it will return array or int
     */
    public function fetchFeatureAttributeInfo($flag, $attribute_id, $search_array = array()) {
        if ($flag === "number") {
            $this->db->select('count(DISTINCT BFA.id) AS total_total_feature_attribute');
            $this->db->from('business_feature_attribute BFA');
            $this->db->join('business_feature_master BFM', 'BFM.id = BFA.bfm_id', 'left');
            $this->db->join('category_master CM_FIRST', 'BFM.category_id = CM_FIRST.id', 'left');
            $this->db->where('BFM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('BFA.status !=', '3');
            if (count($search_array) > 0) {
                if (count($search_array) > 0) {
                    if (isset($search_array['attribute_name']) && $search_array['attribute_name'] !== "") {
                        $this->db->like('BFA.name', $search_array['attribute_name']);
                    }
                    if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                        $this->db->where('CM_FIRST.id', $search_array['category_id']);
                    }
                    if (isset($search_array['feature_id']) && $search_array['feature_id'] !== "") {
                        $this->db->where('BFA.bfm_id', $search_array['feature_id']);
                    }
                    if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                        $this->db->where('BFA.vendor_count >=', $search_array['tot_vendor_from']);
                    }
                    if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                        $this->db->where('BFA.vendor_count <=', $search_array['tot_vendor_to']);
                    }
                    if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                        $this->db->where('BFA.updated_at >=', $search_array['updated_at_from']);
                    }
                    if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                        $target_date = $search_array['updated_at_to'];
                        $this->db->where('BFA.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
                    }
                    if (isset($search_array['status']) && $search_array['status'] !== "") {
                        $this->db->where('BFA.status', $search_array['status']);
                    }
                }
            }
            $result = $this->db->get()->result_array();
            $final_outcome = $result[0]['total_total_feature_attribute'];
        } else {
            $this->db->select('BFA.*,BFM.category_id as main_category_id');
            $this->db->from('business_feature_attribute BFA');
            $this->db->join('business_feature_master BFM', 'BFM.id = BFA.bfm_id', 'left');
            $this->db->join('category_master CM_FIRST', 'BFM.category_id = CM_FIRST.id', 'left');
            $this->db->where('BFM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('BFA.status !=', '3');
            $this->db->where('BFA.id', $attribute_id);
            $result = $this->db->get()->result_array();
            $final_outcome = $result[0];
        }
        return $final_outcome;
    }

    /**
     * Added for listing business feature
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which column to be ordered
     * @param  string $orderByType  //In which order the column is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit listing will go on
     * @return array                //Result of the query
     */
    public function fetchFeatureResultMain($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('CM_FIRST.id AS c.id,CM_FIRST.cat_name,AFM.id,AFM.category_id ,AFM.feature_name ,AFM.status ,AFM.updated_at,count(DISTINCT BFA.id) AS tot_attribute_count');
        $this->db->from('business_feature_master AFM');
        $this->db->join('category_master CM_FIRST', 'AFM.category_id = CM_FIRST.id', 'left');
        $this->db->join('business_feature_attribute BFA', 'AFM.id = BFA.bfm_id AND BFA.status != 3 ', 'left');
        $this->db->where('AFM.status !=', '3');
        $this->db->where('CM_FIRST.status !=', '3');
        if (count($search_array) > 0) {
            if (isset($search_array['cat_name']) && $search_array['cat_name'] !== "") {
                $this->db->where('AFM.category_id', $search_array['cat_name']);
            }
            if (isset($search_array['feature_name']) && $search_array['feature_name'] !== "") {
                $this->db->like('AFM.feature_name', $search_array['feature_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('AFM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('AFM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('AFM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->group_by('AFM.id');
        if ((isset($search_array['tot_attribute_from']) && $search_array['tot_attribute_from'] !== "") || (isset($search_array['tot_attribute_to']) && $search_array['tot_attribute_to'] !== "")) {
            if (isset($search_array['tot_attribute_from']) && $search_array['tot_attribute_from'] !== "") {
                $this->db->having('count(BFA.id) >=', $search_array['tot_attribute_from']);
            }
            if (isset($search_array['tot_attribute_to']) && $search_array['tot_attribute_to'] !== "") {
                $this->db->having('count(BFA.id) <=', $search_array['tot_attribute_to']);
            }
        }

        if ($orderByData === 'feature_name') {
            $this->db->order_by('AFM.feature_name', $orderByType);
        }
        if ($orderByData === 'tot_attribute_count') {
            $this->db->order_by('tot_attribute_count', $orderByType);
        }
        if ($orderByData === 'cat_name') {
            $this->db->order_by('CM_FIRST.cat_name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('AFM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for updating additional
     * feature 
     * @param  array $post_data
     * @param  int $id
     */
    public function update_feature($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('business_feature_master', $post_data);
    }

    /**
     * Added for fetching details
     * of an business feature by name
     * @param  string $name
     * @param  int    $category_id
     * @return array
     */
    public function get_feature_details_name($name, $category_id = 0) {
        $this->db->select('*');
        $this->db->from('business_feature_master');
        $this->db->where('feature_name', $name);
        $this->db->where('status !=', '3');
        if ($category_id > 0) {
            $this->db->where('category_id', $category_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for fetching all the related
     * features based on category id
     * @param  int $category_id 
     * @return array              
     */
    public function get_all_related_features($category_id) {
        $this->db->select("*");
        $this->db->from("business_feature_master");
        $this->db->where('category_id', $category_id);
        $this->db->where('status !=', '3');
        $query = $this->db->get();
        return $query->result_array();
    }

    /**
     * Added for listing business feature attributes
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which column to be ordered
     * @param  string $orderByType  //In which order the column is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchFeatureAttributes($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('CM_FIRST.cat_name as category_name,BFM.feature_name as feature_name,BFA.*');
        $this->db->from('business_feature_attribute BFA');
        $this->db->join('business_feature_master BFM', 'BFM.id = BFA.bfm_id', 'left');
        $this->db->join('category_master CM_FIRST', 'BFM.category_id = CM_FIRST.id', 'left');
        $this->db->where('BFM.status !=', '3');
        $this->db->where('CM_FIRST.status !=', '3');
        $this->db->where('BFA.status !=', '3');
        if (count($search_array) > 0) {
            if (isset($search_array['attribute_name']) && $search_array['attribute_name'] !== "") {
                $this->db->like('BFA.name', $search_array['attribute_name']);
            }
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('CM_FIRST.id', $search_array['category_id']);
            }
            if (isset($search_array['feature_id']) && $search_array['feature_id'] !== "") {
                $this->db->where('BFA.bfm_id', $search_array['feature_id']);
            }
            if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                $this->db->where('BFA.vendor_count >=', $search_array['tot_vendor_from']);
            }
            if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                $this->db->where('BFA.vendor_count <=', $search_array['tot_vendor_to']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BFA.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BFA.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BFA.status', $search_array['status']);
            }
        }
        if ($orderByData === 'attribute_name') {
            $this->db->order_by('BFA.name', $orderByType);
        }
        if ($orderByData === "vendor_count") {
            $this->db->order_by('BFA.vendor_count', $orderByType);
        }
        if ($orderByData === 'feature_name') {
            $this->db->order_by('BFM.feature_name', $orderByType);
        }
        if ($orderByData === 'cat_name') {
            $this->db->order_by('CM_FIRST.cat_name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('BFA.updated_at', $orderByType);
        }
        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for adding
     * new data for the business feature
     * attribute
     * @param array $post_data 
     * @return int
     */
    public function add_feature_attribute($post_data) {
        $this->db->insert('business_feature_attribute', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for fetching business attribute
     * details
     * @param  string $name
     * @param  int    $bfm_id
     * @return array
     */
    public function get_feature_attribute_detail($name, $bfm_id = 0) {
        $this->db->select('*');
        $this->db->from('business_feature_attribute');
        $this->db->where('name', $name);
        $this->db->where('status !=', '3');
        if ($bfm_id > 0) {
            $this->db->where('bfm_id', $bfm_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for updating feature
     * attribute for business
     * @param  array $post_data
     * @param  int $id
     */
    public function update_feature_attribute($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('business_feature_attribute', $post_data);
    }

    /**
     * Added for fetching details based 
     * on business feature attribute
     * @param  string $flag                   //Added for fetching what kind of result required
     * @param  int    $feature_attribute_id   //Added for feature attribute id
     * @param  array  $search_array           //Added for search array
     * @param  string $orderByData  //Which column to be ordered
     * @param  string $orderByType  //In which order the column is to be ordered
     * @return int/array                      //Depending upon search criteria it will return array or int
     */
    public function fetchBusinessFeatureAttribute($flag, $feature_attribute_id, $search_array = array(), $orderByData = "", $orderByType = "") {
        if ($flag === "number") {
            $this->db->select('count(DISTINCT BFA.id) AS total_feature_attribute');
            $this->db->from('business_feature_attribute BFA');
            $this->db->join('business_feature_master BFM', 'BFM.id = BFA.bfm_id', 'left');
            $this->db->join('category_master CM_FIRST', 'BFM.category_id = CM_FIRST.id', 'left');
            $this->db->where('BFM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('BFA.status !=', '3');
            if (count($search_array) > 0) {
                if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                    $this->db->where('BFA.vendor_count >=', $search_array['tot_vendor_from']);
                }
                if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                    $this->db->where('BFA.vendor_count <=', $search_array['tot_vendor_to']);
                }
                if (isset($search_array['name']) && $search_array['name'] !== "") {
                    $this->db->like('BFA.name', $search_array['name']);
                }
                if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                    $this->db->where('CM_FIRST.id', $search_array['category_id']);
                }
                if (isset($search_array['bfm_id']) && $search_array['bfm_id'] !== "") {
                    $this->db->where('BFA.bfm_id', $search_array['bfm_id']);
                }
                if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                    $this->db->where('BFA.updated_at >=', $search_array['updated_at_from']);
                }
                if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                    $target_date = $search_array['updated_at_to'];
                    $this->db->where('BFA.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
                }
                if (isset($search_array['status']) && $search_array['status'] !== "") {
                    $this->db->where('BFA.status', $search_array['status']);
                }
            }
            $result = $this->db->get()->result_array();
            $final_outcome = $result[0]['total_feature_attribute'];
        } else {
            $this->db->select('BFA.*,BFM.category_id as main_category_id,BFM.feature_name');
            $this->db->from('business_feature_attribute BFA');
            $this->db->join('business_feature_master BFM', 'BFM.id = BFA.bfm_id', 'left');
            $this->db->join('category_master CM_FIRST', 'BFM.category_id = CM_FIRST.id', 'left');
            $this->db->where('BFM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('BFA.status !=', '3');
            if (count($search_array) > 0) {
                if (isset($search_array['tot_vendor_from']) && $search_array['tot_vendor_from'] !== "") {
                    $this->db->where('BFA.vendor_count >=', $search_array['tot_vendor_from']);
                }
                if (isset($search_array['tot_vendor_to']) && $search_array['tot_vendor_to'] !== "") {
                    $this->db->where('BFA.vendor_count <=', $search_array['tot_vendor_to']);
                }
                if (isset($search_array['name']) && $search_array['name'] !== "") {
                    $this->db->like('BFA.name', $search_array['name']);
                }
                if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                    $this->db->where('CM_FIRST.id', $search_array['category_id']);
                }
                if (isset($search_array['bfm_id']) && $search_array['bfm_id'] !== "") {
                    $this->db->where('BFA.bfm_id', $search_array['bfm_id']);
                }
                if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                    $this->db->where('BFA.updated_at >=', $search_array['updated_at_from']);
                }
                if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                    $target_date = $search_array['updated_at_to'];
                    $this->db->where('BFA.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
                }
                if (isset($search_array['status']) && $search_array['status'] !== "") {
                    $this->db->where('BFA.status', $search_array['status']);
                }
            }
            if ($feature_attribute_id > 0) {
                $this->db->where('BFA.id', $feature_attribute_id);
            }
            if ($orderByData !== "" && $orderByType !== "") {
                if ($orderByData === 'business_feature_master_id') {
                    $this->db->order_by('BFA.bfm_id', $orderByType);
                    $this->db->order_by('BFA.name', $orderByType);
                }
            }
            $result = $this->db->get()->result_array();
            $final_outcome = $result;
        }
        return $final_outcome;
    }

    /**
     * Added for fetching details 
     * of the feature attribute
     * based on condition for 
     * editing
     * @param  string $name
     * @param  int    $feature_id
     * @param  int    $id
     * @return array
     */
    public function get_feature_attribute_edit_details($name, $feature_id = 0, $id = 0) {
        $this->db->select('*');
        $this->db->from('business_feature_attribute');
        $this->db->where('name', $name);
        $this->db->where('status !=', '3');
        if ($feature_id > 0) {
            $this->db->where('feature_id', $feature_id);
        }
        if ($id > 0) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for updating business feature details
     * @param array $dynamic_feature_info
     * @param int $business_id
     */
    public function update_dynamic_feature($dynamic_feature_info = array(), $business_id = 0) {
        /* ---Updating existing dynamic feature start--- */
        $this->db->select('feature_attribute_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('business_feature_details');
        $result = $this->db->get()->result_array();
        if (is_array($result)) {
            foreach ($result as $value) {
                $business_feature_attribute_info = $this->db->get_where('business_feature_attribute', array('id' => $value["feature_attribute_id"]))->result_array();
                if ($business_feature_attribute_info[0]["vendor_count"] > 0) {
                    $update_data["vendor_count"] = $business_feature_attribute_info[0]["vendor_count"] - 1;
                } else {
                    $update_data["vendor_count"] = 0;
                }
                $this->db->where('id', $value["feature_attribute_id"]);
                $this->db->update('business_feature_attribute', $update_data);
            }
        }
        /* ---Updating existing dynamic feature ends--- */
        $this->db->delete('business_feature_details', array('business_id' => $business_id));
        $this->db->insert_batch('business_feature_details', $dynamic_feature_info);
        foreach ($dynamic_feature_info as $value) {
            $business_feature_attribute_info = $this->db->get_where('business_feature_attribute', array('id' => $value["feature_attribute_id"]))->result_array();
            $update_data["vendor_count"] = $business_feature_attribute_info[0]["vendor_count"] + 1;
            $this->db->where('id', $value["feature_attribute_id"]);
            $this->db->update('business_feature_attribute', $update_data);
        }
    }

    /**
     * Added for fetching all dynamic
     * feature information based on
     * business id
     * @param int $business_id
     * @return array
     */
    public function dynamic_feature_info_business($business_id) {
        $this->db->select('feature_attribute_id');
        $this->db->where('business_id', $business_id);
        $this->db->from('business_feature_details');
        $result = $this->db->get()->result_array();
        return $result;
    }

}

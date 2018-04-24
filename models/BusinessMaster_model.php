<?php

class BusinessMaster_model extends CI_Model {

    /**
     * Added for adding new business
     * @param array $post_data //Data to be inserted
     * @param int $category_id //Category to be inserted
     * @return int
     */
    public function add_business($post_data, $category_id) {
        /* ---Business Entry--- */
        $this->db->insert('business_master', $post_data);
        $insert_id = $this->db->insert_id();
        /* ---category_business table Entry--- */
        $category_entry['category_id'] = $category_id;
        $category_entry['business_id'] = $insert_id;
        $this->db->insert('category_business', $category_entry);
        /* ---Increase vendor count for the category--- */
        $category_master_info = $this->db->get_where('category_master', array('id' => $category_id))->result_array();
        if ($category_master_info[0]["vendor_count"] == 0) {
            $category_master_data["vendor_count"] = 1;
        } else {
            $category_master_data["vendor_count"] = $category_master_info[0]["vendor_count"] + 1;
        }
        $this->db->update('category_master', $category_master_data, array('id' => $category_id));
        return $insert_id;
    }

    /**
     * Added for updating business
     * @param array $post_data //Data to be updated
     * @param int $category_id //Category to be updated
     * @param int $id //Business id to be updated
     * @param int $id
     */
    public function update_business($post_data, $category_id = "", $id) {
        $get_business_info = $this->get_business_info($attribute = array(), $attribute_type = array(), $id); //Get existing information for the business
        $old_Category_id = $get_business_info['category_id'];
        /* ---Business Entry Update--- */
        $this->db->update('business_master', $post_data, array('id' => $id));
        /* ---category_business table Entry--- */
        if ($category_id !== "") {
            if ($old_Category_id != $category_id) {
                /* ---Increase the business of the new category--- */
                $category_master_info = $this->db->get_where('category_master', array('id' => $category_id))->result_array();
                if ($category_master_info[0]["vendor_count"] == 0) {
                    $category_master_data["vendor_count"] = 1;
                } else {
                    $category_master_data["vendor_count"] = $category_master_info[0]["vendor_count"] + 1;
                }
                $this->db->delete('category_business', array('business_id' => $id));
                $category_entry['category_id'] = $category_id;
                $category_entry['business_id'] = $id;
                $this->db->insert('category_business', $category_entry);
                $this->db->update('category_master', $category_master_data, array('id' => $category_id));
                /* ---Decrease the business of the old category--- */
                $category_master_info = $this->db->get_where('category_master', array('id' => $old_Category_id))->result_array();
                $category_master_data["vendor_count"] = $category_master_info[0]["vendor_count"] - 1;
                $this->db->update('category_master', $category_master_data, array('id' => $old_Category_id));
            }
        }
    }

    /**
     * Added for deleting business
     * @param int $category_id
     * @param int $id
     * @return int
     */
    public function delete_business($id) {
        $id = intval($id);
        if (is_numeric($id)) {
            /* ---Added for updating category master starts--- */
            $category_business_info = $this->db->get_where('category_business', array('business_id' => $id))->result_array();
            if(count($category_business_info)>0){
                foreach ($category_business_info as $value) {
                    $category_master_info = $this->db->get_where('category_master', array('id' => $value["category_id"]))->result_array();
                    $category_master_data["vendor_count"] = $category_master_info[0]["vendor_count"] - 1;
                    $this->db->update('category_master', $category_master_data, array('id' => $value["category_id"]));
                }
            }
            /* ---Added for updating category master ends--- */

            /*---Added for deleting additional phone number statrs---*/
            $this->db->delete('additional_phone_number', array('business_id' => $business_id));
            /*---Added for deleting additional phone number ends---*/

            /*---Added for deleting areas of expertise starts--*/
            $areas_of_expertise_business_info = $this->db->get_where('areas_of_expertise_business', array('business_id' => $id))->result_array();
            if(count($areas_of_expertise_business_info)>0){
                foreach ($areas_of_expertise_business_info as $value) {
                    $areas_of_expertise_master_info = $this->db->get_where('areas_of_expertise_master', array('id' => $value["areas_of_expertise_master_id"]))->result_array();
                    $areas_of_expertise_master_data["vendor_count"] = $areas_of_expertise_master_info[0]["vendor_count"] - 1;
                    $this->db->update('areas_of_expertise_master', $category_master_data, array('id' => $value["areas_of_expertise_master_id"]));
                }
            }
            /*---Added for deleting areas of expertise ends--*/

            /*---Added for deleting additional language for the business starts--*/
            $additional_language_business_info = $this->db->get_where('additional_language_business', array('business_id' => $id))->result_array();
            if(count($additional_language_business_info)>0){
                foreach ($additional_language_business_info as $value) {
                    $additional_language_master_info = $this->db->get_where('additional_language_master', array('id' => $value["additional_language_id"]))->result_array();
                    $additional_language_master_data["vendor_count"] = $additional_language_master_info[0]["vendor_count"] - 1;
                    $this->db->update('additional_language_master', $category_master_data, array('id' => $value["additional_language_id"]));
                }
            }
            /*---Added for deleting additional language for the business ends--*/

            /*---Added for deleting business expertise for the business starts--*/
            $business_characteristics_business_info = $this->db->get_where('business_characteristics_business', array('business_id' => $id))->result_array();
            if(count($business_characteristics_business_info)>0){
                foreach ($business_characteristics_business_info as $value) {
                    $business_characteristics_master_info = $this->db->get_where('business_characteristics_master', array('id' => $value["business_characteristics_master_id"]))->result_array();
                    $business_characteristics_master_data["vendor_count"] = $business_characteristics_master_info[0]["vendor_count"] - 1;
                    $this->db->update('business_characteristics_master', $category_master_data, array('id' => $value["business_characteristics_master_id"]));
                }
            }
            /*---Added for deleting business expertise for the business ends--*/
            
            $update_data["status"] = "3";
            $this->db->update('business_master', $update_data, array('id' => $id));
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Added for getting business 
     * information
     * @param array $attribute
     * @param array $attribute_type
     * @param int $business_id
     * @return array
     */
    public function get_business_info($attribute = array(), $attribute_type = array(), $business_id = 0) {
        $this->db->select('BM.*,CB.category_id,CM.cat_name');
        $this->db->from('business_master BM');
        $this->db->join('category_business CB', 'CB.business_id=BM.id', 'left');
        $this->db->join('category_master CM', 'CM.id=CB.category_id', 'left');
        $this->db->where('BM.status !=', '3');
        $this->db->where('CM.status !=', '3');
        if (isset($attribute_type['category_id'])) {
            if ($attribute_type['category_id']) {
                $this->db->where('CB.category_id', $attribute['category_id']);
            }
        }
        if (isset($attribute_type['vendor_name'])) {
            if ($attribute_type['vendor_name']) {
                $this->db->where('BM.vendor_name', $attribute['vendor_name']);
            }
        }
        if (isset($attribute_type['vendor_number'])) {
            if ($attribute_type['vendor_number']) {
                $this->db->where('BM.vendor_number', $attribute['vendor_number']);
            }
        }
        if (isset($attribute_type['vendor_email'])) {
            if ($attribute_type['vendor_email']) {
                $this->db->where('BM.vendor_email', $attribute['vendor_email']);
            }
        }
        if (isset($attribute_type['user_id'])) {
            if ($attribute_type['user_id']) {
                $this->db->where('BM.user_id', $attribute['user_id']);
            }
        }
        if (isset($attribute_type['vendor_slug'])) {
            if ($attribute_type['vendor_slug']) {
                $this->db->where('BM.vendor_slug', $attribute['vendor_slug']);
            }
        }
        if (isset($attribute_type['vendor_site_url'])) {
            if ($attribute_type['vendor_site_url']) {
                $this->db->where('BM.vendor_site_url', $attribute['vendor_site_url']);
            }
        }
        if ($business_id > 0) {
            $this->db->where('BM.id', $business_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for inserting business images
     * @param array $post_data
     * @return int
     */
    public function add_business_image($post_data) {
        $this->db->insert('business_image', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating
     * business image
     * @param array $post_data
     * @param int $id
     */
    public function update_business_image($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('business_image', $post_data);
    }

    /**
     * Added for deleting 
     * business image
     * @param int $id
     */
    public function delete_business_image($id) {
        $this->db->delete('business_image', array('id' => $id));
    }

    /**
     * Added for 
     * getting all the banner 
     * images for business
     * @param int $id
     * @param int $business_id
     * @return array
     */
    public function get_all_banner_business($id = 0, $business_id = 0) {
        $search_array = array();
        if ($id > 0) {
            $search_array['id'] = $id;
        }
        if ($business_id > 0) {
            $search_array['business_id'] = $business_id;
        }
        $query = $this->db->get_where('business_image', $search_array)->result_array();
        return $query;
    }

    /**
     * Added for fetching related 
     * business
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchBusinessInfo($search_array = array()) {
        $this->db->select('count(DISTINCT BM.id) AS total_business');
        $this->db->from('business_master BM');
        $this->db->join('category_business CB', 'CB.business_id=BM.id', 'left');
        $this->db->join('category_master CM', 'CM.id=CB.category_id', 'left');
        $this->db->where('BM.status !=', '3');
        $this->db->where('CM.status !=', '3');
        if (isset($search_array['business_name_url'])) {
            if ($search_array['business_name_url'] !== "") {
                $this->db->like('BM.vendor_name', $search_array['business_name_url']);
                $this->db->or_like('BM.vendor_site_url', $search_array['business_name_url']);
            }
        }
        if (isset($search_array['user_id'])) {
            if ($search_array['user_id'] !== "") {
                $this->db->where('BM.user_id', $search_array['user_id']);
            }
        }
        if (isset($search_array['category_id'])) {
            if ($search_array['category_id'] !== "") {
                $this->db->where('CB.category_id', $search_array['category_id']);
            }
        }
        if (isset($search_array['vendor_number'])) {
            if ($search_array['vendor_number'] !== "") {
                $this->db->where('BM.vendor_number', $search_array['vendor_number']);
            }
        }
        if (isset($search_array['vendor_email'])) {
            if ($search_array['vendor_email'] !== "") {
                $this->db->like('BM.vendor_email', $search_array['vendor_email'], 'both');
            }
        }
        if (isset($search_array['from_tot_rater']) && $search_array['from_tot_rater'] !== "") {
            $this->db->where('BM.total_rater >=', $search_array['from_tot_rater']);
        }
        if (isset($search_array['to_tot_rater']) && $search_array['to_tot_rater'] !== "") {
            $this->db->where('BM.total_rater <=', $search_array['to_tot_rater']);
        }
        if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
            $this->db->where('BM.updated_at >=', $search_array['updated_at_from']);
        }
        if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
            $target_date = $search_array['updated_at_to'];
            $this->db->where('BM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
        }
        if (isset($search_array['from_avg_rate']) && $search_array['from_avg_rate'] !== "") {
            $this->db->where('BM.avg_rate >=', $search_array['from_avg_rate']);
        }
        if (isset($search_array['to_avg_rate']) && $search_array['to_avg_rate'] !== "") {
            $this->db->where('BM.avg_rate <=', $search_array['to_avg_rate']);
        }
        if (isset($search_array['from_tot_viewer']) && $search_array['from_tot_viewer'] !== "") {
            $this->db->where('BM.tot_viewer >=', $search_array['from_tot_viewer']);
        }
        if (isset($search_array['to_tot_viewer']) && $search_array['to_tot_viewer'] !== "") {
            $this->db->where('BM.tot_viewer <=', $search_array['to_tot_viewer']);
        }
        if (isset($search_array['vendor_street'])) {
            if ($search_array['vendor_street'] !== "") {
                $this->db->like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_city', $search_array['vendor_city'], 'both');
                $this->db->or_like('BM.vendor_location', $search_array['vendor_location'], 'both');
            }
        }
        if (isset($search_array['vendor_city'])) {
            if ($search_array['vendor_city'] !== "") {
                $this->db->like('BM.vendor_city', $search_array['vendor_city'], 'both');
                $this->db->or_like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_location', $search_array['vendor_location'], 'both');
            }
        }
        if (isset($search_array['vendor_location'])) {
            if ($search_array['vendor_location'] !== "") {
                $this->db->like('BM.vendor_location', $search_array['vendor_location'], 'both');
                $this->db->or_like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_city', $search_array['vendor_city'], 'both');
            }
        }
        if (isset($search_array['status']) && $search_array['status'] !== "") {
            $this->db->where('BM.status', $search_array['status']);
        }
        if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
            $this->db->where('BM.is_featured', $search_array['is_featured']);
        }
        $result = $this->db->get()->result_array();
        return $result[0]['total_business'];
    }

    /**
     * Added for listing all business
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchBusinessResultMain($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('BM.*,CONCAT(UM.first_name," ",UM.last_name) AS owner_name');
        $this->db->from('business_master BM');
        $this->db->join('category_business CB', 'CB.business_id=BM.id', 'left');
        $this->db->join('category_master CM', 'CM.id=CB.category_id', 'left');
        $this->db->join('users_master UM', 'UM.id=BM.user_id', 'left');
        $this->db->where('BM.status !=', '3');
        $this->db->where('CM.status !=', '3');
        $this->db->where('UM.status !=', '3');
        $this->db->group_by('BM.id');
        if (isset($search_array['business_name_url'])) {
            if ($search_array['business_name_url'] !== "") {
                $this->db->like('BM.vendor_name', $search_array['business_name_url']);
                $this->db->or_like('BM.vendor_site_url', $search_array['business_name_url']);
            }
        }
        if (isset($search_array['user_id'])) {
            if ($search_array['user_id'] !== "") {
                $this->db->where('BM.user_id', $search_array['user_id']);
            }
        }
        if (isset($search_array['category_id'])) {
            if ($search_array['category_id'] !== "") {
                $this->db->where('CB.category_id', $search_array['category_id']);
            }
        }
        if (isset($search_array['vendor_number'])) {
            if ($search_array['vendor_number'] !== "") {
                $this->db->where('BM.vendor_number', $search_array['vendor_number']);
            }
        }
        if (isset($search_array['vendor_email'])) {
            if ($search_array['vendor_email'] !== "") {
                $this->db->like('BM.vendor_email', $search_array['vendor_email'], 'both');
            }
        }
        if (isset($search_array['from_tot_rater']) && $search_array['from_tot_rater'] !== "") {
            $this->db->where('BM.total_rater >=', $search_array['from_tot_rater']);
        }
        if (isset($search_array['to_tot_rater']) && $search_array['to_tot_rater'] !== "") {
            $this->db->where('BM.total_rater <=', $search_array['to_tot_rater']);
        }
        if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
            $this->db->where('BM.updated_at >=', $search_array['updated_at_from']);
        }
        if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
            $target_date = $search_array['updated_at_to'];
            $this->db->where('BM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
        }
        if (isset($search_array['from_avg_rate']) && $search_array['from_avg_rate'] !== "") {
            $this->db->where('BM.avg_rate >=', $search_array['from_avg_rate']);
        }
        if (isset($search_array['to_avg_rate']) && $search_array['to_avg_rate'] !== "") {
            $this->db->where('BM.avg_rate <=', $search_array['to_avg_rate']);
        }
        if (isset($search_array['from_tot_viewer']) && $search_array['from_tot_viewer'] !== "") {
            $this->db->where('BM.tot_viewer >=', $search_array['from_tot_viewer']);
        }
        if (isset($search_array['to_tot_viewer']) && $search_array['to_tot_viewer'] !== "") {
            $this->db->where('BM.tot_viewer <=', $search_array['to_tot_viewer']);
        }
        if (isset($search_array['vendor_street'])) {
            if ($search_array['vendor_street'] !== "") {
                $this->db->like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_city', $search_array['vendor_city'], 'both');
                $this->db->or_like('BM.vendor_location', $search_array['vendor_location'], 'both');
            }
        }
        if (isset($search_array['vendor_city'])) {
            if ($search_array['vendor_city'] !== "") {
                $this->db->like('BM.vendor_city', $search_array['vendor_city'], 'both');
                $this->db->or_like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_location', $search_array['vendor_location'], 'both');
            }
        }
        if (isset($search_array['vendor_location'])) {
            if ($search_array['vendor_location'] !== "") {
                $this->db->like('BM.vendor_location', $search_array['vendor_location'], 'both');
                $this->db->or_like('BM.vendor_street', $search_array['vendor_street'], 'both');
                $this->db->or_like('BM.vendor_city', $search_array['vendor_city'], 'both');
            }
        }
        if (isset($search_array['status']) && $search_array['status'] !== "") {
            $this->db->where('BM.status', $search_array['status']);
        }
        if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
            $this->db->where('BM.is_featured', $search_array['is_featured']);
        }
        if ($orderByData === 'business_name') {
            $this->db->order_by('BM.vendor_name', $orderByType);
        }
        if ($orderByData === 'vendor_name') {
            $this->db->order_by('UM.first_name', $orderByType);
        }
        if ($orderByData === 'category_id') {
            $this->db->order_by('CM.cat_name', $orderByType);
        }
        if ($orderByData === 'phone_number') {
            $this->db->order_by('BM.vendor_number', $orderByType);
        }
        if ($orderByData === 'email') {
            $this->db->order_by('BM.vendor_email', $orderByType);
        }
        if ($orderByData === 'total_rater') {
            $this->db->order_by('BM.total_rater', $orderByType);
        }
        if ($orderByData === 'avg_rate') {
            $this->db->order_by('BM.avg_rate', $orderByType);
        }
        if ($orderByData === 'total_viewer') {
            $this->db->order_by('BM.tot_viewer', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('BM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

}

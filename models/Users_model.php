<?php

class Users_model extends CI_Model {

    /**
     * Added for updating users
     * data information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_user($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('users_master', $post_data);
    }

    /**
     * Added for fetching customers
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $customer_id  //Added for user id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchCustomerInfo($flag, $customer_id = 0, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(id) AS total_customers');
        } else {
            $this->db->select('*');
        }

        $this->db->where('user_type', "3");

        if (count($search_array) > 0) {
            if (isset($search_array['user_name']) && $search_array['user_name'] !== "") {
                $this->db->like('first_name', $search_array['user_name']);
                $this->db->like('last_name', $search_array['user_name']);
            }
            if (isset($search_array['email']) && $search_array['email'] !== "") {
                $this->db->where('email', $search_array['email']);
            }
            if (isset($search_array['phone']) && $search_array['phone'] !== "") {
                $this->db->where('phone', $search_array['phone']);
            }
            if (isset($search_array['city']) && $search_array['city'] !== "") {
                $this->db->where('city', $search_array['city']);
            }
            if (isset($search_array['signup_type']) && $search_array['signup_type'] !== "") {
                $this->db->where('signup_type', $search_array['signup_type']);
            }
            if (isset($search_array['newsletter_subscribe']) && $search_array['newsletter_subscribe'] !== "") {
                $this->db->where('newsletter_subscribe', $search_array['newsletter_subscribe']);
            }
            if (isset($search_array['last_log_in_from']) && $search_array['last_log_in_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['last_log_in_from']);
            }
            if (isset($search_array['last_log_in_to']) && $search_array['last_log_in_to'] !== "") {
                $target_date = $search_array['last_log_in_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('status', $search_array['status']);
            }
        }

        $this->db->where('status !=', "3");
        if ($customer_id !== 0) {
            $this->db->where('id', $customer_id);
        }

        if ($flag !== 'number') {
            $this->db->order_by('updated_at', 'desc');
        }

        $this->db->from('users_master');
        $result = $this->db->get()->result_array();

        if ($flag === 'number') {
            return $result[0]['total_customers'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing customers
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which column to be ordered
     * @param  string $orderByType  //In which order the column is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit listing will go on
     * @return array                //Result of the query
     */
    public function fetchCustomerResultMain($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('id,profile_picture,CONCAT(first_name, " ",last_name) AS user_name,email,phone,city,signup_type,newsletter_subscribe,last_login,updated_at,status');
        $this->db->from('users_master');
        $this->db->where('status !=', '3');
        $this->db->where('user_type', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['user_name']) && $search_array['user_name'] !== "") {
                $this->db->like('first_name', $search_array['user_name']);
                $this->db->like('last_name', $search_array['user_name']);
            }
            if (isset($search_array['email']) && $search_array['email'] !== "") {
                $this->db->where('email', $search_array['email']);
            }
            if (isset($search_array['phone']) && $search_array['phone'] !== "") {
                $this->db->where('phone', $search_array['phone']);
            }
            if (isset($search_array['city']) && $search_array['city'] !== "") {
                $this->db->where('city', $search_array['city']);
            }
            if (isset($search_array['signup_type']) && $search_array['signup_type'] !== "") {
                $this->db->where('signup_type', $search_array['signup_type']);
            }
            if (isset($search_array['newsletter_subscribe']) && $search_array['newsletter_subscribe'] !== "") {
                $this->db->where('newsletter_subscribe', $search_array['newsletter_subscribe']);
            }
            if (isset($search_array['last_log_in_from']) && $search_array['last_log_in_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['last_log_in_from']);
            }
            if (isset($search_array['last_log_in_to']) && $search_array['last_log_in_to'] !== "") {
                $target_date = $search_array['last_log_in_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('status', $search_array['status']);
            }
        }

        if ($orderByData === 'user_name') {
            $this->db->order_by('user_name', $orderByType);
        }
        if ($orderByData === 'email') {
            $this->db->order_by('email', $orderByType);
        }
        if ($orderByData === 'city') {
            $this->db->order_by('city', $orderByType);
        }
        if ($orderByData === 'phone') {
            $this->db->order_by('phone', $orderByType);
        }
        if ($orderByData === 'last_login') {
            $this->db->order_by('last_login', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching vendors
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $vendor_id    //Added for vendor id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchVendorInfo($flag, $vendor_id = 0, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(id) AS total_vendors');
        } else {
            $this->db->select('*');
        }
        if (count($search_array) > 0) {
            if(isset($search_array["user_type"])){
                if (is_array($search_array["user_type"])) {
                    $this->db->where_in('user_type', $search_array["user_type"]);
                } else {
                    $this->db->where('user_type', $search_array["user_type"]);
                }
            }
            if (isset($search_array['user_name']) && $search_array['user_name'] !== "") {
                $this->db->like('first_name', $search_array['user_name']);
                $this->db->like('last_name', $search_array['user_name']);
            }
            if (isset($search_array['email']) && $search_array['email'] !== "") {
                $this->db->where('email', $search_array['email']);
            }
            if (isset($search_array['phone']) && $search_array['phone'] !== "") {
                $this->db->where('phone', $search_array['phone']);
            }
            if (isset($search_array['city']) && $search_array['city'] !== "") {
                $this->db->where('city', $search_array['city']);
            }
            if (isset($search_array['signup_type']) && $search_array['signup_type'] !== "") {
                $this->db->where('signup_type', $search_array['signup_type']);
            }
            if (isset($search_array['newsletter_subscribe']) && $search_array['newsletter_subscribe'] !== "") {
                $this->db->where('newsletter_subscribe', $search_array['newsletter_subscribe']);
            }
            if (isset($search_array['last_log_in_from']) && $search_array['last_log_in_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['last_log_in_from']);
            }
            if (isset($search_array['last_log_in_to']) && $search_array['last_log_in_to'] !== "") {
                $target_date = $search_array['last_log_in_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('status', $search_array['status']);
            }
        }

        $this->db->where('status !=', "3");
        if ($vendor_id !== 0) {
            $this->db->where('id', $vendor_id);
        }

        if ($flag !== 'number') {
            $this->db->order_by('updated_at', 'desc');
        }

        $this->db->from('users_master');
        $result = $this->db->get()->result_array();

        if ($flag === 'number') {
            return $result[0]['total_vendors'];
        } else {
            return $result;
        }
    }

}

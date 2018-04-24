<?php

class CategoryPriceChartMaster_model extends CI_Model {

    /**
     * Added for adding price chart
     * @param array $post_data 
     * @return int
     */
    public function add_price_chart($post_data) {
        $this->db->insert('category_price_chart_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for fetching price
     * chart
     * @param  string $flag             //Added for fetching what kind of result required
     * @param  int    $price_chart_id   //Added for price_chart id
     * @param  array  $search_array     //Added for search array
     * @return int/array                //Depending upon search criteria it will return array or int
     */
    public function fetchPriceChartInfo($flag, $price_chart_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(DISTINCT CPCM.id) AS total_price_chart');
        } else {
            $this->db->distinct();
            $this->db->select('CPCM.*');
        }
        if ($price_chart_id !== 0) {
            $this->db->where('CPCM.id', $price_chart_id);
        }

        $this->db->from('category_price_chart_master AS CPCM');
        $this->db->join('category_master CM_FIRST', 'CPCM.category_id = CM_FIRST.id  ', 'left');
        $this->db->join('category_price_chart_details CPCD', 'CPCD.price_id=CPCM.id AND CPCD.status != 3', 'left');

        if (count($search_array) > 0) {
            if (isset($search_array['menu_name']) && $search_array['menu_name'] !== "") {
                $this->db->like('CPCM.menu_name', $search_array['menu_name']);
            }
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('CPCM.category_id', $search_array['category_id']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('CPCM.status', $search_array['status']);
            }

            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('CPCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('CPCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('CPCM.status !=', "3");
        $this->db->where('CM_FIRST.status !=', "3");

        if ($flag !== 'number') {
            $this->db->order_by('CPCM.updated_at', 'desc');
        }
        if ((isset($search_array['tot_menu_to']) && $search_array['tot_menu_to'] !== "") || (isset($search_array['tot_menu_from']) && $search_array['tot_menu_from'] !== "")) {
            $this->db->group_by('CPCM.id');
            if (isset($search_array['tot_menu_from']) && $search_array['tot_menu_from'] !== "") {
                $this->db->having('count(CPCD.id) >=', $search_array['tot_menu_from']);
            }
            if (isset($search_array['tot_menu_to']) && $search_array['tot_menu_to'] !== "") {
                $this->db->having('count(CPCD.id) <=', $search_array['tot_menu_to']);
            }
        }
        if ($flag === 'number') {
            $subQuery = $this->db->get_compiled_select();
            $result = $this->db->query("select sum(total_price_chart) as sum_of_price_chart from ($subQuery) category_price_chart_master")->result();
        } else {
            $result = $this->db->get()->result_array();
        }

        if ($flag === 'number') {
            return $result[0]->sum_of_price_chart;
        } else {
            return $result;
        }
    }

    /**
     * Added for updating 
     * price chart
     * @param  array $post_data
     * @param  int $id
     */
    public function update_price_chart($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('category_price_chart_master', $post_data);
    }

    /**
     * Added for fetching details
     * of an price chart by name
     * @param  string $name
     * @param  int    $category_id
     * @return array
     */
    public function get_price_chart_name($name, $category_id = 0) {
        $this->db->select('*');
        $this->db->from('category_price_chart_master');
        $this->db->where('menu_name', $name);
        $this->db->where('status !=', '3');
        if ($category_id > 0) {
            $this->db->where('category_id', $category_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for listing price chart
     * @param  array  $search_array //Searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchPriceChartResult($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('CM_FIRST.cat_name,CPCM.id,CPCM.category_id ,CPCM.menu_name,CPCM.picture ,CPCM.status ,CPCM.updated_at, count(DISTINCT CPCD.id) as details_menu_count');
        $this->db->from('category_price_chart_master CPCM');
        $this->db->join('category_master CM_FIRST', 'CPCM.category_id = CM_FIRST.id  ', 'left');
        $this->db->join('category_price_chart_details CPCD', 'CPCM.id = CPCD.price_id AND CPCD.status != 3 ', 'left');

        $this->db->where('CPCM.status !=', '3');
        $this->db->where('CM_FIRST.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('CPCM.category_id', $search_array['category_id']);
            }
            if (isset($search_array['menu_name']) && $search_array['menu_name'] !== "") {
                $this->db->like('CPCM.menu_name', $search_array['menu_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('CPCM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('CPCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('CPCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->group_by('CPCM.id');
        if ((isset($search_array['tot_menu_to']) && $search_array['tot_menu_to'] !== "") || (isset($search_array['tot_menu_from']) && $search_array['tot_menu_from'] !== "")) {
            if (isset($search_array['tot_menu_from']) && $search_array['tot_menu_from'] !== "") {
                $this->db->having('count(CPCD.id) >=', $search_array['tot_menu_from']);
            }
            if (isset($search_array['tot_menu_to']) && $search_array['tot_menu_to'] !== "") {
                $this->db->having('count(CPCD.id) <=', $search_array['tot_menu_to']);
            }
        }

        if ($orderByData === 'menu_name') {
            $this->db->order_by('CPCM.menu_name', $orderByType);
        }
        if ($orderByData === 'details_menu_count') {
            $this->db->order_by('details_menu_count', $orderByType);
        }
        if ($orderByData === 'category_id') {
            $this->db->order_by('CM_FIRST.cat_name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('CPCM.updated_at', $orderByType);
        }
        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        if (isset($result[0])) {
            if (is_null($result[0]['cat_name'])) {
                return array();
            } else {
                return $result;
            }
        } else {
            return array();
        }
        return $result;
    }

    /**
     * Added for fetching price
     * chart details
     * @param  string $flag               //Added for fetching what kind of result required
     * @param  int    $chart_details_id   //Added for chart details id
     * @param  array  $search_array       //Added for search array
     * @return int/array                  //Depending upon search criteria it will return array or int
     */
    public function fetchPriceChartDetailsInfo($flag, $chart_details_id, $search_array = array()) {
        if ($flag === "number") {
            $this->db->select('count(DISTINCT CPCD.id) AS total_price_details');
            $this->db->from('category_price_chart_details CPCD');
            $this->db->join('category_price_chart_master CPCM', 'CPCM.id = CPCD.price_id', 'left');
            $this->db->join('category_master CM_FIRST', 'CPCM.category_id = CM_FIRST.id', 'left');
            $this->db->where('CPCM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('CPCD.status !=', '3');
            if (count($search_array) > 0) {
                if (isset($search_array['menu_name']) && $search_array['menu_name'] !== "") {
                    $this->db->like('CPCD.menu_name', $search_array['menu_name']);
                }
                if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                    $this->db->where('CM_FIRST.id', $search_array['category_id']);
                }
                if (isset($search_array['main_menu_id']) && $search_array['main_menu_id'] !== "") {
                    $this->db->where('CPCD.price_id', $search_array['main_menu_id']);
                }
                if (isset($search_array['highest_price_from']) && $search_array['highest_price_from'] !== "") {
                    $this->db->where('highest_price >=', $search_array['highest_price_from']);
                }
                if (isset($search_array['highest_price_to']) && $search_array['highest_price_to'] !== "") {
                    $this->db->where('highest_price <=', $search_array['highest_price_to']);
                }
                if (isset($search_array['lowest_price_from']) && $search_array['lowest_price_from'] !== "") {
                    $this->db->where('lowest_price >=', $search_array['lowest_price_from']);
                }
                if (isset($search_array['lowest_price_to']) && $search_array['lowest_price_to'] !== "") {
                    $this->db->where('lowest_price <=', $search_array['lowest_price_to']);
                }
                if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                    $this->db->where('CPCD.updated_at >=', $search_array['updated_at_from']);
                }
                if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                    $target_date = $search_array['updated_at_to'];
                    $this->db->where('CPCD.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
                }
                if (isset($search_array['status']) && $search_array['status'] !== "") {
                    $this->db->where('CPCD.status', $search_array['status']);
                }
            }
            $result = $this->db->get()->result_array();
            $final_outcome = $result[0]['total_price_details'];
        } else {
            $this->db->select('CPCD.*,CPCM.category_id as main_category_id');
            $this->db->from('category_price_chart_details CPCD');
            $this->db->join('category_price_chart_master CPCM', 'CPCM.id = CPCD.price_id', 'left');
            $this->db->join('category_master CM_FIRST', 'CPCM.category_id = CM_FIRST.id', 'left');
            $this->db->where('CPCM.status !=', '3');
            $this->db->where('CM_FIRST.status !=', '3');
            $this->db->where('CPCD.status !=', '3');
            $this->db->where('CPCD.id', $chart_details_id);
            $result = $this->db->get()->result_array();
            $final_outcome = $result[0];
        }
        return $final_outcome;
    }

    /**
     * Added for listing price chart
     * details
     * @param  array  $search_array //Searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchPriceChartDetailsResult($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('CM_FIRST.cat_name as category_name,CPCM.menu_name as main_menu_name,CPCD.*');
        $this->db->from('category_price_chart_details CPCD');
        $this->db->join('category_price_chart_master CPCM', 'CPCM.id = CPCD.price_id', 'left');
        $this->db->join('category_master CM_FIRST', 'CPCM.category_id = CM_FIRST.id', 'left');
        $this->db->where('CPCM.status !=', '3');
        $this->db->where('CM_FIRST.status !=', '3');
        $this->db->where('CPCD.status !=', '3');
        if (count($search_array) > 0) {
            if (isset($search_array['menu_name']) && $search_array['menu_name'] !== "") {
                $this->db->like('CPCD.menu_name', $search_array['menu_name']);
            }
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('CM_FIRST.id', $search_array['category_id']);
            }
            if (isset($search_array['main_menu_id']) && $search_array['main_menu_id'] !== "") {
                $this->db->where('CPCD.price_id', $search_array['main_menu_id']);
            }
            if (isset($search_array['highest_price_from']) && $search_array['highest_price_from'] !== "") {
                $this->db->where('highest_price >=', $search_array['highest_price_from']);
            }
            if (isset($search_array['highest_price_to']) && $search_array['highest_price_to'] !== "") {
                $this->db->where('highest_price <=', $search_array['highest_price_to']);
            }
            if (isset($search_array['lowest_price_from']) && $search_array['lowest_price_from'] !== "") {
                $this->db->where('lowest_price >=', $search_array['lowest_price_from']);
            }
            if (isset($search_array['lowest_price_to']) && $search_array['lowest_price_to'] !== "") {
                $this->db->where('lowest_price <=', $search_array['lowest_price_to']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('CPCD.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('CPCD.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('CPCD.status', $search_array['status']);
            }
        }
        if ($orderByData === 'menu_name') {
            $this->db->order_by('CPCD.menu_name', $orderByType);
        }
        if ($orderByData === 'highest_price') {
            $this->db->order_by('highest_price', $orderByType);
        }
        if ($orderByData === 'main_menu_id') {
            $this->db->order_by('CPCM.menu_name', $orderByType);
        }
        if ($orderByData === 'lowest_price') {
            $this->db->order_by('lowest_price', $orderByType);
        }
        if ($orderByData === 'category_id') {
            $this->db->order_by('CM_FIRST.cat_name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('CPCD.updated_at', $orderByType);
        }
        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching details
     * of an price chart details by name
     * @param  string $name
     * @param  int    $price_id
     * @return array
     */
    public function get_price_chart_name_detail($name, $price_id = 0) {
        $this->db->select('*');
        $this->db->from('category_price_chart_details');
        $this->db->where('menu_name', $name);
        $this->db->where('status !=', '3');
        if ($price_id > 0) {
            $this->db->where('price_id', $price_id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for fetching details 
     * for edit
     * of an price chart details by name
     * @param  string $name
     * @param  int    $price_id
     * @param  int    $id
     * @return array
     */
    public function get_price_chart_name_edit_detail($name, $price_id = 0, $id = 0) {
        $this->db->select('*');
        $this->db->from('category_price_chart_details');
        $this->db->where('menu_name', $name);
        $this->db->where('status !=', '3');
        if ($price_id > 0) {
            $this->db->where('price_id', $price_id);
        }
        if ($id > 0) {
            $this->db->where('id !=', $id);
        }
        $query = $this->db->get();
        return $query->row_array();
    }

    /**
     * Added for category_price_chart_details
     * @param array $post_data 
     * @return int
     */
    public function add_price_chart_details($post_data) {
        $this->db->insert('category_price_chart_details', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating 
     * price chart details
     * @param  array $post_data
     * @param  int $id
     */
    public function update_price_chart_details($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('category_price_chart_details', $post_data);
    }

}

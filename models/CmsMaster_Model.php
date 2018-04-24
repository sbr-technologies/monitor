<?php

class CmsMaster_Model extends CI_Model {

    /**
     * Added for updating static
     * image information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_static_image($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('static_image_manager', $post_data);
    }

    /**
     * Added for listing all active
     * images
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchStaticImageList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('SIM.id,SIM.name,SIM.image_name,SIM.updated_at');
        $this->db->from('static_image_manager SIM');

        if (count($search_array) > 0) {
            if (isset($search_array['name']) && $search_array['name'] !== "") {
                $this->db->like('SIM.name', $search_array['name']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('SIM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('SIM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }
        if ($orderByData === 'name') {
            $this->db->order_by('SIM.name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('SIM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching images
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $id   //Added for image id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchStaticImageInfo($flag, $id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(SIM.id) AS total_static_image');
        } else {
            $this->db->select('SIM.*');
        }
        if ($id !== 0) {
            $this->db->where('SIM.id', $id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['name']) && $search_array['name'] !== "") {
                $this->db->like('SIM.name ', $search_array['name']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('SIM.updated_at >=', $search_array['updated_at_from']);
            }

            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('SIM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        if ($flag !== 'number') {
            $this->db->order_by('SIM.updated_at', 'desc');
        }

        $this->db->from('static_image_manager SIM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['total_static_image'];
        } else {
            return $result;
        }
    }

    /**
     * Added for page SEO 
     * setting
     * @param int $id
     * @return array
     */
    public function pageSeoInfo($id) {
        $result = $this->db->get_where('pages_seo_info', array('id' => $id))->result_array();
        return $result;
    }

    /**
     * Added for updating
     * SEO for the pages
     * @param type $post_data
     * @param type $id
     */
    public function update_seo($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('pages_seo_info', $post_data);
    }

    /**
     * Added for fetching language content 
     * information based on multilingual 
     * information
     * @param string $flag
     * @param int $language_id
     * @param array $search_array
     * @return array
     */
    public function fetchLanguageContentInfo($flag, $language_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(id) AS language_count');
        } else {
            $this->db->select('*');
        }
        if ($language_id !== 0) {
            $this->db->where('id', $language_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['word_en']) && $search_array['word_en'] !== "") {
                $this->db->like('word_en', $search_array['word_en']);
            }
            if (isset($search_array['word_iw']) && $search_array['word_iw'] !== "") {
                $this->db->like('word_iw', $search_array['word_iw']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('datetime >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('datetime <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }
        if ($flag !== 'number') {
            $this->db->order_by('datetime', 'desc');
        }
        $this->db->from('language_master');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['language_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for fetching language contents
     * @param array $search_array
     * @param string $orderByData
     * @param string $orderByType
     * @param int $start
     * @param int $length
     */
    public function fetchLanguageContentList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('*');
        $this->db->from('language_master');
        if (count($search_array) > 0) {
            if (isset($search_array['word_en']) && $search_array['word_en'] !== "") {
                $this->db->like('word_en', $search_array['word_en']);
            }
            if (isset($search_array['word_iw']) && $search_array['word_iw'] !== "") {
                $this->db->like('word_iw', $search_array['word_iw']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('datatime >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('datatime <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }
        if ($orderByData === 'word_en') {
            $this->db->order_by('word_en', $orderByType);
        }
        if ($orderByData === 'word_iw') {
            $this->db->order_by('word_iw', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('datetime', $orderByType);
        }
        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for inserting
     * new content
     * @param array $post_data
     * @return int
     */
    public function add_new_content($post_data) {
        $this->db->insert('language_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id;
    }

    /**
     * Added for updating content
     * @param array $post_data
     * @param int $id
     */
    public function update_content($post_data, $id) {
        $this->db->where('id', $id);
        $this->db->update('language_master', $post_data);
    }

    /**
     * Fetching result details
     * @return array
     */
    public function languageDetails() {
        $query = $this->db->get('language_master');
        if ($query->num_rows() > 0) {
            return $query->result_array();
        } else {
            return array();
        }
    }

}

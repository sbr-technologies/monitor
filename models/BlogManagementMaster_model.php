<?php

class BlogManagementMaster_model extends CI_Model {
    
    /**
     * Added for addition
     * blog category 
     * @param array
     * @return int //inserted id
     */
    public function add_blog_category($post_data){
        $this->db->insert('blog_category_master', $post_data);
        $insert_id = $this->db->insert_id();
        return $insert_id ; 
    }

    /**
     * Added for update
     * blog category 
     * @param  array $post_data 
     * @param  int $id        
     * @return int            
     */
    public function update_blog_category($post_data,$id){
        $this->db->where('id', $id);
        $this->db->update('blog_category_master', $post_data);
    }

    /**
     * Added for updating 
     * blog information
     * @param  array $post_data
     * @param  int $id
     */
    public function update_blog($post_data, $id, $tag_array, $blog_category) {
        $this->db->where('id', $id);
        $this->db->update('blog_master', $post_data);
        $this->db->delete('blog_tag_details', array("blog_id" => $id));
        $this->db->delete('category_blog_master', array("blog_id" => $id));
        /* ---inserting for tags starts here--- */
        if (count($tag_array) > 0) {
            foreach ($tag_array as $key => $value) {
                $add_tag_info["added_date"] = date("y-m-d H:i:s");
                $add_tag_info["tag_name"] = $value;
                $add_tag_info["blog_id"] = $id;
                $tag_blog_master[] = $add_tag_info;
            }
            $this->db->insert_batch('blog_tag_details', $tag_blog_master);
        }
        /* ---inserting for tags ends here--- */

        /* ---inserting for blog category starts here--- */
        if (count($blog_category) > 0) {
            foreach ($blog_category as $key => $value) {
                $add_blog_info["category_id"] = $value;
                $add_blog_info["blog_id"] = $id;
                $blog_category_master[] = $add_blog_info;
            }
            $this->db->insert_batch('category_blog_master', $blog_category_master);
        }
        /* ---inserting for blog category ends here--- */
    }

    /**
     * Added for getting the details of 
     * a blog category based on
     * the $result_type parameter
     * @param  string $result_type        //If number it will return the number record for a name
     * @param  string $blog_category_name  //Name of the target blog category
     * @return array/int                  //Array for the result or count the number of records
     */
    public function get_details_blog_category_name($result_type = "", $blog_category_name) {
        if ($result_type === "number") {
            $this->db->select('count(id) AS blog_category_count');
        } else {
            $this->db->select('*');
        }
        $this->db->where('status !=', "3");
        $this->db->where('category_name', $blog_category_name);
        $this->db->from('blog_category_master');
        $result = $this->db->get()->result_array();
        if ($result_type === "number") {
            return $result[0]['blog_category_count'];
        } else {
            return $result;
        }
    }

    /**
     * Added for listing all active
     * blog category
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchBlogCategoryList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->select('BCM.id,BCM.category_name,BCM.status,BCM.updated_at');
        $this->db->from('blog_category_master BCM');
        $this->db->where('BCM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['category_name']) && $search_array['category_name'] !== "") {
                $this->db->like('BCM.category_name', $search_array['category_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BCM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        if ($orderByData === 'name') {
            $this->db->order_by('BCM.category_name', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('BCM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching blog categoies
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $blog_category_id   //Added for blog category id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchBlogCategoryInfo($flag, $blog_category_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(BCM.id) AS total_blog_category');
        } else {
            $this->db->select('BCM.*');
        }
        if ($blog_category_id > 0) {
            $this->db->where('BCM.id', $blog_category_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['category_name']) && $search_array['category_name'] !== "") {
                $this->db->like('BCM.category_name', $search_array['category_name']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BCM.status', $search_array['status']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BCM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BCM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
        }

        $this->db->where('BCM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('BCM.updated_at', 'desc');
        }

        $this->db->from('blog_category_master BCM');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['total_blog_category'];
        } else {
            return $result;
        }
    }

    /**
     * Added for inserting
     * new blog
     * @param array $post_data
     * @param array $tag_array
     * @param array $blog_category
     * @return int
     */
    public function add_blog_master($post_data, $tag_array, $blog_category) {
        $this->db->insert('blog_master', $post_data);
        $insert_id = $this->db->insert_id();
        if ($insert_id) {
            /* ---inserting for tags starts here--- */
            if (count($tag_array) > 0) {
                foreach ($tag_array as $key => $value) {
                    $add_tag_info["added_date"] = date("y-m-d H:i:s");
                    $add_tag_info["tag_name"] = $value;
                    $add_tag_info["blog_id"] = $insert_id;
                    $tag_blog_master[] = $add_tag_info;
                }
                $this->db->insert_batch('blog_tag_details', $tag_blog_master);
            }
            /* ---inserting for tags ends here--- */

            /* ---inserting for blog category starts here--- */
            if (count($blog_category) > 0) {
                foreach ($blog_category as $key => $value) {
                    $add_blog_info["category_id"] = $value;
                    $add_blog_info["blog_id"] = $insert_id;
                    $blog_category_master[] = $add_blog_info;
                }
                $this->db->insert_batch('category_blog_master', $blog_category_master);
            }
            /* ---inserting for blog category ends here--- */
        }
        return $insert_id;
    }

    /**
     * Added for listing all active
     * blogs 
     * @param  array  $search_array //searing criteria array to list the table
     * @param  string $orderByData  //Which colomn to be ordered
     * @param  string $orderByType  //In which order the colomn is to be ordered
     * @param  int    $start        //From where the listing is going to start
     * @param  int    $length       //Upto which limit lisitng will go on
     * @return array                //Result of the query
     */
    public function fetchBlogList($search_array, $orderByData, $orderByType, $start, $length) {
        $this->db->distinct();
        $this->db->select('BM.*');
        $this->db->from('blog_master BM');
        $this->db->join('category_blog_master CBM', 'BM.id = CBM.blog_id', 'left');
        $this->db->join('blog_category_master BCM', 'CBM.category_id = BCM.id', 'left');
        $this->db->where('BM.status !=', '3');
        $this->db->where('BCM.status !=', '3');

        if (count($search_array) > 0) {
            if (isset($search_array['head_line']) && $search_array['head_line'] !== "") {
                $this->db->like('BM.head_line', $search_array['head_line']);
            }
            if (isset($search_array['author_name']) && $search_array['author_name'] !== "") {
                $this->db->like('BM.author', $search_array['author_name']);
            }
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('BCM.id >=', $search_array['category_id']);
            }
            if (isset($search_array['tot_view_from']) && $search_array['tot_view_from'] !== "") {
                $this->db->where('BM.total_viewers >=', $search_array['tot_view_from']);
            }
            if (isset($search_array['tot_view_to']) && $search_array['tot_view_to'] !== "") {
                $this->db->where('BM.total_viewers <=', $search_array['tot_view_to']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
                $this->db->where('BM.is_featured', $search_array['is_featured']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BM.status', $search_array['status']);
            }
        }

        if ($orderByData === 'head_line') {
            $this->db->order_by('BM.head_line', $orderByType);
        }
        if ($orderByData === 'author_name') {
            $this->db->order_by('BM.author', $orderByType);
        }

        if ($orderByData === 'tot_view') {
            $this->db->order_by('BM.total_viewers', $orderByType);
        }
        if ($orderByData === 'updated_at') {
            $this->db->order_by('BM.updated_at', $orderByType);
        }

        $this->db->limit($length, $start);
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching blogs 
     * @param  string $flag         //Added for fetching what kind of result required
     * @param  int    $blog_id   //Added for blog id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchBlogInfo($flag, $blog_id, $search_array = array()) {
        if ($flag === 'number') {
            $this->db->select('count(DISTINCT BM.id) AS total_blog');
        } else {
            $this->db->distinct();
            $this->db->select('BM.*');
        }
        if ($blog_id > 0) {
            $this->db->where('BM.id', $blog_id);
        }
        if (count($search_array) > 0) {
            if (isset($search_array['head_line']) && $search_array['head_line'] !== "") {
                $this->db->like('BM.head_line', $search_array['head_line']);
            }
            if (isset($search_array['author_name']) && $search_array['author_name'] !== "") {
                $this->db->like('BM.author', $search_array['author_name']);
            }
            if (isset($search_array['category_id']) && $search_array['category_id'] !== "") {
                $this->db->where('BCM.id >=', $search_array['category_id']);
            }
            if (isset($search_array['tot_view_from']) && $search_array['tot_view_from'] !== "") {
                $this->db->where('BM.total_viewers >=', $search_array['tot_view_from']);
            }
            if (isset($search_array['tot_view_from']) && $search_array['tot_view_from'] !== "") {
                $this->db->where('BM.total_viewers >=', $search_array['tot_view_from']);
            }
            if (isset($search_array['tot_view_to']) && $search_array['tot_view_to'] !== "") {
                $this->db->where('BM.total_viewers <=', $search_array['tot_view_to']);
            }
            if (isset($search_array['updated_at_from']) && $search_array['updated_at_from'] !== "") {
                $this->db->where('BM.updated_at >=', $search_array['updated_at_from']);
            }
            if (isset($search_array['updated_at_to']) && $search_array['updated_at_to'] !== "") {
                $target_date = $search_array['updated_at_to'];
                $this->db->where('BM.updated_at <=', strftime("%Y-%m-%d", strtotime("$target_date +1 day")));
            }
            if (isset($search_array['is_featured']) && $search_array['is_featured'] !== "") {
                $this->db->where('BM.is_featured', $search_array['is_featured']);
            }
            if (isset($search_array['status']) && $search_array['status'] !== "") {
                $this->db->where('BM.status', $search_array['status']);
            }
        }

        $this->db->where('BM.status !=', "3");
        $this->db->where('BCM.status !=', "3");
        if ($flag !== 'number') {
            $this->db->order_by('BM.updated_at', 'desc');
        }

        $this->db->from('blog_master BM');
        $this->db->join('category_blog_master CBM', 'BM.id = CBM.blog_id', 'left');
        $this->db->join('blog_category_master BCM', 'CBM.category_id = BCM.id', 'left');
        $result = $this->db->get()->result_array();
        if ($flag === 'number') {
            return $result[0]['total_blog'];
        } else {
            return $result;
        }
    }

    /**
     * Added for fetching blogs 
     * @param  int    $blog_id   //Added for blog id
     * @param  array  $search_array //Added for search array
     * @return int/array            //Depending upon search criteria it will return array or int
     */
    public function fetchBlogRelatedCategoryInfo($blog_id, $search_array = array()) {
        $this->db->select('BCM.*');
        $this->db->where('BM.id', $blog_id);
        $this->db->where('BM.status !=', "3");
        $this->db->where('BCM.status !=', "3");
        $this->db->from('blog_master BM');
        $this->db->join('category_blog_master CBM', 'BM.id = CBM.blog_id', 'left');
        $this->db->join('blog_category_master BCM', 'CBM.category_id = BCM.id', 'left');
        $result = $this->db->get()->result_array();
        return $result;
    }

    /**
     * Added for fetching tag 
     * @param  int blog id
     * @return array 
     */
    public function fetchBlogRelatedTagInfo($blog_id)
    {
        $this->db->select('*');
        $this->db->from('blog_tag_details');
        if ($blog_id !== 0) {
            $this->db->where('blog_id', $blog_id);
        }
        $result = $this->db->get()->result_array();
        return $result;
    }
    
    /**
     * Added for fetching block
     * by head line 
     * @param  array  $search_array 
     * @param  int $blog_id      
     * @return int              
     */
    public function get_block_by_name($flag,$search_array = array(),$blog_id)
    {   
        if($flag ==="number"){
            $this->db->select('count(DISTINCT BM.id) AS total_blog');
        } else {
            $this->db->select('BM.*');
        }
        if (count($search_array) > 0) {
            if (isset($search_array['head_line']) && $search_array['head_line'] !== "") {
                $this->db->where('BM.head_line', $search_array['head_line']);
            }
            if (isset($search_array['blog_category']) && $search_array['blog_category'] !== "") {
                $this->db->where('BCM.id',$search_array['blog_category'][0]);
            }
        }
        if ($blog_id > 0) {
            $this->db->where('BM.id!=', $blog_id);
        }
        $this->db->where('BM.status !=', "3");
        $this->db->where('BCM.status !=', "3");
        $this->db->from('blog_master BM');
        $this->db->join('category_blog_master CBM', 'BM.id = CBM.blog_id', 'left');
        $this->db->join('blog_category_master BCM', 'CBM.category_id = BCM.id', 'left');        
        $result = $this->db->get()->result_array();
        if($flag ==="number"){
            return $result[0]['total_blog'];
        } else {
            return $result ; 
        }
    }


}

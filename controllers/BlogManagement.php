<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BlogManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('BlogManagementMaster_model', 'blogManagementMaster_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for listing
     * all the blog categories
     */
    public function index() {
        $data['title'] = 'All Blog Categories';
        $this->load->view('blog-management/index', $data);
    }

    /**
     * Added for all the 
     * blog categories through ajax
     */
    public function ajaxBlogCategory() {
        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('category_name') !== "") {
                $search_array['category_name'] = $this->input->post('category_name');
            }

            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_to') !== "") {
                $search_array['updated_at_to'] = $this->input->post('updated_at_to');
            }
            if ($this->input->post('status') !== "") {
                $search_array['status'] = $this->input->post('status');
            }

            $search_array = $this->security->xss_clean($search_array); /* ---Added for filtering array,furst cleaned the data and then array_filter is cleaning the blank keys of the array means the blank ibputs or only the white space inputs got flushed--- */
            /* ---Search data array ended--- */

            /* ---Index of the sorting column (0 index based - i.e. 0 is the first record)--- */
            $orderState = $this->input->post('order');
            $orderByColumnIndex = $orderState[0]['column'];

            /* ---Get name of the sorting column from its index--- */
            $colomnToSort = $this->input->post('columns');
            $orderBy = $colomnToSort[$orderByColumnIndex]['data'];
            /* ---Added for checking what kind of order ASC or DESC--- */
            $orderTypeState = $this->input->post('order');
            $orderByType = $orderTypeState[0]['dir'];
            /* ---Get the number of records to be fetched--- */
            $iTotalRecords = $this->blogManagementMaster_model->fetchBlogCategoryInfo('number', 0, $search_array);

            /* ---Get the number of data is going to be shown in the table--- */
            $iDisplayLength = intval($this->input->post('length'));
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;

            /* ---Get the start point of the table,this is the number of colomn from where the table is going to generate--- */
            $iDisplayStart = intval($this->input->post('start'));

            /* ---counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables--- */
            $sEcho = intval($this->input->post('draw'));

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            if ($orderBy === '0') {
                $orderByData = 'name';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->blogManagementMaster_model->fetchBlogCategoryList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);

            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-blog-category/' . $encrypted_id) . '" class="btn btn-icon-only green " data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red " id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['category_name'] . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button>';

                $records["data"][] = array($res['category_name'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for inserting new
     * blog category
     */
    public function addBlogCategory() {
        $data['title'] = 'Add Blog Category';
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_blog_category_name_exist_add',
                'errors' => array(
                    'required' => 'Please provide blog category name',
                    'check_blog_category_name_exist_add' => 'Oops !!! Blog category name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('blog-management/addBlogCategory', $data);
        } else {
            $inserting_data['category_name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->blogManagementMaster_model->add_blog_category($inserting_data);
            redirect('blogcategory-list');
        }
    }

    /**
     * Added for checking 
     * blog category name is enlisted or 
     * not
     * @param string $name
     * @return boolean
     */
    public function check_blog_category_name_exist_add($name) {
        $search_array = array();
        $search_array['category_name'] = $name; 
        $result = $this->blogManagementMaster_model->fetchBlogCategoryInfo('number',0,$search_array);
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for checking 
     * blog category name is enlisted or 
     * not through ajax
     * @param string $name
     */
    public function ajax_blog_category_name_exist_add() {
        if ($this->input->is_ajax_request()) {
            $search_array = array();
            if ($this->input->post('name', true) !== "") {
                $search_array['category_name'] = $this->input->post('name');
                $result = $this->blogManagementMaster_model->fetchBlogCategoryInfo('number',0,$search_array);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for deleting blog category
     */
    public function deleteBlogCategory() {
        if ($this->input->is_ajax_request()) {
            $blog_category_id = $this->input->post('blog_category_id');
            $main_blog_category_id = $this->encrypt_decrypt->decrypt($blog_category_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->blogManagementMaster_model->update_blog_category($update_array, $main_blog_category_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing
     * blog category
     */
    public function editBlogCategory() {
        $data['title'] = 'Edit Blog Category';
        $encrypted_blog_category_id = $this->uri->segment(2);
        $main_blog_category_id = $this->encrypt_decrypt->decrypt($encrypted_blog_category_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all', $main_blog_category_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_blog_category_id;
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_blog_category_name_exist_edit',
                'errors' => array(
                    'required' => 'Please provide blog category name',
                    'check_blog_category_name_exist_edit' => 'Oops !!! Blog category name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('blog-management/editBlogCategory', $data);
        } else {
            $updating_data['category_name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $updating_data['status'] = '1';
            } else {
                $updating_data['status'] = '0';
            }
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->blogManagementMaster_model->update_blog_category($updating_data, $main_blog_category_id);
            redirect('blogcategory-list');
        }
    }

    /**
     * Added for checking whether blog category name is
     * already existed or not
     * @return boolean
     */
    public function check_blog_category_name_exist_edit() {
        $search_array = array();
        $blog_category_id = $this->input->post('blog_category_id');
        $search_array['category_name'] = $this->input->post('name', true);
        $result = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all',0,$search_array);
        if (count($result) === 0) {
            $response = true;
        } else {
            if ($result[0]['id'] === $blog_category_id) {
                $response = true;
            } else {
                $response = false;
            }
        }
        return $response;
    }

    /**
     * Added for checking a 
     * blog category name exist or not at the time of editing
     */
    public function check_blog_category_edit_ajax() {
        if ($this->input->is_ajax_request()) {
            $search_array = array();
            $blog_category_id = $this->input->post('blog_category_id');
            $search_array['category_name'] = $this->input->post('name', true);
            $result = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all',0,$search_array);
            if (count($result) === 0) {
                $response = "true";
            } else {
                if ($result[0]['id'] === $blog_category_id) {
                    $response = "true";
                } else {
                    $response = "false";
                }
            }
            echo $response;
        }
    }

    /**
     * Added for listing
     * all the blogs 
     */
    public function blogMasterList() {
        $data['blog_category'] = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all', 0, array());
        $data['title'] = 'All Blogs';
        $this->load->view('blog-management/blogMasterList', $data);
    }

    /**
     * Added for all the 
     * blogs through ajax
     */
    public function ajaxBlogMaster() {
        if ($this->input->is_ajax_request()) {
            /* ---Searching array blank initialized--- */
            $search_array = array();
            $records = array();
            $records["data"] = array();

            /* ---Search data array started--- */
            if ($this->input->post('head_line') !== "") {
                $search_array['head_line'] = $this->input->post('head_line');
            }
            if ($this->input->post('author_name') !== "") {
                $search_array['author_name'] = $this->input->post('author_name');
            }
            if ($this->input->post('category_id') !== "") {
                $search_array['category_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('tot_view_from') !== "") {
                $search_array['tot_view_from'] = $this->input->post('tot_view_from');
            }
            if ($this->input->post('tot_view_to') !== "") {
                $search_array['tot_view_to'] = $this->input->post('tot_view_to');
            }
            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_to') !== "") {
                $search_array['updated_at_to'] = $this->input->post('updated_at_to');
            }
            if ($this->input->post('is_featured') !== "") {
                $search_array['is_featured'] = $this->input->post('is_featured');
            }
            if ($this->input->post('status') !== "") {
                $search_array['status'] = $this->input->post('status');
            }
            $search_array = $this->security->xss_clean($search_array); /* ---Added for filtering array,furst cleaned the data and then array_filter is cleaning the blank keys of the array means the blank ibputs or only the white space inputs got flushed--- */
            /* ---Search data array ended--- */

            /* ---Index of the sorting column (0 index based - i.e. 0 is the first record)--- */
            $orderState = $this->input->post('order');
            $orderByColumnIndex = $orderState[0]['column'];

            /* ---Get name of the sorting column from its index--- */
            $colomnToSort = $this->input->post('columns');
            $orderBy = $colomnToSort[$orderByColumnIndex]['data'];

            /* ---Added for checking what kind of order ASC or DESC--- */
            $orderTypeState = $this->input->post('order');
            $orderByType = $orderTypeState[0]['dir'];

            /* ---Get the number of records to be fetched--- */
            $iTotalRecords = $this->blogManagementMaster_model->fetchBlogInfo('number', 0, $search_array);

            /* ---Get the number of data is going to be shown in the table--- */
            $iDisplayLength = intval($this->input->post('length'));
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;

            /* ---Get the start point of the table,this is the number of colomn from where the table is going to generate--- */
            $iDisplayStart = intval($this->input->post('start'));

            /* ---counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables--- */
            $sEcho = intval($this->input->post('draw'));

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            if ($orderBy === '0') {
                $orderByData = 'head_line';
            } elseif ($orderBy === '1') {
                $orderByData = 'author_name';
            } elseif ($orderBy === '2') {
                $orderByData = 'category_name';
            } elseif ($orderBy === '3') {
                $orderByData = 'tot_view';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->blogManagementMaster_model->fetchBlogList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                $fetch_cat_name = array();
                if ($res['blog_image'] != "") {
                    $image_html = '<img src="' . ASSET_URL . "uploads/blog-images/thumb/" . $res['blog_image'] . '"><br/>' . $res['head_line'];
                } else {
                    $image_html = '<img src="' . ASSET_URL . 'uploads/blog-images/thumb/noimage.jpg "><br/>' . $res['head_line'];
                }

                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                if ($res['is_featured'] === '1') {
                    $is_featured = '<img src="' . ASSET_URL . 'global/img/thumbs_up.png">';
                } else {
                    $is_featured = '<img src="' . ASSET_URL . 'global/img/thumbs_down.png">';
                }
                $category = $this->blogManagementMaster_model->fetchBlogRelatedCategoryInfo($res['id'], $search_array);
                foreach ($category as $value) {
                    $fetch_cat_name[] = $value['category_name'];
                }
                $show_cat_name = implode(', ', $fetch_cat_name);
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-blogmaster/' . $encrypted_id) . '" class="btn btn-icon-only green " data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red " id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['head_line'] . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button>';
                $records["data"][] = array($image_html, $res['author'], $show_cat_name, $res['total_viewers'], date("Y-m-d", strtotime($res['updated_at'])), $is_featured, $status_html, $button_html);
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for
     * blog insertion
     */
    public function addBlogMaster() {
        $data['title'] = 'Add Blog';
        $data['blog_category'] = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all', 0, array());
        $config = array(
            array(
                'field' => 'blog_category[]',
                'label' => 'blog_category[]',
                'set_value' => 'blog_category',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select blog category'
                ),
            ),
            array(
                'field' => 'tags',
                'label' => 'tags',
                'set_value' => 'tags',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide tags'
                ),
            ),
            array(
                'field' => 'author',
                'label' => 'author',
                'set_value' => 'author',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide author'
                ),
            ),
            array(
                'field' => 'head_line',
                'label' => 'head_line',
                'set_value' => 'head_line',
                'rules' => 'trim|required|callback_check_head_line_exist_add',
                'errors' => array(
                    'required' => 'Please provide head line',
                    'check_head_line_exist_add' => 'Oops !!! Head line is already taken'
                ),
            ),
            array(
                'field' => 'meta_tag',
                'label' => 'meta_tag',
                'set_value' => 'meta_tag',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta tag'
                ),
            ),
            array(
                'field' => 'meta_description',
                'label' => 'meta_description',
                'set_value' => 'meta_description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta description'
                ),
            ),
            array(
                'field' => 'meta_keywords',
                'label' => 'meta_keywords',
                'set_value' => 'meta_keywords',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta keywords'
                ),
            ),
            array(
                'field' => 'description',
                'label' => 'description',
                'set_value' => 'description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide description'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('blog-management/addBlogMaster', $data);
        } else {
            $blog_cat = $this->input->post('blog_category');
            $inserting_data['head_line'] = $this->input->post('head_line');
            $inserting_data['meta_tag'] = $this->input->post('meta_tag');
            $inserting_data['author'] = $this->input->post('author');
            $inserting_data['meta_description'] = $this->input->post('meta_description');
            $inserting_data['meta_keywords'] = $this->input->post('meta_keywords');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }

            if ($this->input->post('is_featured') === 'on') {
                $inserting_data['is_featured'] = '1';
            } else {
                $inserting_data['is_featured'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data['total_viewers'] = 0;
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserting_data['description'] = htmlentities($this->input->post('description'));
            /* ---inserting blog tag starts----- */
            $tag_input = $this->input->post('tags');
            $tag_input_array = explode(",", $tag_input);
            $tag_input_array = $this->security->xss_clean($tag_input_array);
            /* ---inserting blog tag ends----- */

            /* ---inserting blog category ends----- */
            $blog_category = $this->input->post('blog_category');
            $blog_category = $this->security->xss_clean($blog_category);
            /* ---inserting blog category ends----- */

            /* ---Added for blog slug creation--- */
            $blog_slug = urlslug($inserting_data['head_line']);
            $search_array['blog_slug'] = $blog_slug;
            $data_resut_info = $this->blogManagementMaster_model->fetchBlogInfo("number", "", $search_array);
            if ($data_resut_info == 0) {
                $inserting_data['blog_slug'] = $blog_slug;
            } else {
                $inserting_data['blog_slug'] = $blog_slug = $blog_slug . "-" . time();
            }
            /* ---Added for blog slug creation--- */
            $source_image_path = UPLOADING_PATH . '/blog-images/original/';
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config_up['file_name'] = $blog_slug;
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('blog_image')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . '/blog-images/preview/';
                _resize_avtar($upload_data['file_name'], "388", "249", $preview_target, $source_image_path, TRUE);
                $thumb_target = UPLOADING_PATH . '/blog-images/thumb/';
                _resize_avtar($upload_data['file_name'], "100", "70", $thumb_target, $source_image_path, TRUE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($thumb_target . $upload_data['file_name'], 0777);
                $file_name = $upload_data['file_name'];
            } else {
                $file_name = "";
            }
            $inserting_data['blog_image'] = $file_name;
            $inserted_id = $this->blogManagementMaster_model->add_blog_master($inserting_data, $tag_input_array, $blog_category);
            redirect('blogmaster-list');
        }
    }

    /**
     * Added for checking 
     * blog head line is enlisted or 
     * not
     * @param string $head_line
     * @return boolean
     */
    public function check_head_line_exist_add($head_line) {
        $search_array = array();
        if ($head_line !== "") {
            $search_array['head_line'] = $head_line ;  
        }
        $result = $this->blogManagementMaster_model->get_block_by_name('number',$search_array,'0');
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for checking 
     * blog head line is enlisted or 
     * not through ajax
     * @param string $head_line
     */
    public function ajax_head_line_exist_add() {
        if ($this->input->is_ajax_request()) {
            $search_array = array();
            if ($this->input->post('head_line', true) !== "") {
                $search_array['head_line'] = $this->input->post('head_line');
                $blog_category = $this->input->post('blog_category');
                $blog_category = explode(',', $blog_category);
                $search_array['blog_category'] = $blog_category;
                $result = $this->blogManagementMaster_model->get_block_by_name('number',$search_array,'0');
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for deleting blog 
     */
    public function deleteBlog() {
        if ($this->input->is_ajax_request()) {
            $blog_id = $this->input->post('blog_id');
            $main_blog_id = $this->encrypt_decrypt->decrypt($blog_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->blogManagementMaster_model->update_blog($update_array, $main_blog_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    public function editBlogMaster() {
        $data['title'] = 'Edit Blog';
        $encrypted_blog_id = $this->uri->segment(2);
        $main_blog_id = $this->encrypt_decrypt->decrypt($encrypted_blog_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->blogManagementMaster_model->fetchBlogInfo('all', $main_blog_id, array());
        $id = $result[0]['id'];
        $data['blog_category'] = $this->blogManagementMaster_model->fetchBlogCategoryInfo('all', 0, array());
        $data['blog_category_id'] = $this->blogManagementMaster_model->fetchBlogRelatedCategoryInfo($id, array());
        $tags_info = $this->blogManagementMaster_model->fetchBlogRelatedTagInfo($id);
        foreach ($tags_info as $value) {
            $tag_new[] = $value['tag_name'];
        }
        $tags_info_new = implode(',', $tag_new);
        $data['tags_info'] = $tags_info_new;
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_blog_id;
        $config = array(
            array(
                'field' => 'blog_category[]',
                'label' => 'blog_category[]',
                'set_value' => 'blog_category',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select blog category'
                ),
            ),
            array(
                'field' => 'tags',
                'label' => 'tags',
                'set_value' => 'tags',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide tags'
                ),
            ),
            array(
                'field' => 'author',
                'label' => 'author',
                'set_value' => 'author',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide author'
                ),
            ),
            array(
                'field' => 'head_line',
                'label' => 'head_line',
                'set_value' => 'head_line',
                'rules' => 'trim|required|callback_check_blog_head_line_exist_edit',
                'errors' => array(
                    'required' => 'Please provide head line',
                    'check_blog_head_line_exist_edit' => 'Oops !!! Head line is already taken for another blog'
                ),
            ),
            array(
                'field' => 'meta_tag',
                'label' => 'meta_tag',
                'set_value' => 'meta_tag',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta tag'
                ),
            ),
            array(
                'field' => 'meta_description',
                'label' => 'meta_description',
                'set_value' => 'meta_description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta description'
                ),
            ),
            array(
                'field' => 'meta_keywords',
                'label' => 'meta_keywords',
                'set_value' => 'meta_keywords',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide meta keywords'
                ),
            ),
            array(
                'field' => 'description',
                'label' => 'description',
                'set_value' => 'description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide description'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('blog-management/editBlogMaster', $data);
        } else {
            $source_image_path = UPLOADING_PATH . '/blog-images/original/';
            $files_name = 'blog-picture-' . rand(10000, 99999) . "-" . time() . "-" . rand(10000, 99999);
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config_up['file_name'] = $files_name;
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('blog_image')) {
                $upload_data = $this->upload->data();
            } else {
                $data['image_error'] = $this->upload->display_errors();
            }
            if ($_FILES['blog_image']['name'] !== "") {
                $file_name = $upload_data['file_name'];
            } else if ($this->input->post('hidden_image') !== "") {
                $file_name = $this->input->post('hidden_image');
            } else {
                $file_name = "";
            }
            if (($file_name !== "") || ($_FILES['blog_image']['name'] !== "") || ($this->input->post('hidden_image') !== "")) {
                $preview_target = UPLOADING_PATH . '/blog-images/preview/';
                _resize_avtar($file_name, "388", "249", $preview_target, $source_image_path, TRUE);
                $thumb_target = UPLOADING_PATH . '/blog-images/thumb/';
                _resize_avtar($file_name, "100", "70", $thumb_target, $source_image_path, TRUE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $file_name, 0777);
                chmod($preview_target . $file_name, 0777);
                chmod($thumb_target . $file_name, 0777);
                /* ====================unlink image====================== */
                if ($result[0]['blog_image'] !== "" && $_FILES['blog_image']['name'] !== "") {
                    unlink($source_image_path . $result[0]['blog_image']);
                    unlink($preview_target . $result[0]['blog_image']);
                    unlink($thumb_target . $result[0]['blog_image']);
                }
            }
            /* ====================================================== */
            $blog_id = $this->input->post('blog_id');
            $updating_data['blog_image'] = $file_name;
            $updating_data['head_line'] = $this->input->post('head_line');
            $updating_data['meta_tag'] = $this->input->post('meta_tag');
            $updating_data['author'] = $this->input->post('author');
            $updating_data['meta_description'] = $this->input->post('meta_description');
            $updating_data['meta_keywords'] = $this->input->post('meta_keywords');
            if ($this->input->post('status') === 'on') {
                $updating_data['status'] = '1';
            } else {
                $updating_data['status'] = '0';
            }
            if ($this->input->post('is_featured') === 'on') {
                $updating_data['is_featured'] = '1';
            } else {
                $updating_data['is_featured'] = '0';
            }
            $updating_data['created_at'] = date("Y-m-d H:i:s");
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $updating_data['description'] = htmlentities($this->input->post('description'));
            /* ---inserting blog tag starts----- */
            $tag_input = $this->input->post('tags');
            $tag_input_array = explode(",", $tag_input);
            $tag_input_array = $this->security->xss_clean($tag_input_array);
            /* ---inserting blog tag ends----- */

            /* ---inserting blog category ends----- */
            $blog_category = $this->input->post('blog_category');
            $blog_category = $this->security->xss_clean($blog_category);
            /* ---inserting blog category ends----- */
            $updated_id = $this->blogManagementMaster_model->update_blog($updating_data, $blog_id, $tag_input_array, $blog_category);
            redirect('blogmaster-list');
        }
    }

    /**
     * Added for checking whether head line is
     * already existed or not
     * @return boolean
     */
    public function check_blog_head_line_exist_edit() {
        $search_array = array();
        $blog_id = $this->input->post('blog_id');
        $search_array['head_line'] = $this->input->post('head_line', true);
        $blog_category = $this->input->post('blog_category');
        $blog_category = explode(',', $blog_category);
        $search_array['blog_category'] = $blog_category;
        $result = $this->blogManagementMaster_model->get_block_by_name('all',$search_array,$blog_id);
        if (count($result) === 0) {
            $response = true;
        } else {
            if ($result[0]['id'] === $blog_id) {
                $response = true;
            } else {
                $response = false;
            }
        }
        return $response;
    }

    /**
     * Added for checking a 
     * head line name exist or not at the time of editing
     */
    public function check_blog_head_line_edit_ajax() {
        if ($this->input->is_ajax_request()) {
            $search_array = array();
            $blog_id = $this->input->post('blog_id');
            $search_array['head_line'] = $this->input->post('head_line', true);
            $blog_category = $this->input->post('blog_category');
            $blog_category = explode(',', $blog_category);
            $search_array['blog_category'] = $blog_category;
            $result = $this->blogManagementMaster_model->get_block_by_name('all',$search_array,$blog_id);
            if (count($result) === 0) {
                $response = "true";
            } else {
                if ($result[0]['id'] === $blog_id) {
                    $response = "true";
                } else {
                    $response = "false";
                }
            }
            echo $response;
        }
    }

}

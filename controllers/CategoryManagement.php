<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CategoryManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('Category_model', 'category_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for category
     * listing for the admin
     */
    public function index() {
        $data['title'] = 'Category';
        $this->load->view('category/index', $data);
    }

    /**
     * Added for fetching ajax result
     * in order to list main categories
     */
    public function ajaxMainCategory() {
        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();

            /* ---Search data array started--- */
            if ($this->input->post('cat_name') !== "") {
                $search_array['cat_name'] = $this->input->post('cat_name');
            }
            if ($this->input->post('tot_vendor_from') !== "") {
                $search_array['tot_vendor_from'] = $this->input->post('tot_vendor_from');
            }
            if ($this->input->post('tot_vendor_to') !== "") {
                $search_array['tot_vendor_to'] = $this->input->post('tot_vendor_to');
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
            if ($this->input->post('is_featured') !== "") {
                $search_array['is_featured'] = $this->input->post('is_featured');
            }
            $search_array = $this->security->xss_clean($search_array); /* ---Added for filtering array cleaned the data --- */
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
            $iTotalRecords = $this->category_model->fetchCategoryInfo('number', 0, $search_array);

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
            } elseif ($orderBy === '1') {
                $orderByData = 'total_vendor';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->category_model->fetchCategoryResultMain($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            /* echo "<pre>";
              print_r($result);
              die(); */
            foreach ($result as $key => $res) {

                /* ---Category basic info html--- */
                if ($res['cat_image'] !== NULL) {
                    $category_info = '<a target="_blank" href="javascript:void(0)" >
                                                <img src="' . ASSET_URL . 'uploads/category-images/final-show/' . $res['cat_image'] . '">
                                            </a><br/>';
                } else {
                    $category_info = '<a target = "_blank" href = "javascript:void(0)" >
                    <img src = "' . ASSET_URL . 'uploads/category-images/final-show/noimage-preview.jpg">
                    </a><br/>';
                }
                $category_info .= "<span>" . $res['cat_name'] . "</span>";
                /* ---Category basic info html--- */

                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }

                if ($res['is_featured'] === '1') {
                    $featured_html = '<img src="' . ASSET_URL . 'global/img/thumbs_up.png">';
                } else {
                    $featured_html = '<img src="' . ASSET_URL . 'global/img/thumbs_down.png">';
                }
                if ($res['cat_icon'] !== NULL) {
                    $category_icon = '<img src="' . ASSET_URL . 'uploads/category-icons/thumb/' . $res['cat_icon'] . '">';
                } else {
                    $category_icon = '<img src = "' . ASSET_URL . 'uploads/category-icons/thumb/noimage-preview.jpg">';
                }

                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-category/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit"><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\')" data-toggle="tooltip" title="delete"><i class="fa fa-trash"></i></button>';
                $button_html .= '<button title="view related details" type="button" class="btn btn-icon-only blue" onclick="javascript:get_related_details(\'' . $encrypted_id . '\')"  ><i class="fa fa-anchor"></i></button>';
                $records["data"][] = array($category_info, $res['vendor_count'], date("Y-m-d", strtotime($res['updated_at'])), $featured_html, $status_html, $category_icon, $button_html);
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
     * Added for adding new
     * category
     */
    public function addCategoryManager() {
        $data['title'] = 'Add Category';
        $config = array(
            array(
                'field' => 'cat_name',
                'label' => 'cat_name',
                'set_value' => 'cat_name',
                'rules' => 'trim|required|callback_check_category_exist_add',
                'errors' => array(
                    'required' => 'Please provide category name',
                    'check_category_exist_add' => 'Oops !!! Category name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('category/addCategoryManager', $data);
        } else {
            $inserting_data['cat_name'] = $this->input->post('cat_name');
            $inserting_data['cat_name_url'] = urlslug($inserting_data['cat_name']);
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
            if ($this->input->post('is_visible') === 'on') {
                $inserting_data['is_visible'] = '1';
            } else {
                $inserting_data['is_visible'] = '0';
            }
            $inserting_data['meta_description'] = $this->input->post("meta_description");
            $inserting_data['meta_title'] = $this->input->post("meta_title");
            $inserting_data['meta_keywords'] = $this->input->post("meta_keywords");
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data['vendor_count'] = 0;
            $inserting_data = $this->security->xss_clean($inserting_data);
            $source_image_path = UPLOADING_PATH . '/category-images/original/';
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config_up['file_name'] = $inserting_data['cat_name_url'];
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('cat_image')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . '/category-images/preview/';
                _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                $final_preview = UPLOADING_PATH . '/category-images/final-show/';
                _resize_avtar($upload_data['file_name'], "66", "78", $final_preview, $source_image_path, FALSE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($final_preview . $upload_data['file_name'], 0777);
                $file_name = $upload_data['file_name'];
            } else {
                $file_name = NULL;
            }
            /* ---Category icon uploading starts --- */
            if ($_FILES['cat_icon']['name'] !== "") {
                $source_image_path = UPLOADING_PATH . "category-icons/original/";
                $preview_target = UPLOADING_PATH . 'category-icons/preview/';
                $final_show = UPLOADING_PATH . 'category-icons/final-show/';
                $thumb_target = UPLOADING_PATH . 'category-icons/thumb/';

                $config_icon['upload_path'] = $source_image_path;
                $config_icon['allowed_types'] = 'gif|jpg|png';
                $config_icon['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
                $config_icon['remove_spaces'] = TRUE;
                $config_icon['file_name'] = $inserting_data['cat_name_url'];
                $config_icon['file_ext_tolower'] = TRUE;
                $config_icon['max_width'] = 3100;
                $config_icon['max_height'] = 1800;
                $this->load->library('upload', $config_icon);
                $this->upload->initialize($config_icon);
                if ($this->upload->do_upload('cat_icon')) {
                    $upload_data = $this->upload->data();
                    _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "21", "16", $final_preview, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "80", "50", $thumb_target, $source_image_path, TRUE);
                    /* -- Everything for owner, read and execute for others -- */
                    chmod($source_image_path . $upload_data['file_name'], 0777);
                    chmod($preview_target . $upload_data['file_name'], 0777);
                    chmod($final_preview . $upload_data['file_name'], 0777);
                    chmod($thumb_target . $upload_data['file_name'], 0777);
                    $inserting_data["cat_icon"] = $upload_data['file_name'];
                } else {
                    $inserting_data["cat_icon"] = NULL;
                }
            }
            /* ---Category icon uploading ends --- */

            $inserting_data['cat_image'] = $file_name;
            $inserted_id = $this->category_model->add_category($inserting_data);
            redirect('category-manager');
        }
    }

    /**
     * checking duplicate
     * category name
     */
    public function checkCategoryNameAdd() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cat_name', true) !== "") {
                $cat_name = $this->input->post('cat_name');
                $result = $this->category_model->get_count_by_name($cat_name);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for counting category
     * exist or not at the time adding
     * @param  string $cat_name // The name of the category
     * @return boolean          // Boolean response of the basis of category name
     */
    public function check_category_exist_add($cat_name) {
        $result = $this->category_model->get_count_by_name($cat_name);
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for editing
     * category
     */
    public function editCategoryManager() {
        $data['title'] = 'Edit Category';
        $encrypted_category_id = $this->uri->segment(2);
        $main_category_id = $this->encrypt_decrypt->decrypt($encrypted_category_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->category_model->fetchCategoryInfo('all', $main_category_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_category_id;
        $config = array(
            array(
                'field' => 'cat_name',
                'label' => 'cat_name',
                'set_value' => 'cat_name',
                'rules' => 'trim|required|callback_check_category_exist_edit',
                'errors' => array(
                    'required' => 'Please provide category name',
                    'check_category_exist_edit' => 'Oops !!! Category name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('category/editCategoryManager', $data);
        } else {
            $updating_data['cat_name'] = $this->input->post('cat_name');
            $updating_data['cat_name_url'] = urlslug($updating_data['cat_name']);
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
            $updating_data['meta_description'] = $this->input->post("meta_description");
            $updating_data['meta_title'] = $this->input->post("meta_title");
            $updating_data['meta_keywords'] = $this->input->post("meta_keywords");
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            /* ---Physically deleting images starts--- */
            if ($_FILES['cat_image']['name'] !== "") {
                if ($result[0]['cat_image'] !== NULL) {
                    unlink(UPLOADING_PATH . "category-images/preview/" . $result[0]['cat_image']);
                    unlink(UPLOADING_PATH . "category-images/original/" . $result[0]['cat_image']);
                    unlink(UPLOADING_PATH . "category-images/final-show/" . $result[0]['cat_image']);
                }
            }
            /* ---Physically deleting images ends--- */
            $source_image_path = UPLOADING_PATH . '/category-images/original/';
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config_up['remove_spaces'] = TRUE;
            $config_up['file_name'] = $updating_data['cat_name_url'];
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('cat_image')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . '/category-images/preview/';
                _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                $final_preview = UPLOADING_PATH . '/category-images/final-show/';
                _resize_avtar($upload_data['file_name'], "66", "78", $final_preview, $source_image_path, FALSE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($final_preview . $upload_data['file_name'], 0777);
                $file_name = $upload_data['file_name'];
                $updating_data['cat_image'] = $file_name;
            }
            /* ---Category icon uploading starts --- */
            if ($_FILES['cat_icon']['name'] !== "") {
                $source_image_path = UPLOADING_PATH . "category-icons/original/";
                $preview_target = UPLOADING_PATH . 'category-icons/preview/';
                $final_show = UPLOADING_PATH . 'category-icons/final-show/';
                $thumb_target = UPLOADING_PATH . 'category-icons/thumb/';

                if ($result[0]['cat_icon'] !== NULL) {
                    unlink($source_image_path. $result[0]['cat_icon']);
                    unlink($preview_target . $result[0]['cat_icon']);
                    unlink($final_show . $result[0]['cat_icon']);
                    unlink($thumb_target . $result[0]['cat_icon']);
                }

                $config_icon['upload_path'] = $source_image_path;
                $config_icon['allowed_types'] = 'gif|jpg|png';
                $config_icon['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
                $config_icon['remove_spaces'] = TRUE;
                $config_icon['file_name'] = $updating_data['cat_name_url'];
                $config_icon['file_ext_tolower'] = TRUE;
                $config_icon['max_width'] = 3100;
                $config_icon['max_height'] = 1800;
                $this->load->library('upload', $config_icon);
                $this->upload->initialize($config_icon);
                if ($this->upload->do_upload('cat_icon')) {
                    $upload_data = $this->upload->data();
                    _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "21", "16", $final_show, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "80", "50", $thumb_target, $source_image_path, TRUE);
                    /* -- Everything for owner, read and execute for others -- */
                    chmod($source_image_path . $upload_data['file_name'], 0777);
                    chmod($preview_target . $upload_data['file_name'], 0777);
                    chmod($final_show . $upload_data['file_name'], 0777);
                    chmod($thumb_target . $upload_data['file_name'], 0777);
                    $updating_data["cat_icon"] = $upload_data['file_name'];
                }
            }
            /* ---Category icon uploading ends --- */
            $updating_data = $this->security->xss_clean($updating_data);
            $this->category_model->update_category($updating_data, $main_category_id);
            redirect('category-manager');
        }
    }

    /**
     * Added for checking category name exist
     * or not at the time edit
     */
    public function checkCategoryNameEdit() {
        if ($this->input->is_ajax_request()) {
            $category_id = $this->input->post('category_id');
            $present_cat_name = $this->input->post('cat_name', true);
            $result = $this->category_model->get_category_details_name($present_cat_name);
            if (count($result) === 0) {
                $response = 'true';
            } else {
                if ($result[0]['id'] === $category_id) {
                    $response = 'true';
                } else {
                    $response = 'false';
                }
            }
            echo $response;
        }
    }

    /**
     * Added for checking
     * category exist
     * @return boolean //Returns category exist or not
     */
    public function check_category_exist_edit() {
        $category_id = $this->input->post('category_id');
        $present_cat_name = $this->input->post('cat_name', true);
        $result = $this->category_model->get_category_details_name($present_cat_name);
        if (count($result) === 0) {
            $response = true;
        } else {
            if ($result[0]['id'] === $category_id) {
                $response = true;
            } else {
                $response = false;
            }
        }
        return $response;
    }

    /**
     * Added for deleting category
     */
    public function deleteCategory() {
        if ($this->input->is_ajax_request()) {
            $category_id = $this->input->post('category_id');
            $main_category_id = $this->encrypt_decrypt->decrypt($category_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->category_model->update_category($update_array, $main_category_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Adding category id 
     * to the cookie
     */
    public function detailsByCategoryName() {
        if ($this->input->is_ajax_request()) {
            $main_category_id = $this->input->post('main_category_id');
            set_cookie('main_category_id', $main_category_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

}

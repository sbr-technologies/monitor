<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PriceChartManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library(array(
            'form_validation',
        ));
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('CategoryPriceChartMaster_model', 'categoryPriceChartMaster_model');
        $this->load->model('Category_model', 'category_model');
        /* -------Model Files-------- */
    }

    /**
     * Method for only viewing  the
     * main price chart list
     */
    public function index() {
        $data['title'] = 'Main Price Chart';
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $this->load->view('price_chart_management/index', $data);
    }

    /**
     * Added for ajax price chart manager
     * in order to list main price chart
     */
    public function ajaxPriceChartManager() {
        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();
            $records = array();
            $records["data"] = array();

            /* ---Search data array started--- */
            if ($this->input->post('menu_name') !== "") {
                $search_array['menu_name'] = $this->input->post('menu_name');
            }
            if ($this->input->post('category_id') !== "") {
                $search_array['category_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('tot_menu_from') !== "") {
                $search_array['tot_menu_from'] = $this->input->post('tot_menu_from');
            }
            if ($this->input->post('tot_menu_to') !== "") {
                $search_array['tot_menu_to'] = $this->input->post('tot_menu_to');
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
            $iTotalRecords = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('number', 0, $search_array);

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
                $orderByData = 'menu_name';
            } else if ($orderBy === '1') {
                $orderByData = 'details_menu_count';
            } else if ($orderBy === '2') {
                $orderByData = 'category_id';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->categoryPriceChartMaster_model->fetchPriceChartResult($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                /* ---Price chart basic info html--- */
                if ($res['picture'] !== NULL) {
                    $price_chart_info = '<a target="_blank" href="javascript:void(0)" >
                                                <img src="' . ASSET_URL . 'uploads/price-chart-manager/thumb/' . $res['picture'] . '">
                                            </a><br/>';
                } else {
                    $price_chart_info = '<a target = "_blank" href = "javascript:void(0)" >
                    <img src = "' . ASSET_URL . 'uploads/price-chart-manager/thumb/noimage-preview.jpg">
                    </a><br/>';
                }
                $price_chart_info .= "<span>" . $res['menu_name'] . "</span>";
                /* ---Price chart basic info html--- */
                
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);

                /* ---Button html info--- */
                $button_html = '<a href="' . base_url('edit-price-chart/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit"><i class="fa fa-pencil"></i></a>';
                $button_html .= '<button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['menu_name'] . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button>';
                $button_html .= '<button title="view related details" type="button" class="btn btn-icon-only blue" onclick="javascript:get_related_details(\'' . $encrypted_id . '\')"  ><i class="fa fa-anchor"></i></button>';
                /* ---Button html info--- */

                $records["data"][] = array($price_chart_info, $res['details_menu_count'], $res['cat_name'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
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
     * Added for adding
     * new price chart
     */
    public function addPriceChart() {
        $data['title'] = 'Add Price Chart';
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'category_id',
                'label' => 'category_id',
                'set_value' => 'category_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a category',
                ),
            ),
            array(
                'field' => 'menu_name',
                'label' => 'menu_name',
                'set_value' => 'menu_name',
                'rules' => 'trim|required|callback_check_menu_exist_add',
                'errors' => array(
                    'required' => 'Please provide menu name',
                    'check_menu_exist_add' => "This menu name is already used for this category",
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('price_chart_management/addPriceChart', $data);
        } else {
            $inserting_data['menu_name'] = $this->input->post('menu_name');
            $inserting_data['category_id'] = $this->input->post('category_id');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $source_image_path = UPLOADING_PATH . '/price-chart-manager/original/';
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config_up['file_name'] = urlslug($inserting_data['menu_name']);
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('picture')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . '/price-chart-manager/preview/';
                _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                $final_preview = UPLOADING_PATH . '/price-chart-manager/final-show/';
                _resize_avtar($upload_data['file_name'], "437", "386", $final_preview, $source_image_path, FALSE);
                $thumb_preview = UPLOADING_PATH . '/price-chart-manager/thumb/';
                _resize_avtar($upload_data['file_name'], "50", "50", $thumb_preview, $source_image_path, TRUE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($final_preview . $upload_data['file_name'], 0777);
                chmod($thumb_preview . $upload_data['file_name'], 0777);
                $file_name = $upload_data['file_name'];
            } else {
                $file_name = NULL;
            }
            $inserting_data['picture'] = $file_name;
            $inserted_id = $this->categoryPriceChartMaster_model->add_price_chart($inserting_data);
            redirect('price-chart-manager');
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * adding for PHP callback
     */
    public function checkMenuName() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cat_id', true) !== "" && $this->input->post('menu_name', true) !== "") {
                if ($this->input->post('cat_id') !== "") {
                    $search_array['cat_id'] = $this->input->post('cat_id');
                }
                if ($this->input->post('menu_name') !== "") {
                    $search_array['menu_name'] = $this->input->post('menu_name');
                }
                $search_array = $this->security->xss_clean($search_array);
                $menu_name = $search_array['menu_name'];
                $cat_id = $search_array['cat_id'];
                $result = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name, $cat_id);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * adding for JAVASCRIPT validation
     */
    public function check_menu_exist_add() {
        $search_array = array();
        if ($this->input->post('category_id') !== "" && $this->input->post('feature_name') !== "") {
            if ($this->input->post('category_id') !== "") {
                $search_array['cat_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('menu_name') !== "") {
                $search_array['menu_name'] = $this->input->post('menu_name');
            }
            $search_array = $this->security->xss_clean($search_array);
            $menu_name = $search_array['menu_name'];
            $cat_id = $search_array['cat_id'];
            $result = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name, $cat_id);
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Added for deleting
     * price chart
     */
    public function deletePriceChart() {
        if ($this->input->is_ajax_request()) {
            $menu_id = $this->input->post('menu_id');
            $main_menu_id = $this->encrypt_decrypt->decrypt($menu_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->categoryPriceChartMaster_model->update_price_chart($update_array, $main_menu_id);
            $records['status'] = "1";
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for deleting
     * menu details
     */
    public function deletePriceChartDetails() {
        if ($this->input->is_ajax_request()) {
            $menu_id = $this->input->post('menu_id');
            $main_menu_id = $this->encrypt_decrypt->decrypt($menu_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->categoryPriceChartMaster_model->update_price_chart_details($update_array, $main_menu_id);
            $records['status'] = "1";
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing price
     * chart
     */
    public function editPriceChart() {
        $data['title'] = 'Edit Price Chart';
        $encrypted_price_chart_id = $this->uri->segment(2);
        $data['encrypted_price_chart_id'] = $encrypted_price_chart_id;
        $price_chart_id = $this->encrypt_decrypt->decrypt($encrypted_price_chart_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('all', $price_chart_id, array());
        $data['result'] = $result[0];
        $data['categories'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'category_id',
                'label' => 'category_id',
                'set_value' => 'category_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a category',
                ),
            ),
            array(
                'field' => 'menu_name',
                'label' => 'menu_name',
                'set_value' => 'menu_name',
                'rules' => 'trim|required|callback_check_menu_exist_edit',
                'errors' => array(
                    'required' => 'Please provide menu name',
                    'check_menu_exist_add' => "This menu name is already used for this category",
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('price_chart_management/editPriceChart', $data);
        } else {
            $updating_data['menu_name'] = $this->input->post('menu_name');
            $updating_data['category_id'] = $this->input->post('category_id');
            if ($this->input->post('status') === 'on') {
                $updating_data['status'] = '1';
            } else {
                $updating_data['status'] = '0';
            }
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            /* ---Physically deleting images starts--- */
            if ($_FILES['picture']['name'] !== "") {
                if ($result[0]['picture'] !== NULL) {
                    unlink(UPLOADING_PATH . "price-chart-manager/preview/" . $result[0]['picture']);
                    unlink(UPLOADING_PATH . "price-chart-manager/original/" . $result[0]['picture']);
                    unlink(UPLOADING_PATH . "price-chart-manager/final-show/" . $result[0]['picture']);
                    unlink(UPLOADING_PATH . "price-chart-manager/thumb/" . $result[0]['picture']);
                }
            }
            /* ---Physically deleting images ends--- */
            $source_image_path = UPLOADING_PATH . '/price-chart-manager/original/';
            $config_up['upload_path'] = $source_image_path;
            $config_up['allowed_types'] = 'jpg|jpeg|png|gif';
            $config_up['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config_up['file_name'] = urlslug($updating_data['menu_name']);
            $config_up['file_ext_tolower'] = TRUE;
            $config_up['max_width'] = 3100;
            $config_up['max_height'] = 1800;
            $this->load->library('upload', $config_up);
            if ($this->upload->do_upload('picture')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . '/price-chart-manager/preview/';
                _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                $final_preview = UPLOADING_PATH . '/price-chart-manager/final-show/';
                _resize_avtar($upload_data['file_name'], "437", "386", $final_preview, $source_image_path, FALSE);
                $thumb_preview = UPLOADING_PATH . '/price-chart-manager/thumb/';
                _resize_avtar($upload_data['file_name'], "50", "50", $thumb_preview, $source_image_path, TRUE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($final_preview . $upload_data['file_name'], 0777);
                chmod($thumb_preview . $upload_data['file_name'], 0777);
                $file_name = $upload_data['file_name'];
                $updating_data['picture'] = $file_name;
            }
          
            $updating_data = $this->security->xss_clean($updating_data);
            $inserted_id = $this->categoryPriceChartMaster_model->update_price_chart($updating_data, $price_chart_id);
            redirect('price-chart-manager');
        }
    }

    /**
     * Added for checking
     * price chart exist or not
     * at the time of editing
     */
    public function check_menu_exist_edit() {
        if ($this->input->is_ajax_request()) {
            $menu_name = $this->input->post('menu_name');
            $result = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name);
            $category_id = $this->input->post('category_id');
            $id = $this->input->post('id');
            if (count($result) == 0) {
                $response = "true";
            } else {
                $check_existence = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name, $category_id);
                if ($result['category_id'] == $category_id) {
                    if (count($check_existence) > 0) {
                        if ($check_existence['id'] == $id) {
                            $response = "true";
                        } else {
                            $response = "false";
                        }
                    } else {
                        $response = "false";
                    }
                } else {
                    if (count($check_existence) > 0) {
                        $response = "false";
                    } else {
                        $response = "true";
                    }
                }
            }
            echo $response;
        } else {
            $menu_name = $this->input->post('menu_name');
            $result = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name);
            $category_id = $this->input->post('category_id');
            $id = $this->input->post('id');
            if (count($result) == 0) {
                $response = true;
            } else {
                $check_existence = $this->categoryPriceChartMaster_model->get_price_chart_name($menu_name, $category_id);
                if ($result['category_id'] == $category_id) {
                    if (count($check_existence) > 0) {
                        if ($check_existence['id'] == $id) {
                            $response = true;
                        } else {
                            $response = false;
                        }
                    } else {
                        $response = false;
                    }
                } else {
                    if (count($check_existence) > 0) {
                        $response = false;
                    } else {
                        $response = true;
                    }
                }
            }
            return $response;
        }
    }

    /**
     * Added for fetching main menu
     * based on each category
     */
    public function fetchOnlyMainMenu() {
        if ($this->input->is_ajax_request()) {
            $search_array["category_id"] = $this->input->post('category_id');
            $result = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('all', 0, $search_array);
            if (count($result) > 0) {
                $html_string = '<option value="">Select...</option>';
                foreach ($result as $key => $value) {
                    $html_string .= '<option value="' . $value['id'] . '">' . $value['menu_name'] . '</option>';
                }
            } else {
                $html_string = '<option value="">No Menu Found</option>';
            }
            echo $html_string;
        }
    }

    /**
     * Added for price chart
     * details listing
     */
    public function priceChartDetails() {
        $data['title'] = 'Price Chart Details';
        $search_array = array();
        /* ---These value  will be only active when there is a cookie starts--- */
        $html_string = "";
        $cookie_category_id = "";
        /* ---These value  will be only active when there is a cookie ends--- */

        /* ----Fetching cookie of main menu starts---- */
        $main_menu_id = trim(get_cookie("main_menu_id"));
        if ($main_menu_id !== "") {
            $main_menu_id = $this->encrypt_decrypt->decrypt($main_menu_id, ENCRYPT_DECRYPT_KEY);
            $price_chart_info = $this->categoryPriceChartMaster_model->fetchPriceChartInfo("all", $main_menu_id, $search_array = array());
            $cookie_category_id = $price_chart_info[0]["category_id"];
            $search_array["category_id"] = $cookie_category_id;
            $result = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('all', 0, $search_array);
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    if ($main_menu_id === $value['id']) {
                        $html_string .= '<option value="' . $value['id'] . '" selected>' . $value['menu_name'] . '</option>';
                    } else {
                        $html_string .= '<option value="' . $value['id'] . ' ">' . $value['menu_name'] . '</option>';
                    }
                }
            } else {
                $html_string = '<option value="">No Menu Found</option>';
            }
        }
        /* ----Fetching cookie of main menu ends---- */

        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $data['cookie_category_id'] = $cookie_category_id;
        $data['main_price_chart'] = $html_string;
        $this->load->view('price_chart_management/priceChartDetails', $data);
    }

    /**
     * Added for generating price chart
     * details by Ajax
     */
    public function ajaxPriceChartDetails() {
        if ($this->input->is_ajax_request()) {
            /* ---Searching array blank initialized--- */
            $search_array = array();
            $records = array();
            $records["data"] = array();
            delete_cookie("main_menu_id");
            /* ---Search data array started--- */
            if ($this->input->post('menu_name') !== "") {
                $search_array['menu_name'] = $this->input->post('menu_name');
            }
            if ($this->input->post('category_id') !== "") {
                $search_array['category_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('main_menu_id') !== "") {
                $search_array['main_menu_id'] = $this->input->post('main_menu_id');
            }
            if ($this->input->post('highest_price_from') !== "") {
                $search_array['highest_price_from'] = $this->input->post('highest_price_from');
            }
            if ($this->input->post('highest_price_to') !== "") {
                $search_array['highest_price_to'] = $this->input->post('highest_price_to');
            }
            if ($this->input->post('lowest_price_from') !== "") {
                $search_array['lowest_price_from'] = $this->input->post('lowest_price_from');
            }
            if ($this->input->post('lowest_price_to') !== "") {
                $search_array['lowest_price_to'] = $this->input->post('lowest_price_to');
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
            $iTotalRecords = $this->categoryPriceChartMaster_model->fetchPriceChartDetailsInfo('number', 0, $search_array);

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
                $orderByData = 'menu_name';
            } else if ($orderBy === '1') {
                $orderByData = 'category_id';
            } else if ($orderBy === '2') {
                $orderByData = 'main_menu_id';
            } else if ($orderBy === '3') {
                $orderByData = 'highest_price';
            } else if ($orderBy === '4') {
                $orderByData = 'lowest_price';
            } else {
                $orderByData = 'updated_at';
            }
            $result = $this->categoryPriceChartMaster_model->fetchPriceChartDetailsResult($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);

            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-price-details/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['menu_name'] . '\')" data-toggle="tooltip" title="delete"><i class="fa fa-trash"></i></button>';

                $records["data"][] = array($res['menu_name'], $res['category_name'], $res['main_menu_name'], $res['highest_price'], $res['lowest_price'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
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
     * Added for adding price
     * chart details
     */
    public function addPriceDetails() {

        $data['title'] = 'Add Price Chart Details';
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'category_id',
                'label' => 'category_id',
                'set_value' => 'category_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select a category',
                ),
            ),
            array(
                'field' => 'main_menu_id',
                'label' => 'main_menu_id',
                'set_value' => 'main_menu_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select a main menu',
                ),
            ),
            array(
                'field' => 'menu_name',
                'label' => 'menu_name',
                'set_value' => 'menu_name',
                'rules' => 'trim|required|callback_check_Menu_Name_Details',
                'errors' => array(
                    'required' => 'Please provide menu name',
                    'check_Menu_Name_Details' => "This menu name is already used for this main menu",
                ),
            ),
            array(
                'field' => 'menu_description',
                'label' => 'menu_description',
                'set_value' => 'menu_description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide menu description',
                ),
            ),
            array(
                'field' => 'lowest_price',
                'label' => 'lowest_price',
                'set_value' => 'lowest_price',
                'rules' => 'trim|callback_check_required|callback_check_price',
                'errors' => array(
                    'check_required' => 'Please provide lowest price',
                    'check_price' => 'Lowest price must be number or decimal.',
                ),
            ),
            array(
                'field' => 'highest_price',
                'label' => 'highest_price',
                'set_value' => 'highest_price',
                'rules' => 'trim|callback_check_required|callback_check_price|callback_check_highest_price',
                'errors' => array(
                    'check_required' => 'Please provide highest price',
                    'check_price' => 'Highest price must be number or decimal.',
                    'check_highest_price' => 'Highest price must be greather than lowest price',
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $search_array['category_id'] = $this->input->post('category_id');
            $data['main_menu_detail'] = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('all', 0, $search_array);
            $this->load->view('price_chart_management/addPriceDetails', $data);
        } else {
            $inserting_data['price_id'] = $this->input->post('main_menu_id');
            $inserting_data['menu_name'] = $this->input->post('menu_name');
            $inserting_data['menu_description'] = $this->input->post('menu_description');
            $inserting_data['lowest_price'] = $this->input->post('lowest_price');
            $inserting_data['highest_price'] = $this->input->post('highest_price');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->categoryPriceChartMaster_model->add_price_chart_details($inserting_data);
            redirect('price-chart-details');
        }
    }

    /**
     * Added for
     * validating input
     * @param  string $val //Lowest price and highest price value check
     * @return boolean     //checking condition true or not
     */
    public function check_required($val) {
        if ($val === "") {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for
     * validating input number or decimal
     * @param string $val // Lowest price and highest price value check
     * @return boolean     //checking condition true or not
     */
    public function check_price($val) {
        if ($val !== "") {
            if (preg_match('/^[0-9]*\.?[0-9]+$/', $val)) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Added for checking
     * menu name exist or not
     * at the time of editing for price chart details
     * for js validation
     */
    public function checkMenuNameDetails() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('main_menu_id', true) !== "" && $this->input->post('menu_name', true) !== "") {
                if ($this->input->post('main_menu_id') !== "") {
                    $search_array['main_menu_id'] = $this->input->post('main_menu_id');
                }
                if ($this->input->post('menu_name') !== "") {
                    $search_array['menu_name'] = $this->input->post('menu_name');
                }
                $search_array = $this->security->xss_clean($search_array);

                $menu_name = $search_array['menu_name'];
                $main_menu_id = $search_array['main_menu_id'];
                $result = $this->categoryPriceChartMaster_model->get_price_chart_name_detail($menu_name, $main_menu_id);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * adding for server side validation
     * for price chart details
     * @return boolean     //checking condition true or not
     */
    public function check_Menu_Name_Details() {
        if ($this->input->post('main_menu_id', true) !== "" && $this->input->post('menu_name', true) !== "") {
            if ($this->input->post('main_menu_id') !== "") {
                $search_array['main_menu_id'] = $this->input->post('main_menu_id');
            }
            if ($this->input->post('menu_name') !== "") {
                $search_array['menu_name'] = $this->input->post('menu_name');
            }
            $search_array = $this->security->xss_clean($search_array);
            $menu_name = $search_array['menu_name'];
            $main_menu_id = $search_array['main_menu_id'];
            $result = $this->categoryPriceChartMaster_model->get_price_chart_name_detail($menu_name, $main_menu_id);
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Added for
     * validating input
     * for price chart details
     * @return boolean     //checking condition true or not
     */
    public function check_highest_price() {
        $max = $this->input->post('highest_price');
        $min = $this->input->post('lowest_price');
        if ($max !== "") {
            if ($max < $min) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Added for
     * editing
     * price chart menu details
     */
    public function editPriceChartDetails() {
        $data['title'] = 'Edit Price Chart Details';
        $search_array = array();
        $encrypted_price_chart_id = $this->uri->segment(2);
        $data['encrypted_price_chart_id'] = $encrypted_price_chart_id;
        $price_chart_id = $this->encrypt_decrypt->decrypt($encrypted_price_chart_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->categoryPriceChartMaster_model->fetchPriceChartDetailsInfo('all', $price_chart_id, array());
        $data['result'] = $result;
        $main_category_id = $data['result']['main_category_id'];
        $category_id = $this->input->post('category_id');
        if ($main_category_id === "") {
            $search_array['category_id'] = $category_id;
        } else {
            $search_array['category_id'] = $main_category_id;
        }
        $search_array['category_id'] = $main_category_id;
        $data['main_menu_detail'] = $this->categoryPriceChartMaster_model->fetchPriceChartInfo('all', 0, $search_array);
        $data['categories'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'category_id',
                'label' => 'category_id',
                'set_value' => 'category_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a category',
                ),
            ),
            array(
                'field' => 'main_menu_id',
                'label' => 'main_menu_id',
                'set_value' => 'main_menu_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a main menu',
                ),
            ),
            array(
                'field' => 'menu_name',
                'label' => 'menu_name',
                'set_value' => 'menu_name',
                'rules' => 'trim|required|callback_check_Menu_Name_edit_Details',
                'errors' => array(
                    'required' => 'Please provide menu name',
                    'check_Menu_Name_edit_Details' => "This menu name is already used for this main menu",
                ),
            ),
            array(
                'field' => 'menu_description',
                'label' => 'menu_description',
                'set_value' => 'menu_description',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide menu description',
                ),
            ),
            array(
                'field' => 'lowest_price',
                'label' => 'lowest_price',
                'set_value' => 'lowest_price',
                'rules' => 'trim|callback_check_required|callback_check_price',
                'errors' => array(
                    'check_required' => 'Please provide lowest price',
                    'check_price' => 'Lowest price must be number or decimal.',
                ),
            ),
            array(
                'field' => 'highest_price',
                'label' => 'highest_price',
                'set_value' => 'highest_price',
                'rules' => 'trim|callback_check_required|callback_check_price|callback_check_highest_price',
                'errors' => array(
                    'check_required' => 'Please provide highest price',
                    'check_price' => 'Highest price must be number or decimal.',
                    'check_highest_price' => 'Highest price must be greather than lowest price',
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('price_chart_management/editPriceChartDetails', $data);
        } else {
            $price_detail_id = $this->input->post('id');
            $inserting_data['price_id'] = $this->input->post('main_menu_id');
            $inserting_data['menu_name'] = $this->input->post('menu_name');
            $inserting_data['menu_description'] = $this->input->post('menu_description');
            $inserting_data['lowest_price'] = $this->input->post('lowest_price');
            $inserting_data['highest_price'] = $this->input->post('highest_price');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->categoryPriceChartMaster_model->update_price_chart_details($inserting_data, $price_detail_id);
            redirect('price-chart-details');
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * editing for server side validation
     * for menu name details
     * @return boolean     //checking condition true or not
     */
    public function check_Menu_Name_edit_Details() {
        if ($this->input->post('main_menu_id', true) !== "" && $this->input->post('menu_name', true) !== "") {
            if ($this->input->post('main_menu_id') !== "") {
                $search_array['main_menu_id'] = $this->input->post('main_menu_id');
            }
            if ($this->input->post('menu_name') !== "") {
                $search_array['menu_name'] = $this->input->post('menu_name');
            }
            if ($this->input->post('id') !== "") {
                $search_array['id'] = $this->input->post('id');
            }
            $search_array = $this->security->xss_clean($search_array);
            $menu_name = $search_array['menu_name'];
            $main_menu_id = $search_array['main_menu_id'];
            $id = $search_array['id'];
            $result = $this->categoryPriceChartMaster_model->get_price_chart_name_edit_detail($menu_name, $main_menu_id, $id);
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * editing for client side validation
     * for menu name details
     */
    public function checkMenuNameEditDetails() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('main_menu_id', true) !== "" && $this->input->post('menu_name', true) !== "") {
                if ($this->input->post('main_menu_id') !== "") {
                    $search_array['main_menu_id'] = $this->input->post('main_menu_id');
                }
                if ($this->input->post('menu_name') !== "") {
                    $search_array['menu_name'] = $this->input->post('menu_name');
                }
                if ($this->input->post('id') !== "") {
                    $search_array['id'] = $this->input->post('id');
                }
                $search_array = $this->security->xss_clean($search_array);
                $menu_name = $search_array['menu_name'];
                $main_menu_id = $search_array['main_menu_id'];
                $id = $search_array['id'];
                $result = $this->categoryPriceChartMaster_model->get_price_chart_name_edit_detail($menu_name, $main_menu_id, $id);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Adding main menu id 
     * to the cookie
     */
    public function detailsByMenuName() {
        if ($this->input->is_ajax_request()) {
            $main_menu_id = $this->input->post('main_menu_id');
            set_cookie('main_menu_id', $main_menu_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

}

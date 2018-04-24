<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessFeatureMaster extends CI_Controller {

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
        $this->load->model('BusinessFeatureMaster_model', 'businessFeatureMaster_model');
        $this->load->model('Category_model', 'category_model');
        /* -------Model Files-------- */
    }

    /**
     * Method for only viewing  the
     * business feature list
     */
    public function index() {
        $data['title'] = 'Business Feature';
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $this->load->view('business_feature/index', $data);
    }

    /**
     * Added for adding
     * new business feature
     */
    public function addBusinessFeatureMaster() {
        $data['title'] = 'Add Business Feature';
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'parent_id',
                'label' => 'parent_id',
                'set_value' => 'parent_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a category',
                ),
            ),
            array(
                'field' => 'feature_name',
                'label' => 'feature_name',
                'set_value' => 'feature_name',
                'rules' => 'trim|required|callback_check_feature_exist_add',
                'errors' => array(
                    'required' => 'Please provide feature name',
                    'check_feature_exist_add' => "This business feature name is already used for this category",
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('business_feature/addBusinessFeatureMaster', $data);
        } else {
            $inserting_data['feature_name'] = $this->input->post('feature_name');
            $inserting_data['category_id'] = $this->input->post('parent_id');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->businessFeatureMaster_model->add_feature($inserting_data);
            redirect('business-feature-manager');
        }
    }

    /**
     * checking duplicate
     * feature name
     * client side
     */
    public function checkFeatureName() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('cat_id', true) !== "" && $this->input->post('feature_name', true) !== "") {
                if ($this->input->post('cat_id') !== "") {
                    $search_array['cat_id'] = $this->input->post('cat_id');
                }
                if ($this->input->post('feature_name') !== "") {
                    $search_array['feature_name'] = $this->input->post('feature_name');
                }
                $search_array = $this->security->xss_clean($search_array);
                $result = $this->businessFeatureMaster_model->get_count_by_feature_name($search_array);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * checking duplicate
     * feature name
     * server side
     */
    public function check_feature_exist_add() {
        $search_array = array();
        if ($this->input->post('parent_id') !== "" && $this->input->post('feature_name')) {
            if ($this->input->post('parent_id') !== "") {
                $search_array['cat_id'] = $this->input->post('parent_id');
            }
            if ($this->input->post('feature_name') !== "") {
                $search_array['feature_name'] = $this->input->post('feature_name');
            }
            $result = $this->businessFeatureMaster_model->get_count_by_feature_name($search_array);
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Added for fetching Ajax result
     * in order to list main business features
     */
    public function ajaxBusinessFeatureMaster() {
        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('feature_name') !== "") {
                $search_array['feature_name'] = $this->input->post('feature_name');
            }
            if ($this->input->post('tot_attribute_from') !== "") {
                $search_array['tot_attribute_from'] = $this->input->post('tot_attribute_from');
            }
            if ($this->input->post('tot_attribute_to') !== "") {
                $search_array['tot_attribute_to'] = $this->input->post('tot_attribute_to');
            }
            if ($this->input->post('cat_name') !== "") {
                $search_array['cat_name'] = $this->input->post('cat_name');
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
            $iTotalRecords = $this->businessFeatureMaster_model->fetchFeatureInfo('number', 0, $search_array);
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
                $orderByData = 'feature_name';
            } elseif ($orderBy === '1') {
                $orderByData = 'tot_attribute_count';
            } elseif ($orderBy === '2') {
                $orderByData = 'cat_name';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->businessFeatureMaster_model->fetchFeatureResultMain($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-business-feature/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a>';
                $button_html .= '<button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button></button>';
                $button_html .= '<button title="view related details" type="button" class="btn btn-icon-only blue" onclick="javascript:get_related_details(\'' . $encrypted_id . '\')"  ><i class="fa fa-anchor"></i></button>';
                $records["data"][] = array($res['feature_name'], $res['tot_attribute_count'], $res['cat_name'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
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
     * Added for deleting
     * business feature
     */
    public function deleteFeature() {
        if ($this->input->is_ajax_request()) {
            $feature_id = $this->input->post('feature_id');
            $main_feature_id = $this->encrypt_decrypt->decrypt($feature_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->businessFeatureMaster_model->update_feature($update_array, $main_feature_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing
     * business feature
     */
    public function editBusinessFeatureMaster() {
        $data['title'] = 'Edit Business Feature';
        $encrypted_business_feature_id = $this->uri->segment(2);
        $data['encrypted_business_feature_id'] = $encrypted_business_feature_id;
        $business_feature_id = $this->encrypt_decrypt->decrypt($encrypted_business_feature_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->businessFeatureMaster_model->fetchFeatureInfo('all', $business_feature_id, array());
        $data['result'] = $result[0];
        $data['categories'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'parent_id',
                'label' => 'parent_id',
                'set_value' => 'parent_id',
                'rules' => 'trim|required|callback_check_feature_exist_edit',
                'errors' => array(
                    'required' => 'Please provide a category',
                    'check_feature_exist_edit' => "This business feature name is already used for this category",
                ),
            ),
            array(
                'field' => 'feature_name',
                'label' => 'feature_name',
                'set_value' => 'feature_name',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide feature name',
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('business_feature/editBusinessFeatureMaster', $data);
        } else {
            $inserting_data['feature_name'] = $this->input->post('feature_name');
            $inserting_data['category_id'] = $this->input->post('parent_id');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->businessFeatureMaster_model->update_feature($inserting_data, $business_feature_id);
            redirect('business-feature-manager');
        }
    }

    /**
     * Added for checking
     * feature exist or not
     * at the time of editing
     */
    public function check_feature_exist_edit() {
        if ($this->input->is_ajax_request()) {
            $feature_name = $this->input->post('feature_name');
            $result = $this->businessFeatureMaster_model->get_feature_details_name($feature_name);
            $category_id = $this->input->post('parent_id');
            $id = $this->input->post('id');
            if (count($result) == 0) {
                $response = "true";
            } else {
                $check_existence = $this->businessFeatureMaster_model->get_feature_details_name($feature_name, $category_id);
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
            $feature_name = $this->input->post('feature_name');
            $result = $this->businessFeatureMaster_model->get_feature_details_name($feature_name);
            $category_id = $this->input->post('parent_id');
            $id = $this->input->post('id');
            if (count($result) == 0) {
                $response = true;
            } else {
                $check_existence = $this->businessFeatureMaster_model->get_feature_details_name($feature_name, $category_id);
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
     * Added for fetching feature
     * of different categories
     */
    public function fetchFeature() {
        if ($this->input->is_ajax_request()) {
            $category_id = $this->input->post('category_id');
            $result = $this->businessFeatureMaster_model->get_all_related_features($category_id);
            if (count($result) > 0) {
                $html_string = '<option value="">Select...</option>';
                foreach ($result as $key => $value) {
                    $html_string .= '<option value="' . $value['id'] . '">' . $value['feature_name'] . '</option>';
                }
            } else {
                $html_string = '<option value="">No Menu Found</option>';
            }
            echo $html_string;
        }
    }

    /**
     * Added for listing listing
     * business feature attributes
     */
    public function businessFeatureAttribute() {
        $data['title'] = 'Business Feature Attribute';
        $search_array = array();
        /* ---These value  will be only active when there is a cookie starts--- */
        $html_string = "";
        $cookie_category_id = "";
        /* ---These value  will be only active when there is a cookie ends--- */

        /* ----Fetching cookie of business feature starts---- */
        $main_feature_id = trim(get_cookie("main_feature_id"));
        if ($main_feature_id !== "") {
            $main_feature_id = $this->encrypt_decrypt->decrypt($main_feature_id, ENCRYPT_DECRYPT_KEY);
            $main_business_info = $this->businessFeatureMaster_model->fetchFeatureInfo("all", $main_feature_id, $search_array = array());
            $cookie_category_id = $main_business_info[0]["category_id"];
            $search_array["cat_name"] = $cookie_category_id;
            $result = $this->businessFeatureMaster_model->fetchFeatureInfo('all', 0, $search_array);

            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    if ($main_feature_id === $value['id']) {
                        $html_string .= '<option value="' . $value['id'] . '" selected>' . $value['feature_name'] . '</option>';
                    } else {
                        $html_string .= '<option value="' . $value['id'] . ' ">' . $value['feature_name'] . '</option>';
                    }
                }
            } else {
                $html_string = '<option value="">No Menu Found</option>';
            }
        }
        /* ----Fetching cookie of business feature ends---- */
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $data['cookie_category_id'] = $cookie_category_id;
        $data['main_feature_info'] = $html_string;
        $this->load->view('business_feature/businessFeatureAttribute', $data);
    }

    /**
     * Added for fetching
     * all the attributes based on business features
     */
    public function ajaxBusinessFeatureAttribute() {
        if ($this->input->is_ajax_request()) {
            /* ---Searching array blank initialized--- */
            $search_array = array();
            delete_cookie('main_feature_id');
            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('feature_id') !== "") {
                $search_array['feature_id'] = $this->input->post('feature_id');
            }
            if ($this->input->post('category_id') !== "") {
                $search_array['category_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('attribute_name') !== "") {
                $search_array['attribute_name'] = $this->input->post('attribute_name');
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
            $iTotalRecords = $this->businessFeatureMaster_model->fetchFeatureAttributeInfo('number', 0, $search_array);

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
                $orderByData = 'attribute_name';
            } elseif ($orderBy === '1') {
                $orderByData = 'vendor_count';
            } elseif ($orderBy === '2') {
                $orderByData = 'cat_name';
            } elseif ($orderBy === '3') {
                $orderByData = 'feature_name';
            }  else {
                $orderByData = 'updated_at';
            }

            $result = $this->businessFeatureMaster_model->fetchFeatureAttributes($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-business-feature-attribute/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['name'] . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button>';

                $records["data"][] = array($res['name'], $res['vendor_count'], $res['category_name'],  $res['feature_name'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
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
     * business feature attribute
     */
    public function addBusinessFeatureAttribute() {
        $data['title'] = 'Add Business Feature Attribute';
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
                'field' => 'main_bfm_id',
                'label' => 'main_bfm_id',
                'set_value' => 'main_bfm_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide a feature id',
                ),
            ),
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_attribute_name',
                'errors' => array(
                    'required' => 'Please provide attribute name',
                    'check_attribute_name' => "This attribute name is already used for this feature",
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $search_array['category_id'] = $this->input->post('category_id');
            $data['feature_id_details'] = $this->businessFeatureMaster_model->get_all_related_features($search_array['category_id']);
            $this->load->view('business_feature/addBusinessFeatureAttribute', $data);
        } else {
            $inserting_data['bfm_id'] = $this->input->post('main_bfm_id');
            $inserting_data['name'] = $this->input->post('name', true);
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->businessFeatureMaster_model->add_feature_attribute($inserting_data);
            redirect('business-feature-attribute');
        }
    }

    /**
     * Added for checking menu name
     * exist or not at the time of
     * adding for server side validation
     * for price chart details
     * @return boolean     //checking condition true or not
     */
    public function check_attribute_name() {
        if ($this->input->post('main_bfm_id', true) !== "" && $this->input->post('name', true) !== "") {
            if ($this->input->post('main_bfm_id') !== "") {
                $search_array['bfm_id'] = $this->input->post('main_bfm_id');
            }
            if ($this->input->post('name') !== "") {
                $search_array['name'] = $this->input->post('name');
            }
            $search_array = $this->security->xss_clean($search_array);
            $name = $search_array['name'];
            $bfm_id = $search_array['bfm_id'];
            $result = $this->businessFeatureMaster_model->get_feature_attribute_detail($name, $bfm_id);
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
     * adding for server side validation
     * for price chart details by ajax
     */
    public function checkFeatureAttribute() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('main_bfm_id', true) !== "" && $this->input->post('name', true) !== "") {
                if ($this->input->post('main_bfm_id') !== "") {
                    $search_array['bfm_id'] = $this->input->post('main_bfm_id');
                }
                if ($this->input->post('name') !== "") {
                    $search_array['name'] = $this->input->post('name');
                }
                $search_array = $this->security->xss_clean($search_array);
                $name = $search_array['name'];
                $bfm_id = $search_array['bfm_id'];
                $result = $this->businessFeatureMaster_model->get_feature_attribute_detail($name, $bfm_id);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for fetching only
     * business features
     */
    public function fetchBusinessFeature() {
        if ($this->input->is_ajax_request()) {
            $category_id = $this->input->post('category_id');
            $result = $this->businessFeatureMaster_model->get_all_related_features($category_id);
            if (count($result) > 0) {
                $html_string = '<option value="">Select...</option>';
                foreach ($result as $key => $value) {
                    $html_string .= '<option value="' . $value['id'] . '">' . $value['feature_name'] . '</option>';
                }
            } else {
                $html_string = '<option value="">No Feature Found</option>';
            }
            echo $html_string;
        }
    }

    /**
     * Added for deleting
     * feature attribute
     */
    public function deleteFeatureAttribute() {
        $attribute_id = $this->input->post('attribute_id');
        $main_attribute_id = $this->encrypt_decrypt->decrypt($attribute_id, ENCRYPT_DECRYPT_KEY);
        $update_array['status'] = "3";
        $this->businessFeatureMaster_model->update_feature_attribute($update_array, $main_attribute_id);
        $records['status'] = 1;
        $this->output
                ->set_content_type('application/json')
                ->set_output(json_encode($records));
    }

    /**
     * Added for editing
     * business feature attributes
     */
    public function editBusinessFeatureAttribute() {
        $data['title'] = 'Edit Business Feature Attribute';
        $search_array = array();
        $encrypted_feature_attribute_id = $this->uri->segment(2);
        $data['encrypted_feature_attribute_id'] = $encrypted_feature_attribute_id;
        $feature_attribute_id = $this->encrypt_decrypt->decrypt($encrypted_feature_attribute_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->businessFeatureMaster_model->fetchBusinessFeatureAttribute('all', $feature_attribute_id, array(),"","");
        $data['result'] = $result[0];
        $main_category_id = $data['result']['main_category_id'];
        $category_id = $this->input->post('category_id');
        if ($main_category_id === "") {
            $search_array['category_id'] = $category_id;
        } else {
            $search_array['category_id'] = $main_category_id;
        }
        $search_array['category_id'] = $main_category_id;
        $data['feature_id_details'] = $this->businessFeatureMaster_model->get_all_related_features($main_category_id);
        $data['categories'] = $this->category_model->fetchCategoryInfo('all');
        $config = array(
            array(
                'field' => 'category_id',
                'label' => 'category_id',
                'set_value' => 'category_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select category',
                ),
            ),
            array(
                'field' => 'main_bfm_id',
                'label' => 'main_bfm_id',
                'set_value' => 'main_bfm_id',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please select feature',
                ),
            ),
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_Attribute_Name_edit_Details',
                'errors' => array(
                    'required' => 'Please provide attribute',
                    'check_Attribute_Name_edit_Details' => "This attribute is already used for this feature",
                ),
            ),
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('business_feature/editBusinessFeatureAttribute', $data);
        } else {
            $feature_attribute_id = $this->input->post('id');
            $inserting_data['bfm_id'] = $this->input->post('main_bfm_id');
            $inserting_data['name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->businessFeatureMaster_model->update_feature_attribute($inserting_data, $feature_attribute_id);
            redirect('business-feature-attribute');
        }
    }

    /**
     * Added for editing details
     * of the feature attributes
     * @return boolean     //checking condition true or not
     */
    public function check_Attribute_Name_edit_Details() {
        if ($this->input->post('feature_id', true) !== "" && $this->input->post('name', true) !== "") {
            if ($this->input->post('feature_id') !== "") {
                $search_array['feature_id'] = $this->input->post('feature_id');
            }
            if ($this->input->post('name') !== "") {
                $search_array['name'] = $this->input->post('name');
            }
            if ($this->input->post('id') !== "") {
                $search_array['id'] = $this->input->post('id');
            }
            $search_array = $this->security->xss_clean($search_array);
            $name = $search_array['name'];
            $feature_id = $search_array['feature_id'];
            $id = $search_array['id'];
            $result = $this->businessFeatureMaster_model->get_feature_attribute_edit_details($name, $feature_id, $id);
            if ($result > 0) {
                return false;
            } else {
                return true;
            }
        }
    }

    /**
     * Added for checking Ajax request for
     * attribute validation at the time
     * of editing
     */
    public function checkAttributeNameeditDetailsAjax() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('feature_id', true) !== "" && $this->input->post('name', true) !== "") {
                if ($this->input->post('feature_id') !== "") {
                    $search_array['feature_id'] = $this->input->post('feature_id');
                }
                if ($this->input->post('name') !== "") {
                    $search_array['name'] = $this->input->post('name');
                }
                if ($this->input->post('id') !== "") {
                    $search_array['id'] = $this->input->post('id');
                }
                $search_array = $this->security->xss_clean($search_array);
                $name = $search_array['name'];
                $feature_id = $search_array['feature_id'];
                $id = $search_array['id'];
                $result = $this->businessFeatureMaster_model->get_feature_attribute_edit_details($name, $feature_id, $id);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Adding business feature id 
     * to the cookie
     */
    public function detailsByFeatureName() {
        if ($this->input->is_ajax_request()) {
            $main_feature_id = $this->input->post('main_feature_id');
            set_cookie('main_feature_id', $main_feature_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

}

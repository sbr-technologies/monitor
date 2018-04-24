<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('BusinessMaster_model', 'businessMaster_model');
        $this->load->model('Category_model', 'category_model');
        $this->load->model('Users_model', 'users_model');
        $this->load->model('ServiceAreaMaster_model', 'serviceAreaMaster_model');
        $this->load->model('AvailabilityMaster_model', 'availabilityMaster_model');
        $this->load->model('AdditionalLanguageMaster_model', 'additionalLanguageMaster_model');
        $this->load->model('BusinessFeatureMaster_model', 'businessFeatureMaster_model');
        $this->load->model('AdditionalPhoneNumber_Model', 'additionalPhoneNumber_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for listing
     * all the business
     */
    public function allBusiness() {
        $cookie_vendor_name = "";
        $cookie_category_id = "";
        $data['title'] = 'Business List';
        $main_category_id = trim(get_cookie("main_category_id"));
        if ($main_category_id !== "") {
            $cookie_category_id = $this->encrypt_decrypt->decrypt($main_category_id, ENCRYPT_DECRYPT_KEY);
        }
        $data['cookie_category_id'] = $cookie_category_id;
        $data['cookie_vendor_name'] = $cookie_vendor_name;
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $search_array['user_type'] = array("1","2");
        $data['vendorList'] = $this->users_model->fetchVendorInfo('', 0, $search_array);
        $this->load->view('business-master/allBusiness', $data);
    }

    /**
     * Added for Ajax business list
     */
    public function ajaxBusinessList() {
        if ($this->input->is_ajax_request()) {
            /* ---Searching array blank initialized--- */
            delete_cookie('main_category_id');
            $search_array = array();
            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('business_name_url') !== "") {
                $search_array['business_name_url'] = $this->input->post('business_name_url');
            }
            if ($this->input->post('user_id') !== "") {
                $search_array['user_id'] = $this->input->post('user_id');
            }
            if ($this->input->post('category_id') !== "") {
                $search_array['category_id'] = $this->input->post('category_id');
            }
            if ($this->input->post('vendor_number') !== "") {
                $search_array['vendor_number'] = $this->input->post('vendor_number');
            }
            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_to') !== "") {
                $search_array['updated_at_to'] = $this->input->post('updated_at_to');
            }
            if ($this->input->post('from_tot_rater') !== "") {
                $search_array['from_tot_rater'] = $this->input->post('from_tot_rater');
            }
            if ($this->input->post('to_tot_rater') !== "") {
                $search_array['to_tot_rater'] = $this->input->post('to_tot_rater');
            }
            if ($this->input->post('from_tot_viewer') !== "") {
                $search_array['from_tot_viewer'] = $this->input->post('from_tot_viewer');
            }
            if ($this->input->post('to_tot_viewer') !== "") {
                $search_array['to_tot_viewer'] = $this->input->post('to_tot_viewer');
            }
            if ($this->input->post('from_avg_rate') !== "") {
                $search_array['from_avg_rate'] = $this->input->post('from_avg_rate');
            }
            if ($this->input->post('to_avg_rate') !== "") {
                $search_array['to_avg_rate'] = $this->input->post('to_avg_rate');
            }
            if ($this->input->post('vendor_street') !== "") {
                $search_array['vendor_street'] = $this->input->post('vendor_street');
            }
            if ($this->input->post('vendor_city') !== "") {
                $search_array['vendor_city'] = $this->input->post('vendor_city');
            }
            if ($this->input->post('vendor_location') !== "") {
                $search_array['vendor_location'] = $this->input->post('vendor_location');
            }
            if ($this->input->post('vendor_email') !== "") {
                $search_array['vendor_email'] = $this->input->post('vendor_email');
            }
            if ($this->input->post('status') !== "") {
                $search_array['status'] = $this->input->post('status');
            }
            $search_array = $this->security->xss_clean($search_array); /* ---Added for filtering array,furst cleaned the data and then array_filter is cleaning the blank keys of the array means the blank inputs or only the white space inputs got flushed--- */
            /* ---Search data array started--- */
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
            $iTotalRecords = $this->businessMaster_model->fetchBusinessInfo($search_array);

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
                $orderByData = 'business_name';
            } elseif ($orderBy === '1') {
                $orderByData = 'vendor_name';
            } elseif ($orderBy === '2') {
                $orderByData = 'category_id';
            } elseif ($orderBy === '3') {
                $orderByData = 'phone_number';
            } elseif ($orderBy === '4') {
                $orderByData = 'email';
            } elseif ($orderBy === '5') {
                $orderByData = 'updated_at';
            } elseif ($orderBy === '6') {
                $orderByData = 'total_rater';
            } elseif ($orderBy === '7') {
                $orderByData = 'avg_rate';
            } else {
                $orderByData = 'total_viewer';
            }

            $result = $this->businessMaster_model->fetchBusinessResultMain($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                $category_table = "";
                $additonal_phone_table = "";
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                /* ---Business basic info html--- */
                if ($res['profile_image'] != "" || $res['profile_image'] != NULL) {
                    $business_basic_info = '<a target="_blank" href="javascript:void(0)" >
                                                <img src="' . ASSET_URL . 'uploads/vendor-images/thumb/' . $res['profile_image'] . '">
                                            </a><br/>';
                    $image_url = ASSET_URL . 'uploads/vendor-images/preview/' . $res['profile_image'];
                } else {
                    $business_basic_info = '<a target = "_blank" href = "javascript:void(0)" >
                    <img src = "' . ASSET_URL . 'uploads/vendor-images/thumb/noimage.jpg">
                    </a><br/>';
                    $image_url = ASSET_URL . 'uploads/vendor-images/preview/noimage.jpg';
                }
                $business_basic_info .= "<span id='vendor-name-" . $encrypted_id . "'>" . $res['vendor_name'] . "</span><br/>" . $res['vendor_site_url'] . "<br/>";
                /* ==============starts new code for feature changed================= */
                if ($res['is_featured'] == '1') {
                    $text = '<span class="sbold">Featured</span>';
                    $button_Class = " btn btn-sm green-meadow";
                    $status_msg = 1;
                } else {
                    $text = '<span class="sbold">Simple Business</span>';
                    $button_Class = " btn btn-sm purple-plum ";
                    $status_msg = 0;
                }


                $business_basic_info .= '<button type="button" class="' . $button_Class . '" id="feature-change-button-' . $encrypted_id . '" onclick="javascript:feature_status_change(\'' . $encrypted_id . '\',\'' . $image_url . '\',\'' . $status_msg . '\')" >' . $text . '</i></button>';
                /* ==============ends new code for feature changed================= */

                /* ---Business basic info html--- */

                /* ---Address html info struckture--- */
                $address_info = $res['vendor_street'] . ", " . $res['vendor_city'] . ", " . $res['vendor_location'];
                /* ---Address html info struckture--- */

                /* ---Html buttons for the datatable--- */
                $button_html = '<a href = "' . base_url('edit-business/' . $encrypted_id) . '" class = "btn btn-icon-only green" data-toggle = "tooltip" title = "edit" ><i class = "fa fa-pencil"></i></a>';
                $button_html .= '<button type = "button" class = "btn btn-icon-only red" id = "delete-button-' . $encrypted_id . '" onclick = "javascript:delete_this(\'' . $encrypted_id . '\',\'' . $image_url . '\')" data-toggle = "tooltip" title = "delete" ><i class = "fa fa-trash"></i></button>';
                $button_html .= '<a href = "' . base_url('business-information/' . $encrypted_id) . '" class = "btn btn-icon-only yellow" data-toggle = "tooltip" title = "Information regarding business" ><i class = "fa fa-pencil"></i></a>';
                /* ---Html buttons for the datatable--- */
                $category_result=$this->category_model->associated_business_categories($res['id']);
                $category_table .="<ul>";
                foreach ($category_result as $i=>$cr) {
                    $category_table .="<li>".$cr['cat_name']."</li>";
                }
                $category_table .="</ul>"; 
                $additional_phone_info = $this->additionalPhoneNumber_model->fetchAdditionalPhoneNumber($res['id']);
                if (is_array($additional_phone_info)) {
                    if (count($additional_phone_info)>0) {
                        $additonal_phone_table .= '<br><table><tr style=" border: 1px solid #000000;"><td>Additional Phone Number</td>,</tr>';
                        foreach ($additional_phone_info as $apn) {
                            $additonal_phone_table .="<tr style=' border: 1px solid #000000;'>";
                            $additonal_phone_table .="<td>".$apn["additional_phone_number"]."</td>";
                            $additonal_phone_table .="</tr>";
                        }
                        $additonal_phone_table .= '</table>';
                    } else {
                        $additonal_phone_table = "";
                    }
                } else {
                    $additonal_phone_table = "";
                }

                $records["data"][] = array($business_basic_info, $res['owner_name'], $category_table, $res['vendor_number'].$additonal_phone_table, $res['vendor_email'], date("Y-m-d", strtotime($res['updated_at'])), $res["total_rater"], $res["avg_rate"], $res['tot_viewer'], $address_info, $status_html, $button_html);
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
     * Added for inserting new business 
     * in the database
     */
    public function addBusiness() {
        $data['title'] = 'Add Business';
        $data['vendorList'] = $this->users_model->fetchVendorInfo('', 0, array());
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $this->load->view('business-master/addBusiness', $data);
    }

    /**
     * Added for adding
     * new business basic
     * information through
     * Ajax
     */
    public function addBusinessBasicAjax() {
        if ($this->input->is_ajax_request()) {
            $identification = $this->input->post('identification', true); /* --Added for checking adding mode or editing mode-- */
            $category_id = $this->input->post('category_id', true);
            $sqlData['user_id'] = $this->input->post('user_id');
            $sqlData['vendor_name'] = $this->input->post('vendor_name');
            $sqlData['vendor_number'] = $this->input->post('vendor_number');
            $sqlData['tax_id'] = $this->input->post('tax_id');
            $sqlData['vendor_email'] = $this->input->post('vendor_email');
            $sqlData['vendor_description'] = $this->input->post('vendor_description');
            $sqlData['vendor_street'] = $this->input->post('vendor_street');
            $sqlData['vendor_city'] = $this->input->post('vendor_city');
            $sqlData['vendor_location'] = $this->input->post('vendor_location');
            if ($this->input->post('status') === 'on') {
                $sqlData['status'] = '1';
            } else {
                $sqlData['status'] = '0';
            }
            if ($identification == "0") {
                /* ----Adding information starts---- */
                $sqlData['created_at'] = date("Y-m-d H:i:s");
                $sqlData['updated_at'] = date("Y-m-d H:i:s");
                $sqlData['avg_rate'] = 0;
                $sqlData['total_rate'] = 0;
                $sqlData['avg_rater'] = 0;
                $sqlData['total_rater'] = 0;
                /* --Check for slug exist or not at the time of adding-- */
                $vendor_slug = urlslug($sqlData['vendor_name']);
                $attribute['vendor_slug'] = $vendor_slug;
                $attribute_type["vendor_slug"] = TRUE;
                $result = $this->businessMaster_model->get_business_info($attribute, $attribute_type, 0);
                /* --Check for slug exist or not at the time of editing-- */
                if (count($result) > 0) {
                    $sqlData['vendor_slug'] = $vendor_slug . "-" . rand();
                } else {
                    $sqlData['vendor_slug'] = $vendor_slug;
                }
                $sqlData = $this->security->xss_clean($sqlData);
                $business_id = $this->businessMaster_model->add_business($sqlData, $category_id);
                $encrypted_business_id = $this->encrypt_decrypt->encrypt($business_id, ENCRYPT_DECRYPT_KEY);
                /* ----Adding information ends---- */
            } else {
                /* ----Edition information starts---- */
                $sqlData['updated_at'] = date("Y-m-d H:i:s");
                $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
                /* --Check for slug exist or not at the time of adding-- */
                $vendor_slug = urlslug($sqlData['vendor_name']);
                $attribute['vendor_slug'] = $vendor_slug;
                $attribute_type["vendor_slug"] = TRUE;
                $attribute['category_id'] = $category_id;
                $attribute_type["category_id"] = TRUE;
                $attribute['user_id'] = $sqlData['user_id'];
                $attribute_type["user_id"] = TRUE;
                $slugQueryresult = $this->businessMaster_model->get_business_info($attribute, $attribute_type, 0);

                /* --Check for slug exist or not at the time of editing-- */
                if (count($slugQueryresult) > 0) {
                    if ($slugQueryresult["id"] != $business_id) {
                        $sqlData['vendor_slug'] = $vendor_slug . "-" . rand();
                    }
                } else {
                    $sqlData['vendor_slug'] = $vendor_slug;
                }
                $sqlData = $this->security->xss_clean($sqlData);
                $result = $this->businessMaster_model->update_business($sqlData, $category_id, $business_id);
                $encrypted_business_id = $this->encrypt_decrypt->encrypt($business_id, ENCRYPT_DECRYPT_KEY);
                /* ----Edition information ends---- */
            }

            if (is_numeric($business_id)) {
                $records['status'] = 1;
                $records['encrypted_code'] = $encrypted_business_id;
            } else {
                $records['status'] = 0;
                $records['encrypted_code'] = 0;
            }
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for checking
     * whether a business exist or not
     * at the time of adding
     */
    public function checkBusinessExist() {
        if ($this->input->is_ajax_request()) {
            /* --attribute entry starts-- */
            $attribute["category_id"] = $this->input->post('category_id', true);
            $attribute['user_id'] = $this->input->post('user_id');
            $attribute['vendor_name'] = $this->input->post('vendor_name');
            $identification = $this->input->post('identification');
            /* --attribute entry ends-- */
            /* --attribute type entry starts-- */
            $attribute_type["category_id"] = TRUE;
            $attribute_type['user_id'] = TRUE;
            $attribute_type['vendor_name'] = TRUE;
            /* --attribute type entry ends-- */
            $result = $this->businessMaster_model->get_business_info($attribute, $attribute_type, 0);
            if (count($result) > 0) {
                if ($identification === 0) {
                    echo "false";
                } else {
                    $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
                    if ($business_id == $result['id']) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            } else {
                echo "true";
            }
        }
    }

    /**
     * Added for checking 
     * email exist at the time of adding
     * for the business
     */
    public function checkEmailExist() {
        if ($this->input->is_ajax_request()) {
            /* --attribute entry starts-- */
            $attribute['vendor_email'] = $this->input->post('vendor_email');
            $identification = $this->input->post('identification');
            /* --attribute entry ends-- */
            /* --attribute type entry starts-- */
            $attribute_type["vendor_email"] = TRUE;
            /* --attribute type entry ends-- */
            $result = $this->businessMaster_model->get_business_info($attribute, $attribute_type, 0);
            if (count($result) > 0) {
                if ($identification === 0) {
                    echo "false";
                } else {
                    $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
                    if ($business_id == $result['id']) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            } else {
                echo "true";
            }
        }
    }

    /**
     * Added for checking 
     * number exist at the time of adding
     * for the business
     */
    public function checkNumberExist() {
        if ($this->input->is_ajax_request()) {
            /* --attribute entry starts-- */
            $attribute['vendor_number'] = $this->input->post('vendor_number');
            $identification = $this->input->post('identification');
            /* --attribute entry ends-- */
            /* --attribute type entry starts-- */
            $attribute_type["vendor_number"] = TRUE;
            /* --attribute type entry ends-- */
            $result = $this->businessMaster_model->get_business_info($attribute, $attribute_type, 0);
            if (count($result) > 0) {
                if ($identification === 0) {
                    echo "false";
                } else {
                    $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
                    if ($business_id == $result['id']) {
                        echo "true";
                    } else {
                        echo "false";
                    }
                }
            } else {
                echo "true";
            }
        }
    }

    /**
     * Added for inserting additional information
     * through Ajax
     */
    public function addAdditionalInformationBusiness() {
        if ($this->input->is_ajax_request()) {
            $identification = $this->input->post('additional_information_id');
            /* ---- Inserting data ---- */
            $vendor_facebook_page = $this->input->post("vendor_facebook_page");
            $vendor_site_url = $this->input->post("vendor_site_url");
            $meta_tag = $this->input->post("meta_tag");
            $meta_description = $this->input->post("meta_description");
            $meta_keywords = $this->input->post("meta_keywords");
            /* ---- Inserting data ---- */
            $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
            $business_info = $this->businessMaster_model->get_business_info(array(), array(), $business_id); //Business details information
            /* ---Physically deleting images starts--- */
            if ($business_info["profile_image"] !== NULL) {
                if ($_FILES['profile_image']['name'] !== "") {
                    unlink(UPLOADING_PATH . "vendor-images/preview/" . $business_info["profile_image"]);
                    unlink(UPLOADING_PATH . "vendor-images/original/" . $business_info["profile_image"]);
                    unlink(UPLOADING_PATH . "vendor-images/thumb/" . $business_info["profile_image"]);
                    unlink(UPLOADING_PATH . "vendor-images/admin-preview/" . $business_info["profile_image"]);
                    unlink(UPLOADING_PATH . "vendor-images/front-end/" . $business_info["profile_image"]);
                }
            }
            /* ---Physically deleting images ends--- */
            /* ---Image uploading for business starts--- */
            $source_image_path = UPLOADING_PATH . "/vendor-images/original/";
            $file_name = $business_info['vendor_slug'];
            $config['upload_path'] = $source_image_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 1500; /* ---The size in in KB(here it is 1.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = $file_name;
            $config['file_ext_tolower'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_image')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . "/vendor-images/preview/";
                _resize_avtar($upload_data['file_name'], "120", "148", $preview_target, $source_image_path, FALSE);
                $thumb_target = UPLOADING_PATH . "/vendor-images/thumb/";
                _resize_avtar($upload_data['file_name'], "50", "50", $thumb_target, $source_image_path, TRUE);
                $admin_preview_target = UPLOADING_PATH . "/vendor-images/admin-preview/";
                _resize_avtar($upload_data['file_name'], "150", "200", $admin_preview_target, $source_image_path, FALSE);
                 $front_end_target = UPLOADING_PATH . "/vendor-images/front-end/";
                _resize_avtar($upload_data['file_name'], "201", "369", $front_end_target, $source_image_path, FALSE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($thumb_target . $upload_data['file_name'], 0777);
                chmod($admin_preview_target . $upload_data['file_name'], 0777);
                chmod($front_end_target . $upload_data['file_name'], 0777);
                $profile_image_name = $upload_data['file_name'];
                $image_uploading_status = 1;
                $result['image_url'] = ASSET_URL . "uploads/vendor-images/admin-preview/" . $upload_data['file_name'];
            } else {
                $image_uploading_status = 0;
                $result['image_url'] = ASSET_URL . "uploads/vendor-images/admin-preview/No-Image-Placeholder.jpg";
                $result['err_string'] = strip_tags($this->upload->display_errors());
            }
            /* ---Image uploading for business ends----- */

            /* ----Preparing data for updating transaction starts---- */
            if ($image_uploading_status === 1) {
                $updating_data["vendor_facebook_page"] = $vendor_facebook_page;
                $updating_data["vendor_site_url"] = $vendor_site_url;
                $updating_data["meta_tag"] = $meta_tag;
                $updating_data["meta_description"] = $meta_description;
                $updating_data["meta_keywords"] = $meta_keywords;
                $updating_data["profile_image"] = $profile_image_name;
                $updating_data["updated_at"] = date("Y-m-d H:i:s");
            } else {
                $updating_data["vendor_facebook_page"] = $vendor_facebook_page;
                $updating_data["vendor_site_url"] = $vendor_site_url;
                $updating_data["meta_tag"] = $meta_tag;
                $updating_data["meta_description"] = $meta_description;
                $updating_data["meta_keywords"] = $meta_keywords;
                $updating_data["updated_at"] = date("Y-m-d H:i:s");
            }
            $updating_data = $this->security->xss_clean($updating_data);
            $this->businessMaster_model->update_business($updating_data, "", $business_id);
            /* ----Preparing data for updating transaction ends---- */

            $result['status'] = 1;
            $result['encrypted_code'] = $this->encrypt_decrypt->encrypt($business_id, ENCRYPT_DECRYPT_KEY);
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Added for removing the 
     * profile image of the 
     * business
     */
    public function removeBusinessProfileImage() {
        if ($this->input->is_ajax_request()) {
            $identification = $this->input->post('identification');
            $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
            $updating_data["profile_image"] = NULL;
            $business_info = $this->businessMaster_model->get_business_info(array(), array(), $business_id); //Business details information
            $this->businessMaster_model->update_business($updating_data, "", $business_id);
            /* ---Physically deleting images starts--- */
            unlink(UPLOADING_PATH . "vendor-images/preview/" . $business_info["profile_image"]);
            unlink(UPLOADING_PATH . "vendor-images/original/" . $business_info["profile_image"]);
            unlink(UPLOADING_PATH . "vendor-images/thumb/" . $business_info["profile_image"]);
            unlink(UPLOADING_PATH . "vendor-images/admin-preview/" . $business_info["profile_image"]);
            /* ---Physically deleting images ends--- */
            $result['status'] = 1;
            $result['image_url'] = ASSET_URL . "uploads/vendor-images/admin-preview/No-Image-Placeholder.jpg";
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Added for creating gallery
     * through Ajax,This is showing the 
     * data of the table for the banner
     * images
     */
    public function getGalleryBusiness() {
        if ($this->input->is_ajax_request()) {
            $identification = $this->input->post('business_id');
            $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
            $result["banner_images"] = $this->businessMaster_model->get_all_banner_business(0, $business_id);
            $data = $this->load->view('business-master/getGalleryBusiness', $result, TRUE);
            echo $data;
        }
    }

    /**
     * Added for inserting banner information
     * for business through Ajax
     */
    public function addBannerBusiness() {
        if ($this->input->is_ajax_request()) {
            $identification = $this->input->post('banner_business_id');
            $business_id = $this->encrypt_decrypt->decrypt($identification, ENCRYPT_DECRYPT_KEY); //Decrypting the passed business id through ajax
            /* ---- Inserting data ---- */
            $image_caption = $this->input->post("image_caption");
            /* ---- Inserting data ---- */

            /* ---Image uploading for business starts--- */
            $business_info = $this->businessMaster_model->get_business_info(array(), array(), $business_id); //Business details information
            $source_image_path = UPLOADING_PATH . "/business-banner-images/original/";
            $file_name = $business_info['vendor_slug'] . "-" . time();
            $config['upload_path'] = $source_image_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 1500; /* ---The size in in KB(here it is 1.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = $file_name;
            $config['file_ext_tolower'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('banner_image')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . "/business-banner-images/preview/";
                _resize_avtar($upload_data['file_name'], "400", "800", $preview_target, $source_image_path, FALSE);
                $thumb_target = UPLOADING_PATH . "/business-banner-images/thumb/";
                _resize_avtar($upload_data['file_name'], "100", "200", $thumb_target, $source_image_path, FALSE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($thumb_target . $upload_data['file_name'], 0777);
                /* --Inserting data to the database starts-- */
                $inserting_data["business_id"] = $business_id;
                $inserting_data["image_name"] = $upload_data['file_name'];
                $inserting_data["image_caption"] = $image_caption;
                $inserting_data["created_at"] = date("Y-m-d H:i:s");
                $inserting_data["updated_at"] = date("Y-m-d H:i:s");
                $inserting_data = $this->security->xss_clean($inserting_data);
                $inserted_id = $this->businessMaster_model->add_business_image($inserting_data);
                /* --Inserting data to the database ends-- */
                if ($inserted_id > 0) {
                    $result['status'] = 1;
                    $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($result));
                } else {
                    $result['status'] = 0;
                    $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($result));
                }
            } else {
                $result['status'] = 0;
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($result));
            }
            /* ---Image uploading for business ends----- */
        }
    }

    /**
     * Added for deleting image
     * banner of the business
     */
    public function deleteBannerBusiness() {
        if ($this->input->is_ajax_request()) {
            $banner_id = $this->input->post('banner_id');
            $image_info = $this->businessMaster_model->get_all_banner_business($banner_id, 0);
            /* ---Physically deleting images starts--- */
            unlink(UPLOADING_PATH . "business-banner-images/preview/" . $image_info[0]["image_name"]);
            unlink(UPLOADING_PATH . "business-banner-images/original/" . $image_info[0]["image_name"]);
            unlink(UPLOADING_PATH . "business-banner-images/thumb/" . $image_info[0]["image_name"]);
            /* ---Physically deleting images ends--- */
            $this->businessMaster_model->delete_business_image($banner_id);
            $result['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Added for previewing banner
     * for the gallery
     */
    public function previewBanner() {
        if ($this->input->is_ajax_request()) {
            $banner_id = $this->input->post('banner_id');
            $image_info = $this->businessMaster_model->get_all_banner_business($banner_id, 0);
            $result['status'] = 1;
            $result['image_url'] = ASSET_URL . "uploads/business-banner-images/preview/" . $image_info[0]["image_name"];
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Added for editing banner 
     * images for the business
     * by the modal
     */
    public function editBannerBusiness() {
        if ($this->input->is_ajax_request()) {
            $banner_id = $this->input->post('banner_id');
            $image_info = $this->businessMaster_model->get_all_banner_business($banner_id, 0);
            if (isset($image_info[0]["image_name"])) {
                $result['status'] = 1;
                $result['image_caption'] = $image_info[0]["image_caption"];
                $result['banner_id'] = $banner_id;
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Added for updating
     * banner image through Ajax
     */
    public function editBannerBusinessEntry() {
        if ($this->input->is_ajax_request()) {
            /* ---- Inserting data ---- */
            $image_caption = $this->input->post("edit_image_caption");
            /* ---- Inserting data ---- */
            $banner_id = $this->input->post('edit_banner_business_id');
            if (empty($_FILES['edit_banner_image']['name']) === FALSE) {
                $image_info = $this->businessMaster_model->get_all_banner_business($banner_id, 0);
                $current_image_name = $image_info[0]["image_name"];
                $exploded_image_name = explode('-', $current_image_name);
                array_pop($exploded_image_name);
                $new_image_name = implode("-", $exploded_image_name) . "-" . time();
                /* ---Physically deleting old banner images starts--- */
                unlink(UPLOADING_PATH . "business-banner-images/preview/" . $image_info[0]["image_name"]);
                unlink(UPLOADING_PATH . "business-banner-images/original/" . $image_info[0]["image_name"]);
                unlink(UPLOADING_PATH . "business-banner-images/thumb/" . $image_info[0]["image_name"]);
                /* ---Physically deleting old banner images starts--- */
                $source_image_path = UPLOADING_PATH . "/business-banner-images/original/";
                $file_name = $new_image_name;
                $config['upload_path'] = $source_image_path;
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size'] = 1500; /* ---The size in in KB(here it is 1.5 MB)--- */
                $config['remove_spaces'] = TRUE;
                $config['file_name'] = $file_name;
                $config['file_ext_tolower'] = TRUE;
                $this->load->library('upload', $config);
                if ($this->upload->do_upload('edit_banner_image')) {
                    $upload_data = $this->upload->data();
                    $preview_target = UPLOADING_PATH . "/business-banner-images/preview/";
                    _resize_avtar($upload_data['file_name'], "400", "800", $preview_target, $source_image_path, FALSE);
                    $thumb_target = UPLOADING_PATH . "/business-banner-images/thumb/";
                    _resize_avtar($upload_data['file_name'], "100", "200", $thumb_target, $source_image_path, FALSE);
                    /* -- Everything for owner, read and execute for others -- */
                    chmod($source_image_path . $upload_data['file_name'], 0777);
                    chmod($preview_target . $upload_data['file_name'], 0777);
                    chmod($thumb_target . $upload_data['file_name'], 0777);
                    /* --Inserting data to the database starts-- */
                    $updating_array["image_name"] = $upload_data['file_name'];
                    $updating_array["image_caption"] = $image_caption;
                    $updating_array["updated_at"] = date("Y-m-d H:i:s");
                    $updating_array = $this->security->xss_clean($updating_array);
                    $this->businessMaster_model->update_business_image($updating_array, $banner_id);
                    /* --Inserting data to the database ends-- */
                    $result['status'] = 1;
                    $result['message'] = "Updated banner caption with banner image";
                    $this->output
                            ->set_content_type('application/json')
                            ->set_output(json_encode($result));
                }
            } else {
                $updating_array["image_caption"] = $image_caption;
                $updating_array["updated_at"] = date("Y-m-d H:i:s");
                $updating_array = $this->security->xss_clean($updating_array);
                $this->businessMaster_model->update_business_image($updating_array, $banner_id);
                $result['status'] = 1;
                $result['message'] = "Updated banner caption";
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Added for deleting business
     */
    public function deleteBusiness() {
        if ($this->input->is_ajax_request()) {
            $business_id = $this->input->post('identification');
            $main_business_id = $this->encrypt_decrypt->decrypt($business_id, ENCRYPT_DECRYPT_KEY);
            $records['status'] = $this->businessMaster_model->delete_business($main_business_id);
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing business
     */
    public function editBusiness() {
        $data['title'] = 'Edit Business';
        $encrypted_business_id = $this->uri->segment(2);
        $main_business_id = $this->encrypt_decrypt->decrypt($encrypted_business_id, ENCRYPT_DECRYPT_KEY);
        $data['result'] = $this->businessMaster_model->get_business_info("", "", $main_business_id);
        $data['vendorList'] = $this->users_model->fetchVendorInfo('', 0, array());
        $data['category'] = $this->category_model->fetchCategoryInfo('all');
        $data['encrypted_code'] = $this->encrypt_decrypt->encrypt($main_business_id, ENCRYPT_DECRYPT_KEY);
        $data["banner_images"] = $this->businessMaster_model->get_all_banner_business(0, $main_business_id);
        $this->load->view('business-master/editBusiness', $data);
    }

    /**
     * Added for fetching
     * business information
     */
    public function businessInformation() {
        $data['title'] = 'Edit Business Information';
        $encrypted_business_id = $this->uri->segment(2);
        $main_business_id = $this->encrypt_decrypt->decrypt($encrypted_business_id, ENCRYPT_DECRYPT_KEY);
        $data["availability_info"] = $availability_info_array = $this->availabilityMaster_model->fetchAvailabilityInfo('all', 0, array());
        $data["availability_info_business"] = $this->availabilityMaster_model->availability_info_business($main_business_id);
        $data["service_area_info"] = $service_area_info_array = $this->serviceAreaMaster_model->fetchServiceAreaInfo('all', 0, array());
        $data["service_area_business"] = $this->serviceAreaMaster_model->service_area_info_business($main_business_id);
        $data["additional_language_info"] = $additional_language_info_array = $this->additionalLanguageMaster_model->fetchAdditionalLanguageInfo('all', 0, array());
        $data["additional_language_business"] = $this->additionalLanguageMaster_model->additional_language_info_business($main_business_id);
        $data['encrypted_id'] = $this->encrypt_decrypt->encrypt($main_business_id, ENCRYPT_DECRYPT_KEY);
        $dynamic_feature_info = $this->businessFeatureMaster_model->dynamic_feature_info_business($main_business_id);
        if (is_array($dynamic_feature_info)) {
            if (count($dynamic_feature_info) > 0) {
                foreach ($dynamic_feature_info as $key => $value) {
                    $data['dynamic_feature_info'][] = $value['feature_attribute_id'];
                }
            } else {
                $data['dynamic_feature_info'] = array();
            }
        } else {
            $data['dynamic_feature_info'] = array();
        }
        $data["business_info"] = $this->businessMaster_model->get_business_info(array(), array(), $main_business_id); //Business details information
        $search_array['category_id'] = $data["business_info"]["category_id"];
        $business_feature_info = $this->businessFeatureMaster_model->fetchBusinessFeatureAttribute('all', 0, $search_array, "business_feature_master_id", "ASC");
        if (count($business_feature_info) > 0) {
            $stored_array = array();
            foreach ($business_feature_info as $key => $val) {
                if (in_array($val["bfm_id"], $stored_array)) {
                    $feature_attribute_info["feature_attribute_name"] = $val['name'];
                    $feature_attribute_info["feature_attribute_id"] = $val['id'];
                    $feature_content_postion = count($result_array_feature[$postion]["feature_content"]) + 1;
                    $result_array_feature[$postion]["feature_content"][$feature_content_postion] = $feature_attribute_info;
                } else {
                    $feature_master["feature_master_id"] = $val['bfm_id'];
                    $feature_master["feature_name"] = $val['feature_name'];
                    $feature_attribute_info["feature_attribute_name"] = $val['name'];
                    $feature_attribute_info["feature_attribute_id"] = $val['id'];
                    $feature_master["feature_content"][0] = $feature_attribute_info;
                    if ($key === 0) {
                        $postion = 0;
                    } else {
                        $postion += 1;
                    }
                    $result_array_feature[] = $feature_master;
                }
                $stored_array[] = $val["bfm_id"];
                $stored_array = array_unique($stored_array);
            }
            $data["business_feature_info"] = $result_array_feature;
        } else {
            $data["business_feature_info"] = array();
        }
        if ($this->input->is_ajax_request()) {
            /* ----Added for updating availability information starts--- */
            if ($this->input->post("is_availability_info", TRUE)) {
                if (count($availability_info_array) > 0) {
                    $i = 0;
                    $inserting_data = array();
                    foreach ($availability_info_array as $val) {
                        if ($this->input->post('business_availability_info_' . $i) === 'on') {
                            $data_array['availability_master_id'] = $val['id'];
                            $data_array['business_id'] = $main_business_id;
                            $inserting_data[] = $data_array;
                        }
                        $i++;
                    }
                    if (count($inserting_data) > 0) {
                        $this->availabilityMaster_model->update_availability_with_business($inserting_data, $main_business_id);
                    }
                }
                $records['status'] = 1;
                $records['message'] = "Business availability information successfully updated";
                echo json_encode($records);
                exit();
            }
            /* ----Added for updating availability information ends--- */

            /* ----Added for updating service area information starts--- */
            if ($this->input->post("is_service_area_info", TRUE)) {
                if (count($service_area_info_array) > 0) {
                    $i = 0;
                    $inserting_data = array();
                    foreach ($service_area_info_array as $val) {
                        if ($this->input->post('business_service_area_' . $i) === 'on') {
                            $data_array['service_area_id'] = $val['id'];
                            $data_array['business_id'] = $main_business_id;
                            $inserting_data[] = $data_array;
                        }
                        $i++;
                    }
                    if (count($inserting_data) > 0) {
                        $this->serviceAreaMaster_model->update_service_area_business($inserting_data, $main_business_id);
                    }
                }
                $records['status'] = 1;
                $records['message'] = "Business service area information successfully updated";
                echo json_encode($records);
                exit();
            }
            /* ----Added for updating service area information ends--- */

            /* ----Added for updating additional language information starts--- */
            if ($this->input->post("is_additional_language_info", TRUE)) {
                if (count($additional_language_info_array) > 0) {
                    $i = 0;
                    $inserting_data = array();
                    foreach ($additional_language_info_array as $val) {
                        if ($this->input->post('additional_language_info_' . $i) === 'on') {
                            $data_array['additional_language_id'] = $val['id'];
                            $data_array['business_id'] = $main_business_id;
                            $inserting_data[] = $data_array;
                        }
                        $i++;
                    }
                    if (count($inserting_data) > 0) {
                        $this->additionalLanguageMaster_model->update_additional_language_business($inserting_data, $main_business_id);
                    }
                }
                $records['status'] = 1;
                $records['message'] = "Additional language information successfully updated";
                echo json_encode($records);
                exit();
            }
            /* ----Added for updating additional language information ends--- */

            /* ----Added for updating dynamic feature information starts--- */
            if ($this->input->post("is_dynamic_feature_info", TRUE)) {
                $feature_attribute = $this->input->post("feature_attribute", TRUE);
                $feature_attribute_array = explode(",", $feature_attribute);
                $inserting_data = array();
                foreach ($feature_attribute_array as $key => $value) {
                    $updating_information["feature_attribute_id"] = $value;
                    $updating_information["business_id"] = $main_business_id;
                    $inserting_data[] = $updating_information;
                }
                if (count($inserting_data) > 0) {
                    $this->businessFeatureMaster_model->update_dynamic_feature($inserting_data, $main_business_id);
                }
                $records['status'] = 1;
                $records['message'] = "Business feature attribute successfully updated";
                echo json_encode($records);
                exit();
            }
            /* ----Added for updating dynamic feature information ends--- */
        }
        $this->load->view('business-master/businessInformation', $data);
    }

    /**
     * Added for making a business
     * featured for the front page
     */
    public function featureBusiness() {
        if ($this->input->is_ajax_request()) {
            $encrypted_business_id = $this->input->post('identification');
            $main_business_id = $this->encrypt_decrypt->decrypt($encrypted_business_id, ENCRYPT_DECRYPT_KEY);
            $is_featured = $this->input->post('status');
            $updating_data["is_featured"] = "$is_featured";
            $updating_data["updated_at"] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->businessMaster_model->update_business($updating_data, "", $main_business_id);
            $result['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Count featured business
     */
    public function featureBusinessCount() {
        if ($this->input->is_ajax_request()) {
            $search_array["is_featured"] = "1";
            $countRecords = $this->businessMaster_model->fetchBusinessInfo($search_array);
            $result_array = $this->businessMaster_model->fetchBusinessResultMain($search_array, "updated_at", "ASC", 0, 3);
            if ($countRecords == 3) {
                $result["status"] = $countRecords;
                $struckture = "";
                $basic_struckture = "";
                
                foreach ($result_array as $res) {
                    $image="";
                    $image_url = ASSET_URL . 'uploads/vendor-images/preview/' . $res['profile_image'];
                    $image .= "<div class='col-md-4'><p><img src='" . $image_url . "' alt='' class='img-responsive'></p></div>";
                    $image .= "<div class='col-md-8'><p><span class='sbold'>" . $res['vendor_name'] . "</span></br>".$res['vendor_site_url']."</p></div>";
                    $basic_struckture .= ' <div class="col-md-12">' . $image . '</div>';
                }
                $result["struckture"] = $basic_struckture;
            } else {
                $result["status"] = 1;
            }
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

}

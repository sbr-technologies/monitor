<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class CmsManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('CmsMaster_Model', 'cmsmaster_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for listing
     * all the images
     */
    public function index() {
        $data['title'] = 'All Images';
        $this->load->view('cms-management/staticimagelist', $data);
    }

    /**
     * Added for listing images
     * through Ajax
     */
    public function ajaxImageList() {

        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('name') !== "") {
                $search_array['name'] = $this->input->post('name');
            }
            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_to') !== "") {
                $search_array['updated_at_to'] = $this->input->post('updated_at_to');
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
            $iTotalRecords = $this->cmsmaster_model->fetchStaticImageInfo('number', 0, $search_array);

            /* ---Get the number of data is going to be shown in the table--- */
            $iDisplayLength = intval($this->input->post('length'));
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;

            /* ---Get the start point of the table,this is the number of colomn from where the table is going to generate--- */
            $iDisplayStart = intval($this->input->post('start'));

            /* ---counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables--- */
            $sEcho = intval($this->input->post('draw'));

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            if ($orderBy === '1') {
                $orderByData = 'name';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->cmsmaster_model->fetchStaticImageList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);

            foreach ($result as $key => $res) {
                $image_html = '<img src="' . ASSET_URL . "uploads/static-images/thumb/" . $res['image_name'] . '">';
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-static-image/' . $encrypted_id) . '" class="btn btn-icon-only green " data-toggle="tooltip" title="edit" ><i class="fa fa-pencil"></i></a>';
                $records["data"][] = array($image_html, $res['name'], date("Y-m-d", strtotime($res['updated_at'])), $button_html);
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
     * Added for editing static images
     */
    public function editStaticImage() {
        $data['title'] = 'All Images';
        $encrypted_image_id = $this->uri->segment(2);
        $main_image_id = $this->encrypt_decrypt->decrypt($encrypted_image_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->cmsmaster_model->fetchStaticImageInfo('all', $main_image_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_image_id;

        if ($this->input->post("is_post")) {
            $source_image_path = UPLOADING_PATH . "static-images/original/";
            $preview_target = UPLOADING_PATH . 'static-images/preview/';
            $final_show = UPLOADING_PATH . 'static-images/final-show/';
            $thumb_target = UPLOADING_PATH . 'static-images/thumb/';
            if ($_FILES['static_image']['name'] !== "") {
                if ($result[0]['image_name'] !== NULL) {
                    unlink($source_image_path . $result[0]['image_name']);
                    unlink($preview_target . $result[0]['image_name']);
                    unlink($final_show . $result[0]['image_name']);
                    unlink($thumb_target . $result[0]['image_name']);
                }
            }
            $config['upload_path'] = $source_image_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 4500; /* ---The size in in KB(here it is 4.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            if ((int) $result[0]["id"] === 1) {
                $config['file_name'] = "home-banner";
            }
            if ((int) $result[0]["id"] === 2) {
                $config['file_name'] = "login-image";
            }
            if ((int) $result[0]["id"] === 3) {
                $config['file_name'] = "logo-image";
            }
            $config['file_ext_tolower'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('static_image')) {
                $upload_data = $this->upload->data();
                _resize_avtar($upload_data['file_name'], "150", "200", $preview_target, $source_image_path, FALSE);
                if ((int) $result[0]["id"] === 1) {
                    _resize_avtar($upload_data['file_name'], "900", "1920", $final_show, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "150", "320", $thumb_target, $source_image_path, TRUE);
                }
                if ((int) $result[0]["id"] === 2) {
                    $upload_data = $this->upload->data();
                    _resize_avtar($upload_data['file_name'], "523", "526", $final_show, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "162", "163", $thumb_target, $source_image_path, TRUE);
                }
                if ((int) $result[0]["id"] === 3) {
                    $upload_data = $this->upload->data();
                    _resize_avtar($upload_data['file_name'], "70", "180", $final_show, $source_image_path, FALSE);
                    _resize_avtar($upload_data['file_name'], "70", "180", $thumb_target, $source_image_path, TRUE);
                }
                chmod($source_image_path . $upload_data['file_name'], 0777);
                chmod($preview_target . $upload_data['file_name'], 0777);
                chmod($final_show . $upload_data['file_name'], 0777);
                $updating_data['image_name'] = $upload_data['file_name'];
                $updating_data['updated_at'] = date("Y-m-d H:i:s");
                $updating_data = $this->security->xss_clean($updating_data);

                $result = $this->cmsmaster_model->update_static_image($updating_data, $main_image_id);
            }
            redirect('static-image-list');
        }
        $this->load->view('cms-management/editStaticImage', $data);
    }

    /**
     * Get the list of
     * all the pages
     */
    public function pageList() {
        $data['title'] = 'All Pages';
        $this->load->view('cms-management/pageList');
    }

    /**
     * Added for SEO setting
     * information
     */
    public function seoSetting() {
        $data['title'] = 'Seo Setting';
        $page = $this->uri->segment(2);
        if ($page === "home") {
            $page_seo_id = 1;
        }
        if ($page === "login") {
            $page_seo_id = 2;
        }
        $result = $this->cmsmaster_model->pageSeoInfo($page_seo_id);
        $data["result"] = $result[0];
        $data['slug'] = $page;
        $config = array(
            array(
                'field' => 'title',
                'label' => 'title',
                'set_value' => 'title',
                'rules' => 'required',
                'errors' => array(
                    'required' => 'Please provide title of the page'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('cms-management/seoSetting', $data);
        } else {
            $updating_data['meta_description'] = $this->input->post("meta_description");
            $updating_data['meta_keywords'] = $this->input->post("meta_keywords");
            $updating_data['title'] = $this->input->post("title");
            $updating_data['meta_title'] = $this->input->post("meta_title");
            if ($updating_data['meta_title'] === "") {
                $updating_data['meta_title'] = $updating_data['title'];
            }
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->cmsmaster_model->update_seo($updating_data, $page_seo_id);
            redirect('page-list');
        }
    }

    /**
     * Added for listing all the
     * multilingual words
     */
    public function languageSetting() {
        $data['title'] = 'Language Setting';
        $this->load->view('cms-management/languageSetting', $data);
    }

    /**
     * Added for listing all the languages 
     * content through Ajax
     */
    public function ajaxLanguageContent() {

        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('word_en') !== "") {
                $search_array['word_en'] = $this->input->post('word_en');
            }
            if ($this->input->post('word_iw') !== "") {
                $search_array['word_iw'] = $this->input->post('word_iw');
            }
            if ($this->input->post('updated_at_from') !== "") {
                $search_array['updated_at_from'] = $this->input->post('updated_at_from');
            }
            if ($this->input->post('updated_at_to') !== "") {
                $search_array['updated_at_to'] = $this->input->post('updated_at_to');
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
            $iTotalRecords = $this->cmsmaster_model->fetchLanguageContentInfo('number', 0, $search_array);

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
                $orderByData = 'word_en';
            } elseif ($orderBy === '1') {
                $orderByData = 'word_iw';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->cmsmaster_model->fetchLanguageContentList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-content/' . $encrypted_id) . '" class="btn btn-icon-only green data-toggle="tooltip" title="edit"   "><i class="fa fa-pencil"></i></a>';

                $records["data"][] = array($res['word_en'], $res['word_iw'], date("Y-m-d", strtotime($res['datetime'])), $button_html);
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
     * Added for inserting 
     * new content
     */
    public function addNewContent() {
        $data['title'] = 'Add New Content';
        $config = array(
            array(
                'field' => 'word_en',
                'label' => 'word_en',
                'set_value' => 'word_en',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide new english content'
                ),
            ),
            array(
                'field' => 'word_iw',
                'label' => 'word_iw',
                'set_value' => 'word_iw',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide new hebrew content'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('cms-management/addNewContent', $data);
        } else {
            $inserting_data['word_en'] = $this->input->post('word_en');
            $inserting_data['word_iw'] = $this->input->post('word_iw');
            $inserting_data['datetime'] = date("Y-m-d H:i:s");
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->cmsmaster_model->add_new_content($inserting_data);
            redirect('language-setting');
        }
    }

    /**
     * Added for updating
     * existing content
     */
    public function editContent() {
        $data['title'] = 'Edit Content';
        $encrypted_content_id = $this->uri->segment(2);
        $main_content_id = $this->encrypt_decrypt->decrypt($encrypted_content_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->cmsmaster_model->fetchLanguageContentInfo('all', $main_content_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_content_id;
        $config = array(
            array(
                'field' => 'word_en',
                'label' => 'word_en',
                'set_value' => 'word_en',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide new english content'
                ),
            ),
            array(
                'field' => 'word_iw',
                'label' => 'word_iw',
                'set_value' => 'word_iw',
                'rules' => 'trim|required',
                'errors' => array(
                    'required' => 'Please provide new hebrew content'
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('cms-management/editContent', $data);
        } else {
            $updating_data['word_en'] = $this->input->post('word_en');
            $updating_data['word_iw'] = $this->input->post('word_iw');
            $updating_data['datetime'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->cmsmaster_model->update_content($updating_data, $main_content_id);
            redirect('language-setting');
        }
    }

    /**
     * Added for generating
     * cache for languages
     */
    public function generateCache() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('data') === "generate-cache") {
                $list = $this->cmsmaster_model->languageDetails();
                if (count($list) > 0) {
                    $language_array = array("en", "iw");
                    foreach ($language_array as $v) {
                        $dataArr = array();
                        foreach ($list as $i => $vv) {
                            $lang_item = $vv['word_' . $v];
                            $lang_item = addslashes($lang_item);
                            $arr_key = $vv['word'];
                            if (isset($lang_item) && $lang_item != '')
                                $dataArr[$arr_key] = $lang_item;
                        }
                        $ser = base64_encode(serialize($dataArr));
                        $file = CACHING_LANGUAGE_PATH . '/language_' . $v . ".txt";
                        if (file_exists($file)) {
                            $fh = fopen($file, 'w');
                            fwrite($fh, $ser);
                            fclose($fh);
                            $result["status"]=1;
                            $result["msg"]="Cache created successfully";
                            $this->output
                                    ->set_content_type('application/json')
                                    ->set_output(json_encode($result));
                        }
                    }
                }
            }
        }
    }

}

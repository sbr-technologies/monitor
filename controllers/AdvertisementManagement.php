<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class AdvertisementManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('AdvertisementMaster_model', 'advertisementMaster_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for listing
     * all the advertisement types
     */
    public function advertisementType() {
        $data['title'] = 'All Advertisement Type';
        $this->load->view('advertisement-management/advertisementType', $data);
    }

    /**
     * Added for all the 
     * advertisement types 
     * listing through Ajax
     */
    public function ajaxAdvertisementTypeList() {
        if ($this->input->is_ajax_request()) {
            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('advetisement_type_name') !== "") {
                $search_array['advertisement_type_advetisement_type_name'] = $this->input->post('name');
            }
            if ($this->input->post('tot_number_adds_from') !== "") {
                $search_array['tot_number_adds_from'] = $this->input->post('tot_number_adds_from');
            }
            if ($this->input->post('tot_number_adds_to') !== "") {
                $search_array['tot_number_adds_to'] = $this->input->post('tot_number_adds_to');
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
            if ($this->input->post('depend_on_category') !== "") {
                $search_array['depend_on_category'] = $this->input->post('depend_on_category');
            }

            $search_array = $this->security->xss_clean($search_array); /* ---Added for filtering array,furst cleaned the data and then array_filter is cleaning the blank keys of the array means the blank inputs or only the white space inputs got flushed--- */
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
            $iTotalRecords = $this->advertisementMaster_model->fetchAdvertisementTypeInfo('number', 0, $search_array);

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
                $orderByData = 'advertisement_type_name_order';
            } elseif ($orderBy === '1') {
                $orderByData = 'total_number_of_adds';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->advertisementMaster_model->fetchAdvertisementList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);
            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                if ($res['depend_on_category'] === '1') {
                    $depend_on_category_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $depend_on_category_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-advertisement-type/' . $encrypted_id) . '" class="btn btn-icon-only green" data-toggle="tooltip" title="edit"><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['name'] . '\')"  data-toggle="tooltip" title="delete"><i class="fa fa-trash"></i></button>';
                $records["data"][] = array($res['name'], $res['number_of_adds'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $depend_on_category_html, $button_html);
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
     * advertisement type
     */
    public function addAdvertisementType() {
        $data['title'] = 'Add Advertisement Type';
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_advertisment_type_add',
                'errors' => array(
                    'required' => 'Please provide advertisement type name',
                    'check_advertisment_type_add' => 'Oops !!! Advertisement type name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('advertisement-management/addAdvertisementType', $data);
        } else {
            $inserting_data['name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            if ($this->input->post('depend_on_category') === 'on') {
                $inserting_data['depend_on_category'] = '1';
            } else {
                $inserting_data['depend_on_category'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data['number_of_adds'] = 0;
            $inserting_data['uniq_code'] = clean_and_clear_content($inserting_data['name']);
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->advertisementMaster_model->add_advertisement_type($inserting_data);
            redirect('advertisement-type');
        }
    }

    /**
     * Added for counting
     * advertisement type at the time of adding
     * @param  string $advertisement_type // The name of the advertisement type
     * @return boolean          // Boolean response on the basis of advertisement type
     */
    public function check_advertisment_type_add($advertisement_type) {
        $search_array["advertisement_type_name"] = $advertisement_type;
        $result = $this->advertisementMaster_model->fetch_advertisement_details_by_name($advertisement_type_name);
        if (count($result) > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for checking advertisement 
     * type through Ajax
     */
    public function check_advertisment_type_add_ajax() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('name', true) !== "") {
                $advertisement_type_name = $this->input->post('name', true);
                $result = $this->advertisementMaster_model->fetch_advertisement_details_by_name($advertisement_type_name);
                if (count($result) > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for deleting advertisement type
     */
    public function deleteAdvertisementType() {
        if ($this->input->is_ajax_request()) {
            $advertisement_type_id = $this->input->post('advertisement_type_id');
            $main_advertisement_type_id = $this->encrypt_decrypt->decrypt($advertisement_type_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->advertisementMaster_model->update_advertisement_type($update_array, $main_advertisement_type_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing customer 
     * type
     */
    public function editAdvertisementType() {
        $data['title'] = 'Edit Advertisement Type';
        $encrypted_advertisement_type_id = $this->uri->segment(2);
        $main_advertisement_type_id = $this->encrypt_decrypt->decrypt($encrypted_advertisement_type_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->advertisementMaster_model->fetchAdvertisementTypeInfo('all', $main_advertisement_type_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_advertisement_type_id;
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_advertisement_type_name_exist_edit',
                'errors' => array(
                    'required' => 'Please provide advertisement type name',
                    'advertisement_type_name_exist_edit' => 'Oops !!! Additional language name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('advertisement-management/editAdvertisementType', $data);
        } else {
            $updating_data['name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $updating_data['status'] = '1';
            } else {
                $updating_data['status'] = '0';
            }
            if ($this->input->post('depend_on_category') === 'on') {
                $updating_data['depend_on_category'] = '1';
            } else {
                $updating_data['depend_on_category'] = '0';
            }
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->advertisementMaster_model->update_advertisement_type($updating_data, $main_advertisement_type_id);
            redirect('advertisement-type');
        }
    }

    /**
     * Added for checking additional language name is
     * already existed or not
     * @return boolean
     */
    public function advertisement_type_name_exist_edit() {
        $advertisement_type_id = $this->input->post('advertisement_type_id');
        $advertisement_type_name = $this->input->post('name', true);
        $result = $this->advertisementMaster_model->fetch_advertisement_details_by_name($advertisement_type_name);
        if (count($result) === 0) {
            $response = true;
        } else {
            if ($result[0]['id'] === $advertisement_type_id) {
                $response = true;
            } else {
                $response = false;
            }
        }
        return $response;
    }

    /**
     * Added for checking a 
     * additional language name exist or not at the time of editing
     * through Ajax
     */
    public function advertisement_type_name_exist_edit_ajax() {
        if ($this->input->is_ajax_request()) {
            $advertisement_type_id = $this->input->post('advertisement_type_id');
            $advertisement_type_name = $this->input->post('name', true);
            $result = $this->advertisementMaster_model->fetch_advertisement_details_by_name($advertisement_type_name);
            if (count($result) === 0) {
                $response = "true";
            } else {
                if ($result[0]['id'] === $advertisement_type_id) {
                    $response = "true";
                } else {
                    $response = "false";
                }
            }
            echo $response;
        }
    }

}

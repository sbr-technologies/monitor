<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class BusinessCharacteristicsController extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library('form_validation');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('BusinessCharacteristics_Model', 'businessCharacteristics_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for listing 
     * all the business characteristics
     */
    public function index() {
        $data['title'] = 'Business Characterstics';
        $this->load->view('business-characteristics/index', $data);   
    }

    /**
     * Added for listing all the 
     * business characteristics areas through AJAX
     */
    public function ajaxBusinessCharacteristics() {
        if ($this->input->is_ajax_request()) {

            /* ---Searching array blank initialized--- */
            $search_array = array();

            $records = array();
            $records["data"] = array();
            /* ---Search data array started--- */
            if ($this->input->post('business_characterstics_name') !== "") {
                $search_array['business_characterstics_name'] = $this->input->post('business_characterstics_name');
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
            $iTotalRecords = $this->businessCharacteristics_model->fetchBusinessCharacteristicsInfo('number', 0, $search_array);

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
                $orderByData = 'vendor_count';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->businessCharacteristics_model->fetchBusinessCharacteristicsList($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);

            foreach ($result as $key => $res) {
                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }
                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);
                $button_html = '<a href="' . base_url('edit-business-characteristics/' . $encrypted_id) . '" class="btn btn-icon-only green data-toggle="tooltip" title="edit"   "><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_this(\'' . $encrypted_id . '\',\'' . $res['name'] . '\')" data-toggle="tooltip" title="delete" ><i class="fa fa-trash"></i></button>';

                $records["data"][] = array($res['name'], $res['vendor_count'], date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
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
     * business characteristics
     */
    public function addBusinessCharacteristics() {
        $data['title'] = 'Add New Business Characteristics';
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_check_business_characteristics_name_exist_add',
                'errors' => array(
                    'required' => 'Please provide new business characteristics',
                    'check_business_characteristics_name_exist_add' => 'Oops !!! This business characteristics is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('business-characteristics/addBusinessCharacteristics', $data);
        } else {
            $inserting_data['name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $inserting_data['status'] = '1';
            } else {
                $inserting_data['status'] = '0';
            }
            $inserting_data['created_at'] = date("Y-m-d H:i:s");
            $inserting_data['updated_at'] = date("Y-m-d H:i:s");
            $inserting_data['vendor_count'] = 0;
            $inserting_data = $this->security->xss_clean($inserting_data);
            $inserted_id = $this->businessCharacteristics_model->add_business_characterstics($inserting_data);
            redirect('business-characteristics');
        }
    }

    /**
     * Added for checking 
     * business characteristics is enlisted or 
     * not
     * @param string $name
     * @return boolean
     */
    public function check_business_characteristics_name_exist_add($name) {
        $result = $this->businessCharacteristics_model->get_business_characteristics_name($name);
        if ($result > 0) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Added for checking 
     * business characteristics is enlisted or 
     * not through AJAX
     * @param string $name
     */
    public function ajax_business_characteristics_name_exist_add() {
        if ($this->input->is_ajax_request()) {
            if ($this->input->post('name', true) !== "") {
                $name = $this->input->post('name');
                $result = $this->businessCharacteristics_model->get_business_characteristics_name($name);
                if ($result > 0) {
                    echo "false";
                } else {
                    echo "true";
                }
            }
        }
    }

    /**
     * Added for deleting business
     * characteristics
     */
    public function deleteBusinessCharacteristics() {
        if ($this->input->is_ajax_request()) {
            $business_characteristics_id = $this->input->post('business_characteristics_id');
            $main_business_characteristics_id = $this->encrypt_decrypt->decrypt($business_characteristics_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->businessCharacteristics_model->update_business_characterstics($update_array, $main_business_characteristics_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for editing
     * business characteristics
     */
    public function editBusinessCharacteristics() {
        $data['title'] = 'Edit Business Characteristics';
        $encrypted_business_characteristics_id = $this->uri->segment(2);
        $main_business_characteristics_id = $this->encrypt_decrypt->decrypt($encrypted_business_characteristics_id, ENCRYPT_DECRYPT_KEY);
        $result = $this->businessCharacteristics_model->fetchBusinessCharacteristicsInfo('all', $main_business_characteristics_id, array());
        $data['result'] = $result[0];
        $data['encrypted_id'] = $encrypted_business_characteristics_id;
        $config = array(
            array(
                'field' => 'name',
                'label' => 'name',
                'set_value' => 'name',
                'rules' => 'trim|required|callback_business_characteristics_name_exist_edit',
                'errors' => array(
                    'required' => 'Please provide business characteristics name',
                    'business_characteristics_name_exist_edit' => 'Oops !!! Business characterstics name is already taken',
                ),
            )
        );
        $this->form_validation->set_rules($config);
        if ($this->form_validation->run() === FALSE) {
            $this->load->view('business-characteristics/editBusinessCharacteristics', $data);
        } else {
            $updating_data['name'] = $this->input->post('name');
            if ($this->input->post('status') === 'on') {
                $updating_data['status'] = '1';
            } else {
                $updating_data['status'] = '0';
            }
            $updating_data['updated_at'] = date("Y-m-d H:i:s");
            $updating_data = $this->security->xss_clean($updating_data);
            $this->businessCharacteristics_model->update_business_characterstics($updating_data, $main_business_characteristics_id);
            redirect('business-characteristics');
        }
    }

    /**
     * Added for checking business characteristics name is
     * already existed or not
     * @return boolean
     */
    public function business_characteristics_name_exist_edit() {
        $business_characteristics_id = $this->input->post('business_characteristics_id');
        $name = $this->input->post('name', true);
        $result = $this->businessCharacteristics_model->get_business_characteristics_details_name($name);
        if (count($result) === 0) {
            $response = true;
        } else {
            if ($result[0]['id'] === $business_characteristics_id) {
                $response = true;
            } else {
                $response = false;
            }
        }
        return $response;
    }

    /**
     * Added for checking a 
     * business characteristics name exist or not at the time of editing
     * through ajax
     */
    public function business_characteristics_name_exist_edit_ajax() {
        if ($this->input->is_ajax_request()) {
            $business_characteristics_id = $this->input->post('business_characteristics_id');
            $name = $this->input->post('name', true);
            $result = $this->businessCharacteristics_model->get_business_characteristics_details_name($name);
            if (count($result) === 0) {
                $response = "true";
            } else {
                if ($result[0]['id'] === $business_characteristics_id) {
                    $response = "true";
                } else {
                    $response = "false";
                }
            }
            echo $response;
        }
    }

}

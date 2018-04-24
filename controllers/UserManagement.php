<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class UserManagement extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        $this->load->library(array(
            'form_validation',
            'email',
        ));
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('Users_model', 'users_model');
        /* -------Model Files-------- */
    }

    /* -------------User Management Begins------------ */

    /**
     * Added for users
     * listing for the admin
     */
    public function index() {
        $data['title'] = 'Users';
        $this->load->view('user-management/users/index', $data);
    }

    /**
     * Added for creating Ajax
     * table for customers
     */
    public function ajaxCustomerList() {
        if ($this->input->is_ajax_request()) {
            /*---Searching array blank initialized---*/
            $search_array = array();

            $records = array();
            $records["data"] = array();

            /*---Search data array started---*/
            if ($this->input->post('user_name') !== "") {
                $search_array['user_name'] = $this->input->post('user_name');
            }
            if ($this->input->post('email') !== "") {
                $search_array['email'] = $this->input->post('email');
            }
            if ($this->input->post('phone') !== "") {
                $search_array['phone'] = $this->input->post('phone');
            }
            if ($this->input->post('city') !== "") {
                $search_array['city'] = $this->input->post('city');
            }
            if ($this->input->post('signup_type') !== "") {
                $search_array['signup_type'] = $this->input->post('signup_type');
            }
            if ($this->input->post('newsletter_subscribe') !== "") {
                $search_array['newsletter_subscribe'] = $this->input->post('newsletter_subscribe');
            }
            if ($this->input->post('last_log_in_from') !== "") {
                $search_array['last_log_in_from'] = $this->input->post('last_log_in_from');
            }
            if ($this->input->post('last_log_in_to') !== "") {
                $search_array['last_log_in_to'] = $this->input->post('last_log_in_to');
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

            $search_array = $this->security->xss_clean($search_array); /*---Added for filtering array cleaned the data ---*/
            /*---Search data array ended---*/

            /*---Index of the sorting column (0 index based - i.e. 0 is the first record)---*/
            $orderState = $this->input->post('order');
            $orderByColumnIndex = $orderState[0]['column'];

            /*---Get name of the sorting column from its index---*/
            $colomnToSort = $this->input->post('columns');
            $orderBy = $colomnToSort[$orderByColumnIndex]['data'];

            /*---Added for checking what kind of order ASC or DESC---*/
            $orderTypeState = $this->input->post('order');
            $orderByType = $orderTypeState[0]['dir'];

            /*---Get the number of records to be fetched---*/
            $iTotalRecords = $this->users_model->fetchCustomerInfo('number', 0, $search_array);

            /*---Get the number of data is going to be shown in the table---*/
            $iDisplayLength = intval($this->input->post('length'));
            $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;

            /*---Get the start point of the table,this is the number of colomn from where the table is going to generate---*/
            $iDisplayStart = intval($this->input->post('start'));

            /*---counter used by DataTables to ensure that the Ajax returns from server-side processing requests are drawn in sequence by DataTables---*/
            $sEcho = intval($this->input->post('draw'));

            $end = $iDisplayStart + $iDisplayLength;
            $end = $end > $iTotalRecords ? $iTotalRecords : $end;

            if ($orderBy === '1') {
                $orderByData = 'user_name';
            } elseif ($orderBy === '2') {
                $orderByData = 'email';
            } elseif ($orderBy === '3') {
                $orderByData = 'phone';
            } elseif ($orderBy === '4') {
                $orderByData = 'city';
            } elseif ($orderBy === '7') {
                $orderByData = 'last_login';
            } else {
                $orderByData = 'updated_at';
            }

            $result = $this->users_model->fetchCustomerResultMain($search_array, $orderByData, $orderByType, $iDisplayStart, $iDisplayLength);

            foreach ($result as $key => $res) {
                $image_url = ASSET_URL . "uploads/customer-images/preview/" . $res['profile_picture'];
                $image_html = '<img src="' . ASSET_URL . "uploads/customer-images/thumb/" . $res['profile_picture'] . '">';

                if ($res['status'] === '1') {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/active.png">';
                } else {
                    $status_html = '<img src="' . ASSET_URL . 'global/img/inactive.png">';
                }

                if ($res['newsletter_subscribe'] === '1') {
                    $newsletter_subscribe_html = '<img src="' . ASSET_URL . 'global/img/thumbs_up.png">';
                } else {
                    $newsletter_subscribe_html = '<img src="' . ASSET_URL . 'global/img/thumbs_down.png">';
                }

                if ($res['signup_type'] === '1') {
                    $sign_up_html = '<img src="' . ASSET_URL . 'global/img/manual.png">';
                } else if ($res['signup_type'] === '2') {
                    $sign_up_html = '<img src="' . ASSET_URL . 'global/img/googleplus.png">';
                } else {
                    $sign_up_html = '<img src="' . ASSET_URL . 'global/img/facebook.png">';
                }

                $encrypted_id = $this->encrypt_decrypt->encrypt($res['id'], ENCRYPT_DECRYPT_KEY);

                if ($res['last_login']) {
                    $login_html = date("Y-m-d", strtotime($res['last_login']));
                } else {
                    $login_html = '<p class="font-red-mint">The user never logged in</p>';
                }
                if ($res['status'] == '1') {
                    $icon = '<i class=" fa fa-minus-circle"></i>';
                    $button_Class = " btn btn-icon-only red-flamingo ";
                    $status_msg = "block";
                } else {
                    $icon = '<i class=" fa fa-check"></i>';
                    $button_Class = " btn btn-icon-only yellow-soft ";
                    $status_msg = "unblock";
                }

                $button_html = '<button type="button" class="btn btn-icon-only yellow" id="send-mail-button-' . $encrypted_id . '" onclick="javascript:send_mail_customer(\'' . $encrypted_id . '\',\'' . $res['user_name'] . '\',\'' . $image_url . '\')" ><i class="fa fa-key"></i></button><a href="' . base_url('customers/edit/' . $encrypted_id) . '" class="btn btn-icon-only green"><i class="fa fa-pencil"></i></a><button type="button" class="btn btn-icon-only red" id="delete-button-' . $encrypted_id . '" onclick="javascript:delete_customer(\'' . $encrypted_id . '\',\'' . $res['user_name'] . '\',\'' . $image_url . '\')" ><i class="fa fa-trash"></i></button><button type="button" class="' . $button_Class . '" id="status-change-button-' . $encrypted_id . '" onclick="javascript:customer_status_change(\'' . $encrypted_id . '\',\'' . $res['user_name'] . '\',\'' . $image_url . '\',\'' . $status_msg . '\')" >' . $icon . '</i></button>';

                $records["data"][] = array($image_html, $res['user_name'], $res['email'], $res['phone'], $res['city'], $sign_up_html, $newsletter_subscribe_html, $login_html, date("Y-m-d", strtotime($res['updated_at'])), $status_html, $button_html);
            }
            $records["draw"] = $sEcho;
            $records["recordsTotal"] = $iTotalRecords;
            $records["recordsFiltered"] = $iTotalRecords;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /* -------------User Management Ends------------ */

    /* -------------Vendor Management Begins------------ */

    /**
     * Added for vendors
     * listing for the admin
     */
    public function vendorList() {
        $data['title'] = 'Vendors';
        $this->load->view('user-management/vendors/index', $data);
    }

    /* -------------Vendor Management Ends------------ */

    /**
     * Added for deleting customer
     */
    public function deleteUser() {
        if ($this->input->is_ajax_request()) {
            $user_id = $this->input->post('user_id');
            $type = $this->input->post('type');
            $main_user_id = $this->encrypt_decrypt->decrypt($user_id, ENCRYPT_DECRYPT_KEY);
            $update_array['status'] = "3";
            $this->users_model->update_user($update_array, $main_user_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

    /**
     * Added for send mail
     * for change password
     * for Customers or Users
     */
    public function customersChangePassword() {
        if ($this->input->is_ajax_request()) {
            $encrypted_customers_id = $this->input->post('user_id');
            $data['encrypted_customers_id'] = $encrypted_customers_id;
            $customers_id = $this->encrypt_decrypt->decrypt($encrypted_customers_id, ENCRYPT_DECRYPT_KEY);
            $this->load->helper('string'); /* For generate random alphanumeric string using CI helper('string') */
            $token = random_string('alnum', 34);
            $html_string = "<!DOCTYPE html>
                                <head>
                                </head>
                                <body style='font-family: Arial; font-size: 12px;'>
                                    <div>
                                        <p>
                                            You have requested a password reset, please follow the link below to reset your password.
                                        </p>
                                        <p>
                                            Please ignore this email if you did not request a password change.
                                        </p>
                                        <p>
                                            <a href='" . base_url('customers-edit-password/' . $encrypted_customers_id . '/' . $token) . "' target='_blank'>click here for reset password</a>
                                        </p>
                                    </div>
                                </body>
                            </html>";

            $from = 'souvik@gmail.com';
            $to = 'karmicksol57@gmail.com';
            $subject = 'For password change.';
            if (htmlMailSend($from, $to, $subject, $html_string)) {
                $update_array['change_password'] = '0';
                $update_array['_token'] = $token;
                $customers_id;
                $update = $this->users_model->update_user($update_array, $customers_id);
                $records['status'] = 1;
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($records));
            } else {
                $this->email->print_debugger();
                $records['status'] = 0;
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($records));
            }
        }
    }

    /**
     * Added for status change customer
     */
    public function userStatusChange() {
        if ($this->input->is_ajax_request()) {
            $user_id = $this->input->post('user_id');
            $type = $this->input->post('type');
            $main_user_id = $this->encrypt_decrypt->decrypt($user_id, ENCRYPT_DECRYPT_KEY);
            $fetchinfo = $this->users_model->fetchCustomerInfo('all', $main_user_id);
            $status = $fetchinfo['0']['status'];
            if ($status == 1) {
                $update_array['status'] = '0';
            } else {
                $update_array['status'] = '1';
            }
            $this->users_model->update_user($update_array, $main_user_id);
            $records['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($records));
        }
    }

}

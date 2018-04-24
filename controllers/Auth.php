<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('encryption');
        $this->load->helper(array(
            'form',
            'security',
        ));
        /* -------Model Files-------- */
        $this->load->model('AdminLogin_model', 'adminLogin_model');
        /* -------Model Files-------- */
    }

    /**
     * Added for admin
     * login base function
     * @return view //Authenticating admin login based on credential
     */
    public function index() {
        if ($this->agent->is_browser()) {
            $this->load->library('form_validation');
            $admin_log_status = $this->session->userdata('admin_login');
            if ($admin_log_status['user'] === 'admin' && $admin_log_status['logged_in'] === TRUE) {
                redirect('dashboard', 'refresh');
            } else {
                $data['login_page_data'] = $this->adminLogin_model->check_admin();
                $config = array(
                    array(
                        'field' => 'email',
                        'label' => 'Email',
                        'set_value' => 'email',
                        'rules' => 'required|valid_email|trim',
                        'errors' => array(
                            'required' => 'Please provide email',
                            'valid_email' => 'Please provide valid email',
                        ),
                    ),
                    array(
                        'field' => 'password',
                        'label' => 'Password',
                        'rules' => 'required|trim',
                        'errors' => array(
                            'required' => 'You must provide a %s.',
                        ),
                    )
                );
                $this->form_validation->set_rules($config);
                if ($this->form_validation->run() == FALSE) {
                    $this->load->view('auth/index', $data);
                } else {
                    $data['email'] = $this->input->post('email');
                    $data['password'] = $this->input->post('password');
                    $data = $this->security->xss_clean($data);
                    $admin_result = $this->adminLogin_model->check_admin();
                    $email_admin = $admin_result['email'];
                    $password_admin = $this->encryption->decrypt(trim($admin_result['password']));
                    if ($data['email'] === $email_admin && $password_admin === $data['password']) {
                        $this->adminLogin_model->update_login_time();
                        $admin_data = array(
                            'user' => 'admin',
                            'logged_in' => TRUE,
                        );
                        $this->session->set_userdata('admin_login', $admin_data);
                        if (get_cookie('rolling_back_url')) {
                            redirect(get_cookie('rolling_back_url'), 'refresh');
                        } else {
                            redirect('dashboard', 'refresh');
                        }
                    } else {
                        $this->session->set_flashdata('login-error', 'Oops!!! Invalid login credential...');
                        $data["image_url"] = captchaCreation();
                        $this->load->view('auth/index', $data);
                    }
                }
            }
        } else {
            if ($this->agent->is_robot()) {
                echo $this->agent->robot() . " detected. Opps !!! You cant access the web application.";
            } else {
                echo "Please use a browser. You are detected by the system. Get a life.";
            }
        }
    }

    /**
     * Logout system
     * of the authority/admin
     * @return redirect
     */
    public function logout() {
        is_admin();
        $this->session->unset_userdata('admin_login');
        redirect('/', 'refresh');
    }

    /**
     * Added for admin edit
     * profile
     */
    public function editProfile() {
        is_admin();
        $data['title'] = 'Admin Profile Edit';
        $data['result'] = $this->adminLogin_model->get_details_admin();
        $this->load->view('auth/editProfile', $data);
    }

    /**
     * Added for ajax
     * update user profile
     */
    public function updateProfile() {
        is_admin();
        if ($this->input->is_ajax_request()) {
            $request_body = file_get_contents('php://input');
            $json = json_decode($request_body);
            $email = $json->code;
            $key = "@&^#%))J10%@#mlo^!"; /* --Provide Your secret key here what you have given in javascript-- */
            $data['email'] = ajax_data_decrypt($key, $email);
            $data['name'] = $json->name;
            $data['updated_at'] = date("Y-m-d H:i:s");
            $data = $this->security->xss_clean($data);
            $this->adminLogin_model->update_admin($data, 1);
            $result['status'] = 1;
            $this->output
                    ->set_content_type('application/json')
                    ->set_output(json_encode($result));
        }
    }

    /**
     * Added for updating
     * profile picture of the admin
     */
    public function updateProfilePicture() {
        is_admin();
        if ($this->input->is_ajax_request()) {
            $source_image_path = UPLOADING_PATH . "/admin-images/original/";
            $file_name = 'admin-picture-' . rand(10000, 99999) . "-" . time() . "-" . rand(10000, 99999);
            $config['upload_path'] = $source_image_path;
            $config['allowed_types'] = 'gif|jpg|png';
            $config['max_size'] = 1500; /* ---The size in in KB(here it is 1.5 MB)--- */
            $config['remove_spaces'] = TRUE;
            $config['file_name'] = $file_name;
            $config['file_ext_tolower'] = TRUE;
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('profile_picture')) {
                $upload_data = $this->upload->data();
                $preview_target = UPLOADING_PATH . "/admin-images/preview/";
                _resize_avtar($upload_data['file_name'], "200", "150", $preview_target, $source_image_path,TRUE);
                $thumb_target = UPLOADING_PATH . "/admin-images/thumb/";
                _resize_avtar($upload_data['file_name'], "29", "29", $thumb_target, $source_image_path,TRUE);
                /* -- Everything for owner, read and execute for others -- */
                chmod($source_image_path . $upload_data['file_name'], 0755);
                chmod($preview_target . $upload_data['file_name'], 0755);
                chmod($thumb_target . $upload_data['file_name'], 0755);
                $data['profile_picture'] = $upload_data['file_name'];
                $data['updated_at'] = date("Y-m-d H:i:s");
                $data = $this->security->xss_clean($data);
                $this->adminLogin_model->update_admin($data, 1);
                $result['status'] = 1;
                $image_url = ASSET_URL . "uploads/admin-images/preview/" . $upload_data['file_name'];
                $result['image_html'] = "<img src='{$image_url}' alt=''/>";
                $result['image_thumb_url'] = ASSET_URL . "uploads/admin-images/thumb/" . $upload_data['file_name'];
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($result));
            } else {
                $result['status'] = 0;
                $result['err_string'] = strip_tags($this->upload->display_errors());
                $this->output
                        ->set_content_type('application/json')
                        ->set_output(json_encode($result));
            }
        }
    }

    /**
     * Added for checking profile
     * password
     */
    public function checkProfilePassword() {
        is_admin();
        if ($this->input->is_ajax_request()) {
            $request_body = file_get_contents('php://input');
            $json = json_decode($request_body);
            $password = $json->firstCodeValue;
            $newPassword = $json->secondCodeValue;
            $key = "@&^#%))J10%@#ml#@#"; /* --Provide Your secret key here what you have given in javascript-- */
            $password = ajax_data_decrypt($key, $password);
            $newPassword = ajax_data_decrypt($key, $newPassword);
            $admin_result = $this->adminLogin_model->check_admin();
            $password_admin = $this->encryption->decrypt(trim($admin_result['password']));
            if ($password_admin === $password) {
                $data['password'] = $this->encryption->encrypt($newPassword);
                $data['updated_at'] = date("Y-m-d H:i:s");
                $data = $this->security->xss_clean($data);
                $this->adminLogin_model->update_admin($data, 1);
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
        }
    }

}

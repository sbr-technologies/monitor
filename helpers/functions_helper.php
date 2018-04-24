<?php

/**
 * Added for checking logged in user is admin or not
 * @return boolean
 */
function is_admin($current_url = "") {
    $ci = & get_instance();
    $admin_log_status = $ci->session->userdata('admin_login');
    if ($ci->agent->is_browser()) {
        if ($admin_log_status['user'] === 'admin' && $admin_log_status['logged_in'] === TRUE) {
            delete_cookie('rolling_back_url');
            return TRUE;
        } else {
            if ($current_url !== "") {
                set_cookie('rolling_back_url', $current_url);
            }
            redirect('/', 'refresh');
        }
    } else {
        $ci->session->unset_userdata('admin_login');
        redirect('/', 'refresh');
    }
}

/**
 * Check a given string is presented in the url or not
 * @param  string  $parameter 
 * @return boolean            
 */
function is_presented_in_url($parameter) {
    $curent_url_array = explode("/", current_url());
    if (in_array($parameter, $curent_url_array)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

/**
 * Added for checking page is accessed 
 * through browser or not
 * @return boolean //Checked the page is only called by a browser or not
 */
function checkBrowser() {
    $ci = & get_instance();
    if ($ci->agent->is_browser()) {
        return true;
    } else {
        if ($ci->agent->is_robot()) {
            echo $ci->agent->robot() . " detected. Opps !!! You cant access the web application.";
        } else {
            echo "Please use a browser. You are detected by the system. Get a life.";
        }
    }
}

/**
 * Added for creating 
 * creating url slug
 * @param  string  $str //Target string to create slug
 * @param  integer $no  //How much number is to be taken
 * @return string       //Generated url slug
 */
function urlslug($str, $no = 200) {
    $str = trim($str);
    $str = str_replace('&#34;', ' ', $str);
    $str = strip_tags($str);
    $str = preg_replace('/[~!@#$%^?;:,\'"`&*><(){}\[\]\.]/', ' ', $str);
    $str = str_replace('/', ' ', $str);
    $str = trim($str);
    $str = str_replace(' ', '-', $str);
    $str = str_replace('--', '-', $str);
    $str = str_replace('--', '-', $str);
    $str = str_replace('--', '-', $str);
    $str = substr($str, 0, $no);
    return $str;
}

/* ---Added for ajax data decrypt begins--- */

function mb_chr($char) {
    return mb_convert_encoding('&#' . intval($char) . ';', 'UTF-8', 'HTML-ENTITIES');
}

function mb_ord($char) {
    $result = unpack('N', mb_convert_encoding($char, 'UCS-4BE', 'UTF-8'));
    if (is_array($result) === true) {
        return $result[1];
    }
    return ord($char);
}

function ajax_data_decrypt($key, $str) {
    if (extension_loaded('mbstring') === true) {
        mb_language('Neutral');
        mb_internal_encoding('UTF-8');
        mb_detect_order(array('UTF-8', 'ISO-8859-15', 'ISO-8859-1', 'ASCII'));
    }
    $s = array();
    for ($i = 0; $i < 256; $i++) {
        $s[$i] = $i;
    }
    $j = 0;
    for ($i = 0; $i < 256; $i++) {
        $j = ($j + $s[$i] + mb_ord(mb_substr($key, $i % mb_strlen($key), 1))) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
    }
    $i = 0;
    $j = 0;
    $res = '';
    for ($y = 0; $y < mb_strlen($str); $y++) {
        $i = ($i + 1) % 256;
        $j = ($j + $s[$i]) % 256;
        $x = $s[$i];
        $s[$i] = $s[$j];
        $s[$j] = $x;
        $res .= mb_chr(mb_ord(mb_substr($str, $y, 1)) ^ $s[($s[$i] + $s[$j]) % 256]);
    }
    return $res;
}

/* ---Added for ajax data decrypt ends--- */

/**
 * Added for resizing images
 * @param  string $photo  
 * @param  string $height 
 * @param  string $width  
 * @param  string $target_path 
 * @param  string $source_image_path 
 * @param  boolean $maintain_ratio 
 */
function _resize_avtar($photo, $height, $width, $target_path, $source_image_path, $maintain_ratio) {
    $ci = & get_instance();
    $ci->load->library('image_lib');
    $config['image_library'] = 'gd2';
    if ($maintain_ratio) {
        $config['maintain_ratio'] = TRUE;
    } else {
        $config['maintain_ratio'] = FALSE;
    }
    $config['width'] = $width;
    $config['height'] = $height;
    $config['source_image'] = $source_image_path . $photo;
    $config['new_image'] = $target_path . $photo;
    $ci->image_lib->initialize($config);
    $ci->image_lib->resize();
    $ci->image_lib->clear();
}

/**
 * Added for fetching
 * admin basic nevigation
 * struckture
 * @return string //returning the html struckture
 */
function get_admin_info() {
    $ci = & get_instance();
    $ci->load->model('AdminLogin_model', 'adminLogin_model');
    $admin_result = $ci->adminLogin_model->check_admin();
    $admin_url = ASSET_URL . "uploads/admin-images/preview/" . $admin_result['profile_picture'];
    $html_string = '<img alt="" class="img-circle" src="' . $admin_url . '" id="admin_image_nav"/>
                                    <span class="username username-hide-on-mobile" id="user_name_admin_nav"> ' . $admin_result['name'] . ' </span>';
    return $html_string;
}

/* ---Captcha management system starts--- */

/**
 * Helper function to crate captcha
 * @return string //returning the captcha image url
 */
function captchaCreation() {
    $ci = & get_instance();
    flush_captcha();
    $ci->load->helper('captcha');
    $vals = array(
        'img_path' => WEBADMIN_PATH . 'captcha-image/',
        'img_url' => base_url('login/captcha-image/'),
        'font_path' => WEBADMIN_PATH . 'font/texb.ttf',
        'img_width' => 500,
        'img_height' => 70,
        'expiration' => 7200,
        'word_length' => 8,
        'font_size' => 25,
        'pool' => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
        // White background and border, black text and red grid
        'colors' => array(
            'background' => array(255, 255, 255),
            'border' => array(255, 255, 255),
            'text' => array(0, 0, 0),
            'grid' => array(255, 40, 40)
        )
    );
    $catpcha_details = create_captcha($vals);
    $image_url = base_url('login/captcha-image/') . $catpcha_details['filename'];
    $ci->session->set_userdata('captchaCode', $catpcha_details['word']);
    $ci->session->set_userdata('captchaCodePath', WEBADMIN_PATH . 'captcha-image/' . $catpcha_details['filename']);
    return $image_url;
}

/**
 * Flushing the captcha
 */
function flush_captcha() {
    $ci = & get_instance();
    if (trim($ci->session->userdata('captchaCodePath')) !== "" && trim($ci->session->userdata('captchaCode')) !== "") {
        $ci->session->unset_userdata('captchaCode');
        unlink($ci->session->userdata('captchaCodePath'));
        $ci->session->unset_userdata('captchaCodePath');
    }
}

/**
 * Added for captcha checking 
 * @param  string $captchaCode //Captcha code by the user
 * @return boolean             //Entered captcha is okay or not 
 */
function captchacheck($captchaCode) {
    $ci = & get_instance();
    if ($ci->session->userdata('captchaCode') === $captchaCode) {
        flush_captcha();
        return true;
    } else {
        return false;
    }
}

/* ---Captcha management system ends--- */

/**
 * Added for html mail sending 
 * @param  string $from 
 * @param  string $to
 * @param  string $subject
 * @param  string $message//main HTML content 
 * @return boolean             //mail send successfully or not
 */
function htmlMailSend($from, $to, $subject, $message) {
    $ci = & get_instance();
    $ci->load->library('email');
    $config = Array(
        'protocol' => 'sendmail',
        'smtp_host' => 'ssl://smtp.googlemail.com',
        'smtp_port' => 465,
        'smtp_user' => 'SMTP Username', //'smtp.gmail.com'; // if you are using gmail
        'smtp_pass' => 'SMTP Password',
        'smtp_timeout' => '4',
        'charset' => 'iso-8859-1',
        'mailtype' => 'html'
    );
    $ci->load->library('email', $config);
    $ci->email->set_newline("\r\n");
    $ci->email->set_header('MIME-Version', '1.0; charset=utf-8');
    $ci->email->set_header('Content-type', 'text/html');
    $ci->email->from($from, 'souvik');
    $ci->email->to($to);
    $ci->email->subject($subject);
    $ci->email->message($message);
    $send_mail = $ci->email->send();
    if ($send_mail) {
        return true;
    } else {
        return false;
    }
}

/* ----html mail sending function ends---- */

/**
 * Added for creating 
 * unique codes based on 
 * advertisement type
 * @param string $string
 * @return string
 */
function clean_and_clear_content($string) {
    $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
    $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string) . "-" . rand(000, 999); // Removes special chars.

    return preg_replace('/-+/', '-', $string); // Replaces multiple hyphens with single one.
}

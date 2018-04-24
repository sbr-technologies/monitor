<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        is_admin(current_url());
        /* -------Model Files-------- */
        /* -------Model Files-------- */
    }

    /**
     * Added for dashboard
     * page for the admin
     */
    public function index() {
        $data['title'] = 'Dashboard';
        $this->load->view('dashboard/index', $data);
    }

}

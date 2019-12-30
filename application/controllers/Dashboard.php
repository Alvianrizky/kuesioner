<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->library(array('ion_auth', 'template'));
    }

    public function index()
    {
        if (!$this->ion_auth->logged_in()){
            
            redirect('auth/login', 'refresh');
        }

        $data = array();
        
        $this->template->backend('dashboard_v', $data);
    }
}

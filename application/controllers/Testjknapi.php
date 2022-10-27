<?php

include FCPATH . "tbsphp_jkn_api_cloud.php";

if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testjknapi extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = null;
        $this->load->view('testjknapi', $data);
    }
}
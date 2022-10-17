<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test extends CI_Controller 
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('accounting');
        $this->load->model('M_piutang', 'piutang', TRUE);
        $this->load->model('M_cetak', 'pdf');
        $this->load->model('M_rs', 'rs');
    }
    
    public function index()
    {
        // var_dump($this->piutang->get_ar_aging_data((object) [
        //         'vendor' => 'BPJS'
        // ]));
        // exit;
        
        return $this->output
            ->set_content_type('application/json')
            ->set_status_header(200)
            ->set_output(json_encode($this->piutang->get_ar_aging_data((object) [
                'todate' => '2022-01-31'
            ])));
    }
}
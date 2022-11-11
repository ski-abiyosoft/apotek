<?php


    defined("BASEPATH") or exit ("No direct script access allowed");

    Class Ro2 extends CI_Controller{

        public function __construct(){
            parent::__construct();

            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2750');
            $this->load->model(array("M_ro2",'M_barang'));
        }

        public function index(){
            $filter_date    = $this->input->get("filterdate");

            if(isset($filter_date)){
                $extract	= explode("~", $filter_date);
    
                $date_extarct	= (object) [
                    "start"	=> $extract[1],
                    "end"	=> $extract[2]
                ];
    
                if($extract[0] == "1"){
                    $list_eorder	= $this->M_ro2->get_list_eorder($date_extarct)->result();
                    $list_order	    = $this->M_ro2->get_list_order()->result();
                } else
                if($extract[0] == "2"){
                    $list_eorder	= $this->M_ro2->get_list_eorder()->result();
                    $list_order	    = $this->M_ro2->get_list_order($date_extarct)->result();
                } else {
                    header("Location:/ro2/");
                }
            } else {
                $list_eorder	= $this->M_ro2->get_list_eorder()->result();
                $list_order	    = $this->M_ro2->get_list_order()->result();
            }

            $data   = [
                "modul" => "Radiologi",
                "title" => "Radiologi",

            ];

            $this->load->view("Ro2/index", $data);
        }

    }
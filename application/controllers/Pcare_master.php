<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class Pcare_master extends CI_Controller{

        public function __construct(){
            parent::__construct();

            $this->session->set_userdata('menuapp', '2900');
		    $this->session->set_userdata('submenuapp', '2903');

            $username   = $this->session->userdata("username");
            if(!isset($username)){
                redirect(base_url("app/logout"));
            }

            $this->load->model([
                "M_pcare"   => "pcare"
            ]);
        }

        public function index(){
            $list_master    = [
                "01" => "Diagnosa",
                "02" => "Dokter",
                "03" => "Faskes",
                "04" => "KDTKP",
                "05" => "Kesadaran",
                "06" => "Obat",
                "07" => "Poli",
                "08" => "Provider",
                "09" => "Sarana",
                "10" => "Spesialis",
                "11" => "Sub Spesialis",
                "12" => "Status Pulang",
                "13" => "Tindakan"
            ];

            $data   = [
                "list_master"       => $list_master,
                "diagnosa"          => $this->pcare->get_data("bpjs_pcare_diagnosa"),
                "dokter"            => $this->pcare->get_data("bpjs_pcare_dokter"),
                "fakses"            => $this->pcare->get_data("bpjs_pcare_faskes"),
                "kdtkp"             => $this->pcare->get_data("bpjs_pcare_kdtkp"),
                "kesadaran"         => $this->pcare->get_data("bpjs_pcare_kesadaran"),
                "obat"              => $this->pcare->get_data("bpjs_pcare_obat"),
                "poli"              => $this->pcare->get_data("bpjs_pcare_poli"),
                "provider"          => $this->pcare->get_data("bpjs_pcare_provider"),
                "sarana"            => $this->pcare->get_data("bpjs_pcare_sarana"),
                "spesialis"         => $this->pcare->get_data("bpjs_pcare_spesialis"),
                "subspesialis"      => $this->pcare->get_data("bpjs_pcare_spesialis_sub"),
                "statuspulang"      => $this->pcare->get_data("bpjs_pcare_status_pulang"),
                "tindakan"          => $this->pcare->get_data("bpjs_pcare_tindakan"),
            ];

            $this->load->view("pcare/master", $data);
        }
        
        // public function data_header($param){
        //     switch($param){
        //         case "01" : 
        //             $table_title    = "Data Diagnosa";
        //             $total_data     = $this->pcare->get_data("bpjs_pcare_diagnosa")->num_rows();
        //             // $render = "<tr>
        //                 // <th style='color:#fff !important'>Kode Diagnosa</th>
        //                 // <th style='color:#fff !important'>Nama Diagnosa</th>
        //             // </tr>";
        //             $render = ["Kode Diagnosa", "Nama Diagnosa"];
        //             break;
        //         case "02" : 
        //             $table_title    = "Data Dokter";
        //             $total_data     = $this->pcare->get_data("bpjs_pcare_dokter")->num_rows();
        //             // $render = "<tr>
        //             //     <th style='color:#fff !important'>Kode kodokter</th>
        //             //     <th style='color:#fff !important'>Nama Dokter</th>
        //             //     <th style='color:#fff !important'>Kode RS</th>
        //             // </tr>";
        //             $render = ["Kode Dokter", "Nama Dokter", "Kode RS"];
        //             break;
        //         case "03" : 
        //             $table_title    = "Data Faskes";
        //             $total_data     = $this->pcare->get_data("bpjs_pcare_faskes")->num_rows();
        //             // $render = "<tr>
        //             //     <th style='color:#fff !important'>Kode PPK</th>
        //             //     <th style='color:#fff !important'>Nama PPK</th>
        //             //     <th style='color:#fff !important'>Alamat</th>
        //             //     <th style='color:#fff !important'>Telpon</th>
        //             //     <th style='color:#fff !important'>Kelas</th>
        //             //     <th style='color:#fff !important'>Kapasitas</th>
        //             // </tr>";
        //             $render = ["Kode PPK", "Nama PPK", "Alamat", "Telepon", "Kelas", "Kapasitas"];
        //             break;
        //     }

        //     echo json_encode(["header" => $render, "total" => $total_data, "title" => $table_title], JSON_UNESCAPED_SLASHES);
        // }

        // public function data($param){
        //     $_dtstart   = $this->input->post("start");
        //     $_dtlength  = $this->input->post("length");
        //     $_dtsearch  = $_POST["search"]["value"];

        //     // SEND REQUEST DATATABLE FROM FILTER
        //     $_dtreq     = (object) ["search" =>  $_dtsearch, "start" => $_dtstart, "length" => $_dtlength ];

        //     switch($param){
        //         case "01" : 
        //             $_dbcolumns = ["kdDiag", "nmDiag"];
        //             $_query     = $this->pcare->get_data("bpjs_pcare_diagnosa", $_dtreq, $_dbcolumns);
        //             $_queryt    = $this->pcare->get_data("bpjs_pcare_diagnosa");
        //             $_data      = [];

        //             foreach($_query->result() as $q){
        //                 $_data[]    = [
        //                     $q->kdDiag,
        //                     $q->nmDiag
        //                 ];
        //             }
        //             break;
        //         case "02" : 
        //             $_dbcolumns = ["kdDokter", "nmDokter", "kodeRs"];
        //             $_query     = $this->pcare->get_data("bpjs_pcare_dokter", $_dtreq, $_dbcolumns);
        //             $_queryt    = $this->pcare->get_data("bpjs_pcare_dokter");
        //             $_data      = [];

        //             foreach($_query->result() as $q){
        //                 $_data[]    = [
        //                     $q->kdDokter,
        //                     $q->nmDokter,
        //                     $q->kodeRs
        //                 ];
        //             }
        //             break;
        //         case "03" : 
        //             $_dbcolumns = ["kdppk", "nmppk", "alamatPpk", "telpPpk", "kelas", "kapasitas"];
        //             $_query     = $this->pcare->get_data("bpjs_pcare_faskes", $_dtreq, $_dbcolumns);
        //             $_queryt    = $this->pcare->get_data("bpjs_pcare_faskes");
        //             $_data      = [];

        //             foreach($_query->result() as $q){
        //                 $_data[]    = [
        //                     $q->kdppk,
        //                     $q->nmppk,
        //                     $q->alamatPpk,
        //                     $r->telpPpk,
        //                     $r->kelas,
        //                     $r->kapasitas
        //                 ];
        //             }
        //             break;
        //     }

        //     $render = [
        //         "draw" => $_POST["draw"],
        //         "recordsTotal" => $_queryt->num_rows(),
        //         "recordsFiltered" => $_query->num_rows(),
        //         "data"  => $_data
        //     ];

        //     echo json_encode($render, JSON_UNESCAPED_SLASHES);
        // }

    }
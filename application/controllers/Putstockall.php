<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    Class Putstockall Extends CI_Controller{
        
        Public function __construct(){
            parent::__construct();
        }

        Public function index(){

            $cabang         = $this->db->query("SELECT * FROM tbl_namers");
            $gudang         = $this->db->query("SELECT * FROM tbl_depo");

            $data["cabang"] = $cabang->result();
            $data["gudang"] = $gudang->result();

            $this->load->view("v_putstockall", $data);
        }

        Public function save(){

            // Values
            $cabang         = $this->input->post("cabang");
            $gudang         = $this->input->post("gudang");
            $saldo          = 100;
            $tanggal        = date("Y-m-d");

            // Initializes
            $check_gudang_logistik  = $this->db->query("SELECT * FROM tbl_apostocklog 
            WHERE koders = '$cabang' AND gudang = '$gudang'");

            $show_all_barang        = $this->db->query("SELECT * FROM tbl_logbarang")->result();

            // Insert
            foreach($show_all_barang as $sabkey => $sabval){
                $query = $this->db->query("INSERT INTO tbl_apostocklog (koders,kodebarang,gudang,terima,saldoakhir,periodedate) 
                VALUES ('$cabang','$sabval->kodebarang','$gudang','$saldo','$saldo','$tanggal')");
            }

            $this->db->query("INSERT INTO tbl_putstockall (cabang,gudang) VALUES ('$cabang','$gudang')");
        }

        Public function savef(){
            // Values
            $cabang         = $this->input->post("cabang");
            $gudang         = $this->input->post("gudang");
            $saldo          = 100;

            // Initializes
            // $check_gudang_logistik  = $this->db->query("SELECT * FROM tbl_barangstock 
            // WHERE koders = '$cabang' AND gudang = '$gudang'");

            $show_all_barang        = $this->db->query("SELECT * FROM tbl_barang")->result();

            // Insert
            foreach($show_all_barang as $sabkey => $sabval){
                $query = $this->db->query("INSERT INTO tbl_barangstock (koders,kodebarang,gudang,terima,saldoakhir) 
                VALUES ('$cabang','$sabval->kodebarang','$gudang','$saldo','$saldo')");
            }

            $this->db->query("INSERT INTO tbl_putstockall (cabang,gudang) VALUES ('$cabang','$gudang')");
        }

    }
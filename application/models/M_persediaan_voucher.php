<?php

    defined("BASEPATH") or exit("No deirect script access allowed");

    Class M_persediaan_voucher extends CI_Model{

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function listvoucher(){
            $unit   = $this->session->userdata("unit");
            return $this->db->query("SELECT * FROM tbl_vocd WHERE tglkirim LIKE '%". date("Y-m-d") ."%' AND koders = '$unit' ORDER BY id DESC");
        }

        public function listbycabang($param){
            if($param == "all"){
                return $this->db->query("SELECT * FROM tbl_vocd WHERE ORDER BY id DESC");
            } else {
                return $this->db->query("SELECT * FROM tbl_vocd WHERE cabangvoc = '$param' AND ORDER BY id DESC");
            }
        }

        public function filter($dari_Periode, $ke_periode){
            $unit   = $this->session->userdata("unit");
            return $this->db->query("SELECT * FROM tbl_vocd WHERE tglkirim BETWEEN '$dari_Periode' AND '$ke_periode' AND koders = '$unit' ORDER BY id DESC");
        }

    }
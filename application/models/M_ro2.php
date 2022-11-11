<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class M_ro2 extends CI_Model{

        private $_view_erad = "view_order_rad";

        public function get_list_eorder($date = ""){
            $unit   = $this->session->userdata("unit");

            if($date == ""){
                $query  = "SELECT 
                a.koders AS koders, 
                a.orderno AS orderno, 
                a.noreg AS noreg, 
                a.rekmed AS rekmed, 
                a.tglorder AS tglorder, 
                a.jamorder AS jamorder, 
                a.kodokter AS kodokter, 
                a.ordernote AS ordernote, 
                a.asal AS asal, 
                a.lab AS lab, 
                a.namapas AS namapas, 
                a.preposisi AS preposisi, 
                c.nadokter AS nadokter, 
                a.labonproses AS labonproses, 
                a.labok AS labok 
                FROM tbl_orderperiksa AS a 
                LEFT JOIN tbl_pasien ON a.rekmed = a.rekmed 
                LEFT JOIN dokter ON a.kodokter = c.kodokter 
                WHERE a.radio = 1 
                AND a.radiook = 0 
                AND a.koders = '$unit'";

                return $this->db->query($query);
            } else {
                //
            }
        }

        public function get_list_order($date = ""){
            //
        }

    }
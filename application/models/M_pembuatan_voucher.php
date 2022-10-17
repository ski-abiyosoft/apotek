<?php

    Class M_pembuatan_voucher extends CI_Model{
        public function __construct(){
            parent::__construct();
            $this->load->database();
	    }

        public function voucher_list($unit, $period){
            return $this->db->query("SELECT a.id, a.cabangvoc, a.tglkirim, a.nokir, a.cust_id, a.user_id, b.koders, b.namars, c.cust_id, c.cust_nama FROM tbl_voch AS a LEFT JOIN tbl_namers AS b ON b.koders = a.cabangvoc LEFT JOIN tbl_penjamin AS c ON c.cust_id = a.cust_id WHERE a.cabangvoc = '$unit' AND a.tglkirim LIKE '%". date("Y-m-d") ."%' ORDER BY a.id DESC");
        }

        public function get_byid($nokir){
            return $this->db->query("SELECT * FROM tbl_vocd WHERE nokir = '$nokir' ORDER BY id ASC");
        }

        public function get_by_konsul($cust_id = 0, $novoucher = 0){
            return $this->db->query("SELECT a.novoucher, a.nominal, a.nokir, b.nokir, b.cust_id FROM tbl_vocd AS a LEFT JOIN tbl_voch AS b ON b.nokir = a.nokir WHERE b.cust_id = '$cust_id' AND a.novoucher = '$novoucher' LIMIT 1");
        }

        public function get_by_penjualan($novoucher = 0){
            return $this->db->query("SELECT * FROM tbl_vocd WHERE novoucher = '$novoucher' LIMIT 1");
        }

        public function filter($unit, $dari_Periode, $ke_periode){
            return $this->db->query("SELECT a.id, a.cabangvoc, a.tglkirim, a.nokir, a.cust_id, a.user_id, b.koders, b.namars, c.cust_id, c.cust_nama FROM tbl_voch AS a LEFT JOIN tbl_namers AS b ON b.koders = a.cabangvoc LEFT JOIN tbl_penjamin AS c ON c.cust_id = a.cust_id WHERE a.cabangvoc = '$unit' AND tglkirim BETWEEN '$dari_Periode' AND '$ke_periode' ORDER BY a.id DESC");
        }

        // public function save(){
        //     $koders = $this->input->post("cabang");
        //     $cabangvoc = $this->input->post("cabang");
        //     // $nokir = $this->input->post("hidenokirim");
        //     $ket = $this->input->post("keterangan");
        //     $tglkirim = $this->input->post("tanggal");
        //     $cust_id = $this->input->post("jenisvouc");
        //     $novoucher = $this->input->post("novoucher");
        //     $nominal = $this->input->post("nominal");

        //     // var_dump($nokir);die;
            
        //     $this->db->query("INSERT INTO tbl_voch (koders,cabangvoc,nokir,ket,tglkirim,cust_id,user_id) VALUE ('$koders','$cabangvoc','$ket','$tglkirim','$cust_id','". $this->session->userdata("username") ."')");
        //     foreach($novoucher as $nkey => $nval){
        //         $this->db->query("INSERT INTO tbl_vocd (koders,nokir,novoucher,tglkirim,cabangvoc,nominal) VALUES ('$koders','$nokir','". $nval ."','$tglkirim','$cabangvoc','". $nominal[$nkey] ."')");
        //     }
        // }
    }
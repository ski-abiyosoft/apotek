<?php

    defined("BASEPATH") or exit ("No direct script access allowed");

    Class M_ro2 extends CI_Model{

        protected $_dokter      = "dokter";
        protected $_petugas     = "tbl_petugas";
        protected $_view_erad   = "view_order_rad";
        protected $_hradio      = "tbl_hradio";
        protected $_dradio      = "tbl_dradio";
        protected $_alkes       = "tbl_alkestransaksi";
        protected $_expertise   = "tbl_expertise";
        protected $_dradiofile  = "tbl_dradiofile";

        protected $_op          = "tbl_orderperiksa";
        protected $_dop         = "tbl_eradio";

        public function get_list_eorder($date = ""){
            $unit   = $this->session->userdata("unit");
            $daten  = date("Y-m-d");

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
                    a.radio AS radio, 
                    a.labonproses AS labonproses, 
                    a.radiook AS radiook, 
                    a.labok AS labok 
                    FROM tbl_orderperiksa AS a 
                    WHERE a.radio = 1 
                    AND a.radiook = 0 
                    AND a.koders = '$unit' 
                    AND a.tglorder LIKE '%$daten%' 
                    ORDER BY a.id DESC";

                return $this->db->query($query);
            } else {
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
                    a.radio AS radio, 
                    a.labonproses AS labonproses, 
                    a.radiook AS radiook, 
                    a.labok AS labok 
                    FROM tbl_orderperiksa AS a 
                    WHERE a.radio = 1 
                    AND a.radiook = 0 
                    AND a.koders = '$unit' 
                    AND a.tglorder BETWEEN '$date->start' AND '$date->end' 
                    ORDER BY a.id DESC";

                return $this->db->query($query);
            }
        }

        public function get_list_order($date = ""){
            $unit   = $this->session->userdata("unit");
            $daten  = date("Y-m-d");

            if($date == ""){
                $query  = "SELECT 
                    koders, 
                    noradio, 
                    tglradio, 
                    jam, 
                    noreg, 
                    rekmed, 
                    namapas, 
                    drpengirim, 
                    asal, 
                    orderno 
                    FROM $this->_hradio 
                    WHERE koders = '$unit'
                    AND tglradio LIKE '%$daten%' 
                    ORDER BY id DESC";

                return $this->db->query($query);
            } else {
                $query  = "SELECT 
                    koders, 
                    noradio, 
                    tglradio, 
                    jam, 
                    noreg, 
                    rekmed, 
                    namapas, 
                    drpengirim, 
                    asal, 
                    orderno 
                    FROM $this->_hradio
                    WHERE koders = '$unit'
                    AND tglradio BETWEEN '$date->start' AND '$date->end' 
                    ORDER BY id DESC";

                return $this->db->query($query);
            }
        }

        public function count_eorder(){
            $unit   = $this->session->userdata("unit");

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
                a.radio AS radio, 
                a.labonproses AS labonproses, 
                a.radiook AS radiook, 
                a.labok AS labok 
                FROM tbl_orderperiksa AS a 
                WHERE a.radio = 1 
                AND a.radiook = 0 
                AND a.koders = '$unit' 
                ORDER BY a.id DESC";

            return $this->db->query($query);
        }

        public function count_order(){
            $unit   = $this->session->userdata("unit");
            $query  = "SELECT 
                koders, 
                noradio, 
                tglradio, 
                jam, 
                noreg, 
                rekmed, 
                namapas, 
                drpengirim 
                FROM $this->_hradio 
                WHERE koders = '$unit'
                ORDER BY id DESC";

            return $this->db->query($query);
        }

        public function get_dokter1($unit, $poli = ""){
            if($poli == ""){
                return $this->db->query("SELECT * FROM $this->_dokter WHERE koders = '$unit' GROUP BY kodokter");
            } else {
                return $this->db->query("SELECT * FROM $this->_dokter WHERE koders = '$unit' AND kopoli = '$poli'");
            }
        }

        public function dokter_radio($unit){
            return $this->db->query("SELECT * FROM $this->_dokter WHERE koders = '$unit' AND kopoli = 'RADIO'");
        }

        public function petugas(){
            return $this->db->query("SELECT * FROM $this->_petugas WHERE kodepos = 'RADIO'");
        }

        public function data_eorder($param, $unit){
            return $this->db->query("SELECT * FROM $this->_op WHERE orderno = '$param' AND koders = '$unit'");
        }

        public function data_order($param){
            return $this->db->query("SELECT * FROM $this->_hradio WHERE noradio = '$param'");
        }

        public function data_eorder_detail($param){
            return $this->db->query("SELECT * FROM $this->_dop WHERE notr = '$param'");
        }

        public function tindakan(){
            $unit   = $this->session->userdata("unit");

            return $this->db->query("SELECT CONCAT('[ ', kodetarif ,' ] - [ ', tindakan ,' ]') AS text, kodetarif AS kodeid FROM daftar_tarif_nonbedah WHERE kodepos = 'RADIO' AND koders = '$unit' ORDER BY tindakan ASC");
        }

        public function billing($noradio){
            return $this->db->query("SELECT * FROM $this->_dradio WHERE noradio = '$noradio'");
        }

        public function bhp($noradio){
            return $this->db->query("SELECT * FROM $this->_alkes WHERE notr = '$noradio'");
        }

        public function get_expertise($noradio){
            return $this->db->query("SELECT * FROM $this->_expertise WHERE noradio = '$noradio'");
        }

        public function get_files($noradio){
            return $this->db->query("SELECT * FROM $this->_dradiofile WHERE noradio = '$noradio'");
        }

        public function get_files_byid($id){
            return $this->db->query("SELECT * FROM $this->_dradiofile WHERE id = '$id'");
        }

        /**/

        public function expertise_check($key){
            return $this->db->query("SELECT * FROM $this->_expertise WHERE noradio = '$key'");
        }

        public function expertise_save($data){
            return $this->db->insert($this->_expertise, $data);
        }

        public function expertise_update($data, $key){
            return $this->db->update($this->_expertise, $data, array("noradio" => $key));
        }

        public function save($data){
            return $this->db->insert($this->_hradio, $data);
        }

        public function update($data, $key){
            return $this->db->update($this->_hradio, $data, array("noradio" => $key));
        }

        public function delete_file($id){
            return $this->db->delete($this->_dradiofile, array("id" => $id));
        }

    }
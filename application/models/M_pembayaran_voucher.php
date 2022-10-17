<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    Class M_pembayaran_voucher extends CI_Model{

        public function __construct(){
            parent::__construct();
            $this->load->database();
        }

        public function list($unit){
            return $this->db->query("SELECT a.koders, a.nokwitansi, a.nojual, a.rekmed, a.namauser, b.nojual, (SUM(b.netrp)) AS total, c.rekmed, c.namapas 
            FROM tbl_vocjualh AS a 
            LEFT JOIN tbl_vocjual AS b ON b.nojual = a.nojual
            LEFT JOIN tbl_pasien AS c ON c.rekmed = a.rekmed
            WHERE (a.koders = '$unit') 
            AND a.tgljual LIKE '%". date("Y-m-d") ."%'
            GROUP BY a.nojual");
        }

        public function get_penjualan_header($nojual){
            return $this->db->query("SELECT * FROM tbl_vocjualh WHERE nojual = '$nojual'");
        }

        public function get_penjualan_detail($nojual){
            return $this->db->query("SELECT nojual, novoucher, nominal, diskon, netrp FROM tbl_vocjual WHERE nojual = '$nojual'");
        }

        public function get_info_pasien($rekmed){
            return $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$rekmed'");
        }

        public function get_info_kasir($nokwitansi){
            return $this->db->query("SELECT * FROM tbl_kasir WHERE nokwitansi = '$nokwitansi'");
        }

        public function get_info_kartukredit($nokwitansi){
            return $this->db->query("SELECT a.*, b.* FROM tbl_kartukredit AS a LEFT JOIN tbl_edc AS b ON b.bankcode = a.kodebank WHERE a.nokwitansi = '$nokwitansi'");
        }

        public function save(){
            $unit           = $this->session->userdata("unit");
            $username       = $this->session->userdata("username");

            $voucher_arr    = array();

            $notr           = $this->input->post("hidenotr");
            $billno         = $this->input->post("hidebillno");
            $tanggal        = $this->input->post("tanggal");
            $nomr           = $this->input->post("nomr");
            $namapas        = $this->input->post("namapas");
            $novoucher      = $this->input->post("novoucher");
            $nominal        = str_replace(",", "", $this->input->post("nominal"));
            $diskon         = $this->input->post("diskon");
            $netrp          = str_replace(",", "", $this->input->post("netrp"));
            $diskonrp       = str_replace(",", "", $this->input->post("diskonrp"));
            $totalrp        = str_replace(",", "", $this->input->post("totalrp"));
            $totaltunai     = $this->input->post("totaltunai");
            $uangpasien     = str_replace(",", "", $this->input->post("uangpasien"));
            $bayarbank      = $this->input->post("bayar_bank");
            $bayartipe      = $this->input->post("bayar_tipe");
            $bayarnokartu   = $this->input->post("bayar_nokartu");
            $bayartrvalid   = $this->input->post("bayar_trvalid");
            $bayarnilai     = $this->input->post("bayar_nilai");
            $bayaradm       = $this->input->post("bayar_adm");
            $bayartotal     = $this->input->post("bayar_total");
            $totalelec      = str_replace(",", "", $this->input->post("totalelec"));

            $jambayar       = date("H:i:s");
            $posbayar       = "VOUCHER";

            $keterangan     = "Pembayaran Pembelian Voucher". $namapas;


            if($this->input->post("kembalian") == "0.00"){
                $kembalian  = str_replace(".", "", $this->input->post("kembalian"));
            } else {
                $kembalian  = str_replace(",", "", $this->input->post("kembalian"));
            }

            // Convert Voucher
            foreach($novoucher as $nv){
                $voucher_arr[] = $nv;
                // $this->db->query("UPDATE tbl_vocd SET tgjual = '$tanggal', diskon = '". $diskon[$nv] ."', rekmed = '$nomr', nokwitansi = '$billno', terjual = '1' WHERE novoucher = '$nv'");
            }
            
            // Insert Voucher + VOCJUAL DETAIL
        
            foreach($voucher_arr as $nvkey => $nvval){
                $this->db->query("INSERT INTO tbl_vocjual (nojual,novoucher,nominal,diskon,netrp) VALUES ('$notr','$nvval','". $nominal[$nvkey]. "','". $diskon[$nvkey] ."','". $netrp[$nvkey] ."')");
                $this->db->query("UPDATE tbl_vocd SET tgjual = '$tanggal', diskon = '". $diskon[$nvkey] ."', rekmed = '$nomr', nokwitansi = '". $billno ."', terjual = '1' WHERE novoucher = '$nvval'");
            }

            // Insert Kasir
            $this->db->query("INSERT INTO tbl_kasir (koders,nokwitansi,rekmed,tglbayar,jambayar,totalsemua,diskonrp,jenisbayar,bayarcash,bayarcard,totalbayar,kembali,posbayar,namapasien,username)
            VALUES ('$unit','$billno','$nomr','$tanggal','$jambayar','$totalrp','$diskonrp','1','$totaltunai','$totalelec','$uangpasien','$kembalian','$posbayar','$namapas','$username')
            ");

            // Insert VOCJUAL HEADER
            $this->db->query("INSERT INTO tbl_vocjualh (koders,nojual,tgljual,rekmed,nokwitansi,keterangan,namauser) VALUES ('$unit','$notr','$tanggal','$nomr','$billno','$keterangan','$username')");

            /*
            $admrp  = ($bayaradm/100) * $bayartotal;

            if($bayarbank != ""){
                $this->db->query("INSERT INTO tbl_kartukredit (koders,nokwitansi,nocard,nootorisasi,tanggal,jumlahbayar,admpersen,admrp,totalcardrp,kodebank) 
                VALUES ('$unit','$billno','$bayarnokartu','$bayartrvalid','$tanggal','$bayarnilai','$bayaradm','$admrp','$bayartotal','$bayarbank')");
            }
            */

            // Insert DC/CC
            if($bayarbank != ""){
                $jumdata_bayar    = count($bayarbank);
            
                for($i=0;$i<=$jumdata_bayar-1;$i++){
                    $total  = str_replace(',','',$bayarnilai[$i]);
                    $adm    = str_replace(',','',$bayaradm[$i]);
                    $gtotal = str_replace(',','',$bayartotal[$i]);
                    $nocard    = str_replace(',','',$bayarnokartu[$i]);
                    $apprv = str_replace(',','',$bayartrvalid[$i]);
                    $bbank = $bayarbank[$i];
                    $typeb = $bayartipe[$i];
                    $admrp  = ($adm/100) * $total;
                    
                    $this->db->query("INSERT INTO tbl_kartukredit (koders,nokwitansi,nocard,nootorisasi,tanggal,jenisbayar,jumlahbayar,admpersen,admrp,totalcardrp,kodebank) 
                    VALUES ('$unit','$billno','$nocard','$apprv','$tanggal','$typeb','$total','$adm','$admrp','$gtotal','$bbank')");				
                }		
            }
        }

    }
<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    Class Pembayaran_voucher extends CI_Controller{

        public function __construct(){
            parent::__construct();
            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2305');
            $this->load->model(array("M_pembayaran_voucher", "M_global", "M_pembuatan_voucher"));
            $this->load->helper(array("app_global","simkeu_nota1","simkeu_nota"));
        }

        public function index(){
            $cek             = $this->session->userdata('level');
            $unit            = $this->session->userdata('unit');
            $period          = date("Y")."-".date("m")."-";
            $bulan           = $this->M_global->_periodebulan();
            $nbulan   	     = $this->M_global->_namabulan($bulan);
            
            $data["periode"] = 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();
            $data["listv"]   = $this->M_pembayaran_voucher->list($unit)->result();
            $data["unit"]    = $unit;

            if(empty($cek)){
                redirect("/");
            } else {
                $this->load->view("klinik/v_pembayaran_voucher", $data);
            }
        }

        public function entri(){
            $unit = $this->session->userdata('unit');
            $cek  = $this->session->userdata("level");
            
            if(empty($cek)){
                redirect("/");
            } else {
                $this->load->view("klinik/v_pembayaran_voucher_add");
            }
        }

        public function edit($param){
            $cek              = $this->session->userdata("level");
            $unit             = $this->session->userdata('unit');
            
            $cek_penjualan    = $this->M_pembayaran_voucher->get_penjualan_header($param);
            $get_penjualan    = $this->M_pembayaran_voucher->get_penjualan_header($param)->row();

            $detail_penjualan = $this->M_pembayaran_voucher->get_penjualan_detail($get_penjualan->nojual)->result();
            $pasien_penjualan = $this->M_pembayaran_voucher->get_info_pasien($get_penjualan->rekmed)->row();
            $kasir_penjualan  = $this->M_pembayaran_voucher->get_info_kasir($get_penjualan->nokwitansi)->row();
            $kredit_penjualan = $this->M_pembayaran_voucher->get_info_kartukredit($get_penjualan->nokwitansi)->result();
            
            $data["header"]   = $get_penjualan;
            $data["listv"]    = $detail_penjualan;
            $data["pasien"]   = $pasien_penjualan;
            $data["kasir"]    = $kasir_penjualan;
            $data["kartu"]    = $kredit_penjualan;

            if(empty($cek)){
                redirect("/");
            } else {
                if($cek_penjualan->num_rows() == 0){
                    redirect(base_url()."pembayaran_voucher");
                }
                $this->load->view("klinik/v_pembayaran_voucher_edit", $data);
            }
        }

        public function getpasien($param){
            $pasien = $this->M_global->getpvpasien($param);
            if($pasien->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                echo json_encode($pasien->row());
            }
        }

        public function getvoucher($param){
            $voucher = $this->M_pembuatan_voucher->get_by_penjualan($param);
            if($voucher->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                echo json_encode($voucher->row());
            }
        }

        public function get_last_number($jenis_urut){
            $unit = $this->session->userdata("unit");
            echo json_encode(array(
                "lastno" => temp_urut_transaksi($jenis_urut, $unit, 19)
            ));
        }

        public function get_recent_number($jenis_urut){
            $unit = $this->session->userdata("unit");
            echo json_encode(array(
                "notr" => recent_urut_transaksi($jenis_urut, $unit, 19)
            ));
        }

        public function save(){
            $query_save = $this->M_pembayaran_voucher->save();
            /*$this->db->query("INSERT INTO tbl_vocjualh (koders, njual, tgljual, rekmed, nokwitansi, keterangan, namauser) VALUES ('$unit','$notr','$tanggal','$nomr','$billno','','$username')");
            foreach($novoucher as $nvkey => $nvval){
                $this->db->query("INSERT INTO tbl_vocjual (nojual, novoucher, nominal, diskon, netrp) VALUES ('$notr','$nvval','". $nominal[$nvkey]. "','". $diskon[$nvkey]. "','". $netrp[$nvkey]. "')");
                $this->db->query("UPDATE tbl_vocd SET tgjual = '$tangal', rekmed = '$nomr', nokwitansi = '$billno', tejual = '0' WHERE novoucher = '$nvval'");
            }*/
            urut_transaksi("JUAL_VOC", 19);
            urut_transaksi("URUTKWT", 19);
        }

        public function update(){
            $unit           = $this->session->userdata("unit");
            $username       = $this->session->userdata("username");

            $voucher_arr    = array();

            $hidenotr       = $this->input->post("hidenotr");
            $notr           = $this->input->post("notr");
            $hidebillno     = $this->input->post("hidebillno");
            $billno         = $this->input->post("billno");
            $tangal         = $this->input->post("tanggal");
            $nomr           = $this->input->post("nomr");
            $namapas        = $this->input->post("namapas");
            $novoucher      = $this->input->post("novoucher");
            $nominal        = str_replace(",", "", $this->input->post("nominal"));
            $diskon         = $this->input->post("diskon");
            $netrp          = str_replace(",", "", $this->input->post("netrp"));
            $diskonrp       = str_replace(",", "", $this->input->post("diskonrp"));
            $totalrp        = str_replace(",", "", $this->input->post("totalrp"));
            $totaltunai     = str_replace(",", "", $this->input->post("totaltunai"));
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

            $keterangan     = "Pembayaran Pembelian Voucher ". $namapas;

            if($this->input->post("kembalian") == "0.00"){
                $kembalian  = str_replace(".", "", $this->input->post("kembalian"));
            } else {
                $kembalian  = str_replace(",", "", $this->input->post("kembalian"));
            }

            foreach($novoucher as $nv){
                $voucher_arr[] = $nv;
                // $this->db->query("UPDATE tbl_vocd SET tgjual = '$tanggal', diskon = '". $diskon[$nv] ."', rekmed = '$nomr', nokwitansi = '$billno', terjual = '1' WHERE novoucher = '$nv'");
            }
            
            // Insert Voucher + VOCJUAL DETAIL
        
            foreach($voucher_arr as $nvkey => $nvval){
                // $this->db->query("INSERT INTO tbl_vocjual (nojual,novoucher,nominal,diskon,netrp) VALUES ('$notr','$nvval','". $nominal[$nvkey]. "','". $diskon[$nvkey] ."','". $netrp[$nvkey] ."')");
                $this->db->query("UPDATE tbl_vocd SET tgjual = '$tanggal', diskon = '". $diskon[$nvkey] ."', rekmed = '$nomr', nokwitansi = '". $hidebillno ."', terjual = '1' WHERE novoucher = '$nvval'");
                $this->db->query("UPDATE tbl_vocjual SET nojual = '$hidenotr'WHERE novoucher = '$nvval'");
            }

            // Update Kasir
            $this->db->query("UPDATE tbl_kasir SET totalsemua = '$totalrp', diskonrp = '$diskonrp', bayarcash = '$totaltunai', bayarcard = '$totalelec', totalbayar= '$uangpasien', kembali = '$kembalian', username = '$username' WHERE nokwitansi = '$billno'");
            // $this->db->query("INSERT INTO tbl_kasir (koders,nokwitansi,rekmed,tglbayar,jambayar,totalsemua,diskonrp,jenisbayar,bayarcash,bayarcard,totalbayar,kembali,posbayar,namapasien,username)
            // VALUES ('$unit','$billno','$nomr','$tanggal','$jambayar','$totalrp','$diskonrp','1','$totaltunai','$totalelec','$uangpasien','$kembalian','$posbayar','$namapas','$username')
            // ");

            // Insert VOCJUAL HEADER
            // $this->db->query("INSERT INTO tbl_vocjualh (koders,nojual,tgljual,rekmed,nokwitansi,keterangan,namauser) VALUES ('$unit','$notr','$tanggal','$nomr','$billno','$keterangan','$username')");

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

        public function hapus($param){
            $unit         = $this->session->userdata("unit");
            $rand         = rand(100000,999999);
            $cek          = $this->session->userdata("level");

            $header       = $this->db->query("SELECT * FROM tbl_vocjualh WHERE nojual = '$param'");
            $detail       = $this->db->query("SELECT * FROM tbl_vocjual WHERE nojual = '$param'");
            $info_voucher = $detail->result();
            $voch         = $header->row();
            if($header->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                foreach($info_voucher as $ivkey => $ivval){
                    $this->db->query("UPDATE tbl_vocd SET tgjual = '', diskon = '', rekmed = '', nokwitansi = '', terjual = '' WHERE novoucher = '$ivval->novoucher'");
                }
                $this->db->query("DELETE FROM tbl_vocjual WHERE nojual = '$param'");
                $this->db->query("DELETE FROM tbl_kasir WHERE nokwitansi = '$voch->nokwitansi'");
                $this->db->query("DELETE FROM tbl_kartukredit WHERE nokwitansi = '$voch->nokwitansi'");
                $this->db->query("DELETE FROM tbl_vocjualh WHERE nojual = '$param'");
                echo json_encode(array("status" => 1));
            }
        }

        public function hapus_voucher($param){
            $unit         = $this->session->userdata("unit");
            $rand         = rand(100000,999999);
            $nokwitansi   = $this->input->post("nokwitansi");
            $info_voucher = $this->db->query("SELECT * FROM tbl_vocjual WHERE novoucher = '$param'")->row();
            if($info_voucher){
                $this->db->query("UPDATE tbl_vocd SET tgjual = '', diskon = 0, rekmed = NULL, nokwitansi = NULL, terjual = 0 WHERE novoucher = '$info_voucher->novoucher'");
                $this->db->query("UPDATE tbl_kasir SET totalsemua = totalsemua-$info_voucher->netrp, diskonrp = diskonrp-$info_voucher->diskon, kembali = kembali+$info_voucher->netrp WHERE nokwitansi = '$nokwitansi'");
                $this->db->query("DELETE FROM tbl_vocjual WHERE novoucher = '$param'");
                echo json_encode(array("status" => 1));
            } else {
                echo json_encode(array("status" => 0));
            }
        }

        public function hapus_kartu_kredit($param){
            $unit       = $this->session->userdata("unit");
            $rand       = rand(100000,999999);
            $nokwitansi = $this->input->post("nokwitansi");
            $info_kartu = $this->db->query("SELECT * FROM tbl_kartukredit WHERE nocard = '$param' AND nokwitansi = '$nokwitansi'")->row();
            // $query_update_kartu = $this->db->query("UPDATE tbl_kartukredit SET nokwitansi = 'deleted". $rand.$unit ."' WHERE nokwitansi = '$nokwitansi'");
            if($info_kartu){
                $this->db->query("UPDATE tbl_kasir SET bayarcard = bayarcard-$info_kartu->totalcardrp, totalbayar = totalbayar-$info_kartu->totalcardrp, kembali = totalbayar-$info_kartu->totalcardrp WHERE nokwitansi = '$nokwitansi'");
                $this->db->query("UPDATE tbl_kartukredit SET jumlahbayar = '0', admpersen = '0', admrp = '0', totalcardrp = '0' WHERE nokwitansi = '$nokwitansi' AND nocard = '$param'");
                echo json_encode(array("status" => 1));
            } else {
                echo json_encode(array("status" => 0));
            }
        }

        public function cetak(){
            $notrx = $this->input->get("notransaksi");
            $check_pembayaran = $this->db->query("SELECT * FROM tbl_vocjualh WHERE nojual = '$notrx'");
            if($check_pembayaran->num_rows() == 0){
                $this->session->set_flashdata("not_found", "Voucher Tidak Ditemukan");
                redirect("/pembayaran_voucher/");
            } else {
                $unit= $this->session->userdata('unit');	 

                $profile = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'")->row();

                $nama_usaha=$profile->namars;
                $alamat1   = $profile->alamat;
                $alamat2  = $profile->kota;

                $header = $check_pembayaran->row();

                $detail_cetak = $this->db->query("SELECT nojual, novoucher, nominal, diskon, netrp FROM tbl_vocjual WHERE nojual = '$header->nojual'");
                $pasien_cetak = $this->db->query("SELECT * FROM tbl_pasien WHERE rekmed = '$header->rekmed'");
                $kasir_cetak = $this->db->query("SELECT * FROM tbl_kasir WHERE nokwitansi = '$header->nokwitansi'");
                $kredit_cetak = $this->db->query("SELECT a.*, b.* FROM tbl_kartukredit AS a LEFT JOIN tbl_edc AS b ON b.bankcode = a.kodebank WHERE a.nokwitansi = '$header->nokwitansi'");

                $detail = $detail_cetak->row();
                $pasien = $pasien_cetak->row();
                $kasir = $kasir_cetak->row();
                $kredit = $kredit_cetak->row();

                $pdf=new simkeu_nota();
                
                $pdf->setID($nama_usaha,$alamat1,$alamat2);
                $pdf->setfont('arial');
                $pdf->setjudul('');
                $pdf->setsubjudul('');
                $pdf->addpage("L","A4");   
                $pdf->setsize("L","A4");
                $pdf->ln(10);

                $pdf->SetWidths(array(40,5,145));
                $align  = array('L','C','L');
                $pdf->setfont('Arial','',10);
                $pdf->SetAligns(array('L','C','L'));
                $fc = array('0','0','0');
                $pdf->FancyRow(array('No Kwitansi',':',$header->nokwitansi),0,0, $align);
                $pdf->FancyRow(array('Tanggal',':',str_replace(" 00:00:00", "", $header->tgljual)),0,0, $align);
                $pdf->FancyRow(array('Sudah Diterima Dari',':',$pasien->namapas),0,0, $align);
                $pdf->FancyRow(array('Uang Sebesar',':',ucwords($this->M_global->terbilang($kasir->totalsemua))),0,0, $align);
                $pdf->FancyRow(array('Untuk Pembayaran',':',$header->keterangan),0,0, $align);
                $pdf->FancyRow(array('Nama Pasien',':',$pasien->namapas),0,0, $align);
                $pdf->FancyRow(array('No. Rek Med',':',$header->rekmed),0,0, $align);
                $pdf->ln(5);

                $pdf->SetWidths(array(95,40));
                $align  = array('L','R');
                $border = array('B','B');
                $pdf->SetAligns(array('L','R'));
                $fc = array(0,0);
                $pdf->setfont('arial','', 18);
                $pdf->FancyRow(array('Rp',number_format($kasir->totalsemua, 0, ',', '.')),0,$border, $align);
                $pdf->ln(10);

                $pdf->SetWidths(array(95,95));
                $align  = array('C','C');
                $pdf->setfont('Arial','',10);
                $pdf->SetAligns(array('C','C'));
                $fc = array('0','0');
                $pdf->FancyRow(array('','Jakarta, '. date("d -m Y")),0,0, $align);
                $pdf->FancyRow(array('',str_replace(" dr. Affandi", "", $profile->namars)),0,0, $align);
                $pdf->ln(5);
                $pdf->FancyRow(array('Pembeli','Cashier'),0,0, $align);
                $pdf->ln(15);
                $pdf->FancyRow(array($pasien->namapas,$kasir->username),0,0, $align);
                $pdf->ln(15);

                //$pdf->SetWidths(array(190));
                //$pdf->SetFont('Arial','',9);
                //$pdf->SetAligns(array('L'));
                //$align  = array('L');
                //$pdf->FancyRow(array('Cetak '.date('d-m-Y').' Jam : '.date('H:i:s')),0,0, $align);
                // $pdf->FancyRow(array($user.' '.$header->jam),0,$border, $align);
            
                $pdf->SetTitle($notrx);
                $pdf->AliasNbPages();
                $pdf->output($notrx.'.PDF','I');			
		
            }
        }

        public function filter($param){
            $cek                = $this->session->userdata('level');
            $unit               = $this->session->userdata('unit');

            $period_length      = explode("~", $param);
            $dari_Periode       = date("Y-m-d", strtotime($period_length[0]));
            $ke_periode         = date("Y-m-d", strtotime($period_length[1]));
            $bulan_dari_priode  = date("n", strtotime($period_length[0]));
            $bulan              = $this->M_global->_periodebulan();

            $tahun1             =  explode("-", $period_length[0]);
            $tahun2             =  explode("-", $period_length[1]);

            $data["listv"]      = $this->db->query("SELECT a.koders, a.nokwitansi, a.nojual, a.rekmed, a.namauser, a.tgljual, b.nojual, (SUM(b.netrp)) AS total, c.rekmed, c.namapas 
            FROM tbl_vocjualh AS a 
            LEFT JOIN tbl_vocjual AS b ON b.nojual = a.nojual
            LEFT JOIN tbl_pasien AS c ON c.rekmed = a.rekmed
            WHERE (a.koders = '$unit') AND (a.tgljual BETWEEN '$dari_Periode' AND '$ke_periode')
            GROUP BY a.nojual")->result();
            
            $dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);
            $ke_bulan           = $this->M_global->_namabulan($bulan);
            $data["periode"]    = 'Periode '.$dari_bulan.'-'.$tahun1[0].' s/d '.$ke_bulan.'-'.$tahun2[0];
            if(!empty($cek)){
                $this->load->view("klinik/v_pembayaran_voucher", $data);
            } else {
                header("location:".base_url());
            }
        }

    }
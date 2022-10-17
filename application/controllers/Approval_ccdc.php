<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    Class Approval_ccdc Extends CI_Controller{
        
        public function __construct(){
            parent::__construct();
            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2308');
            $this->load->helper(array("app_global","simkeu_nota1","simkeu_nota"));
            $this->load->model(array("M_global"));
        }

        public function index(){
            $cek  = $this->session->userdata("level");
            $unit = $this->session->userdata("unit");

            $period          = date("Y")."-".date("m")."-";
            $bulan           = $this->M_global->_periodebulan();
            $nbulan   	     = $this->M_global->_namabulan($bulan);
            
            $data["periode"] = 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();
            $data["listv"]   = $this->db->query("SELECT * FROM tbl_mutasikas WHERE koders = '$unit' AND keterangan LIKE '%Approval Debit/Kredit%' AND tglmutasi LIKE '%". date("Y-m-d") ."%'")->result();
            $data["unit"]    = $unit;

            if(empty($cek)){
                redirect("/home");
            } else {
                $this->load->view("kasir/v_approval_ccdc", $data);
            }
        }

        public function entri(){
            $unit            = $this->session->userdata("unit");
            $cek             = $this->session->userdata("level");

            $cashier         = $this->db->query("SELECT uidlogin, username FROM userlogin ORDER BY username ASC");

            $data["cashier"] = $cashier->result();

            if(empty($cek)){
                header("location:". base_url());
            } else {
                $this->load->view("kasir/v_approval_ccdc_add.php", $data);
            }
        }

        public function query(){
            $unit        = $this->session->userdata("unit");
            $user        = $this->input->post("user");
            $dari_tgl    = $this->input->post("fromdate");
            $sampai_tgl  = $this->input->post("todate");
            $edc         = $this->input->post("edc");
            $shift       = $this->input->post("shift");

            // $check_kasir = $this->db->query("SELECT * FROM tbl_kasir WHERE username = '$user' AND koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$dari_tgl' AND '$sampai_tgl'");
            $check_kasir    = $this->db->query("SELECT * FROM tbl_kartukredit AS a
            LEFT JOIN tbl_kasir AS b ON b.nokwitansi = a.nokwitansi
            WHERE b.username = '$user' AND b.koders = '$unit' AND b.shipt = '$shift' AND b.tglbayar BETWEEN '$dari_tgl' AND '$sampai_tgl' AND a.kodebank = '$edc'");

            if($check_kasir->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                if(strpos($_SERVER["HTTP_REFERER"], "/result/") > 0){
                    redirect(base_url() ."approval_ccdc/result/?cashier=$user&from=$dari_tgl&to=$sampai_tgl&edc=$edc&shift=$shift");
                } else {
                    echo json_encode(array("direct" => "/approval_ccdc/result/?cashier=$user&from=$dari_tgl&to=$sampai_tgl&edc=$edc&shift=$shift"), JSON_UNESCAPED_SLASHES);
                }
            }
        }

        public function result(){
            $cek            = $this->session->userdata("level");
            $unit           = $this->session->userdata("unit");

            $cashier        = $this->input->get("cashier");
            $fromdate       = $this->input->get("from");
            $todate         = $this->input->get("to");
            $edc            = $this->input->get("edc");
            $shift          = $this->input->get("shift");

            if($cashier == "" || $fromdate == "" || $todate == "" || $shift == ""){
                redirect("/approval_ccdc");
            } else {
                if($shift == "--- Pilih Shift ---"){
                    redirect("/approval_ccdc/result/?cashier=$cashier&from=$fromdate&to=$todate&edc=$edc&shift=0");
                } else {

                    // $penerimaan     = $this->db->query("SELECT SUM(a.totalcardrp) AS total 
                    // FROM tbl_kartukredit AS a
                    // LEFT JOIN tbl_edc AS b ON b.bankcode = a.kodebank
                    // LEFT JOIN tbl_kasir AS c ON c.nokwitansi = a.nokwitansi
                    // WHERE c.username = '$cashier'
                    // AND c.tglbayar BETWEEN '$fromdate' AND '$todate'
                    // AND c.shipt = '$shift'
                    // AND a.kodebank = $edc
                    // AND a.cardtype IN (1,2,3)
                    // AND a.nomutasi = 0
                    // AND c.koders = '$unit'
                    // GROUP BY a.nokwitansi")->row();

                    // $penerimaan     = $this->db->query("SELECT SUM(b.totalcardrp) AS total FROM tbl_kasir AS a
                    // LEFT JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi
                    // LEFT JOIN tbl_edc AS c ON c.bankcode = b.kodebank
                    // WHERE a.username = '$cashier'
                    // AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
                    // AND a.shipt = '$shift'
                    // AND b.kodebank = '$edc'
                    // AND b.cardtype IN (1,2,3) 
                    // AND a.nomutasi = ''")->row();

                    // $daftar_penerimaan     = $this->db->query("SELECT a.nokwitansi, a.tglbayar, a.namapasien, b.nocard, c.namabank, SUM(b.totalcardrp) AS totalcardrp, b.nootorisasi FROM tbl_kasir AS a
                    // LEFT JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi
                    // LEFT JOIN tbl_edc AS c ON c.bankcode = b.kodebank
                    // WHERE a.username = '$cashier'
                    // AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
                    // AND a.shipt = '$shift'
                    // AND b.kodebank = '$edc'
                    // AND b.cardtype IN (1,2,3) 
                    // GROUP BY a.nokwitansi, b.nokwitansi");

                    $daftar_penerimaan      = $this->db->query("SELECT b.nokwitansi, a.tanggal AS tglbayar, a.cardtype, a.nocard, c.namabank, a.totalcardrp AS totalcardrp, a.nootorisasi 
                    FROM tbl_kartukredit AS a
                    LEFT JOIN tbl_kasir AS b ON b.nokwitansi = a.nokwitansi
                    LEFT JOIN tbl_edc AS c ON c.bankcode = a.kodebank
                    WHERE b.username = '$cashier'
                    AND a.tanggal BETWEEN '$fromdate' AND '$todate'
                    AND b.shipt = $shift
                    AND a.kodebank = $edc
                    AND a.cardtype IN (1,2,3)
                    AND a.nomutasi = 0
                    AND b.koders = '$unit'");

                    $data["username"]    = $this->session->userdata("username");
                    $data["listv"]       = $daftar_penerimaan->result();
                    // $data["jumlahrp"]    = $penerimaan->total;

                    if(!empty($cek)){
                        $data["shift"] = $shift;
                        $this->load->view("kasir/v_approval_ccdc_res", $data);
                    } else {
                        redirect("kasir/approval_ccdc");
                    }

                }
            }
        }

        public function get_last_number(){
            $unit = $this->session->userdata("unit");
            echo json_encode(array(
                "lastno" => temp_urut_transaksi("URUT_MUTASI", $unit, 19)
            ));
        }

        public function get_recent_number(){
            $unit = $this->session->userdata("unit");
            echo json_encode(array(
                "notr" => recent_urut_transaksi("URUT_MUTASI", $unit, 19)
            ));
        }

        public function save(){
            $unit            = $this->session->userdata("unit");
            $usernamesss     = $this->input->post("username");
            $shift           = $this->input->post("shift");
            $edc             = $this->input->post("kodebank");
            $tanggal         = date("Y-m-d");
            
            $nomutasi        = $this->input->post("hidenomutasi");
            $acdari          = $this->input->post("acdari");
            $acke            = $this->input->post("acke");
            $keterangan      = $this->input->post("keterangan");
            $jumlahmutasi    = str_replace(",", "", $this->input->post("jumlahmutasi"));

            $listpenerimaan  = $this->input->post("listpenerimaan");

            foreach($listpenerimaan as $lpkey => $lpval){
                $this->db->query("UPDATE tbl_kartukredit SET nomutasi = '$nomutasi' WHERE nokwitansi = '$lpval'");
            }

            $this->db->query("INSERT INTO tbl_mutasikas (koders,nomutasi,acdari,acke,keterangan,tglmutasi,mutasirp,username,bankcode,shipt)
            VALUES ('$unit','$nomutasi','$acdari','$acke','$keterangan','$tanggal','$jumlahmutasi','$usernamesss','$edc','$shift')");

            urut_transaksi("URUT_MUTASI", 19);
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

            $data["listv"]      = $this->db->query("SELECT * FROM tbl_mutasikas WHERE koders = '$unit' AND keterangan LIKE '%Approval Debit/Kredit%' AND tglmutasi BETWEEN '$dari_Periode' AND '$ke_periode'")->result();
            $dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);
            $ke_bulan           = $this->M_global->_namabulan($bulan);
            $data["periode"]    = 'Periode '.$dari_bulan.'-'.$tahun1[0].' s/d '.$ke_bulan.'-'.$tahun2[0];
            if(!empty($cek)){
                $this->load->view("kasir/v_approval_ccdc", $data);
            } else {
                header("location:".base_url());
            }
        }

        public function cetak($param){
            $query_check = $this->db->query("SELECT * FROM tbl_mutasikas WHERE nomutasi = '$param'");

            if($query_check->num_rows() == 0){
                redirect("/approval_ccdc");
            } else {
                $unit       = $this->session->userdata('unit');
                $user       = $this->session->userdata('username');

                $profile    = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'")->row();
                $nama_usaha = $profile->namars;
                $alamat1    = $profile->alamat;
                $alamat2    = $profile->kota;
                $cabang     = str_replace("KLINIK ESTETIKA dr. Affandi ", "", $profile->namars);

                // $userinfo   = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$user'")->row();

                $header     = $this->db->query("SELECT * FROM tbl_mutasikas WHERE nomutasi = '$param'")->row();
                
                $listmutasi     = $this->db->query("SELECT b.nokwitansi, b.tglbayar, CONCAT('Transaksi ',REPLACE(b.posbayar, '_', ' '),' ',b.rekmed,' ',b.namapasien) AS keterangan, a.totalcardrp 
                FROM tbl_kartukredit AS a
                LEFT JOIN tbl_kasir AS b ON b.nokwitansi = a.nokwitansi
                LEFT JOIN tbl_mutasikas AS c ON c.nomutasi = a.nomutasi
                WHERE a.nomutasi = '$header->nomutasi'
                AND a.kodebank = c.bankcode
                ORDER BY a.id DESC")->result();

                // $listmutasi = $this->db->query("SELECT a.nokwitansi, a.tglbayar, CONCAT('Transaksi ',REPLACE(a.posbayar, '_', ' '),' ',a.rekmed,' ',a.namapasien) AS keterangan, SUM(b.totalcardrp) AS totalcardrp 
                // FROM tbl_kasir AS a
                // LEFT JOIN tbl_kartukredit AS b
                // ON b.nokwitansi = a.nokwitansi
                // LEFT JOIN tbl_mutasikas AS c
                // ON c.nomutasi = a.nomutasi
                // WHERE b.nomutasi = '$header->nomutasi'
                // AND b.kodebank = c.bankcode
                // GROUP BY a.nokwitansi
                // ORDER BY a.id DESC")->result();

                // $total_debit    = $this->db->query("SELECT SUM(totalcardrp) AS total
                // FROM tbl_kasir AS a
                // LEFT JOIN tbl_kartukredit AS b
                // ON b.nokwitansi = a.nokwitansi
                // LEFT JOIN tbl_mutasikas AS c
                // ON c.nomutasi = b.nomutasi
                // WHERE b.nomutasi = '$header->nomutasi'
                // AND b.kodebank = c.bankcode
                // ORDER BY a.id DESC")->row();
                
                $saldoakhir     = number_format($header->mutasirp,0,"","");

                $acdari         = $this->db->query("SELECT * FROM tbl_accounting WHERE accountno = '$header->acdari'")->row();
                $acke           = $this->db->query("SELECT * FROM tbl_accounting WHERE accountno = '$header->acke'")->row();

                if(empty($acdari) || empty($acke)){
                    $dari = 0;
                    $ke   = 0;
                } else {
                    $dari = $acdari->acname;
                    $ke   = $acke->acname;
                }

                $pdf=new simkeu_nota();
                
                $pdf->setID($nama_usaha,$alamat1,$alamat2);
                $pdf->setfont('arial');
                $pdf->setjudul('');
                $pdf->setsubjudul('');
                $pdf->addpage("P","A4");   
                $pdf->setsize("P","A4");
                $pdf->ln(5);

                $pdf->SetWidths(array(190));
                $pdf->setfont('arial','B','13');
                $pdf->FancyRow(array('LAPORAN PENERIMAAN DAN PENGELUARAN KAS CC/DC'),0,0,"C");
                $pdf->ln(5);

                $pdf->SetWidths(array(190));
                $pdf->setfont('arial','','9');
                $pdf->FancyRow(array('Tanggal '. date("d-m-Y")),0,0,"C");
                $pdf->FancyRow(array($header->username),0,0,"C");
                $pdf->ln(8);

                $pdf->SetWidths(array(10, 40, 25, 70, 45));
                $align  = array("C","C","C","C","C");
                $border = array("LTBR","LTBR","LTBR","LTBR","LTBR");
                $pdf->setfont('arial','B','9');
                $pdf->FancyRow(array('No','No Bukti','Tanggal','Keterangan','Debit'),0, $border,$align);

                $pdf->SetWidths(array(10, 40, 25, 70, 45));
                $align  = array("C","L","L","L","R","R");
                $border = array("LBR","LBR","LBR","LBR","LBR");
                $pdf->setfont('arial','','9');
                $no = 1;
                foreach($listmutasi as $lkey => $lval){
                    $pdf->FancyRow(array(
                        $no++,
                        $lval->nokwitansi,
                        str_replace(" 00:00:00", "", $lval->tglbayar),
                        $lval->keterangan,
                        number_format($lval->totalcardrp, 0, ',', '.')
                    ),0, $border,$align);
                }

                $pdf->SetWidths(array(145, 45));
                $align  = array("L","R","R");
                $border = array("LTBR","LTBR","LTBR");
                $pdf->setfont('arial','B','9');
                $pdf->FancyRow(array('Total',number_format($saldoakhir, 0, ',', '.')),0, $border,$align);
                $pdf->ln(5);

                $pdf->SetWidths(array(95,95));
                $align  = array("L","C");
                $border = array("TLR","");
                $border2 = array("BLR","");
                $pdf->setfont('arial','','9');
                $pdf->FancyRow(array('Telah dipindah bukukan dari kas : '. $dari,''),0, $border, $align);
                $pdf->FancyRow(array('Ke account kas /bank : '. $ke,''),0, $border2, $align);
                $pdf->ln(5);

                $pdf->SetWidths(array(95,95));
                $align  = array("C","C");
                $pdf->setfont('arial','','9');
                $pdf->FancyRow(array('Disetujui Oleh',str_replace("dr. Affandi ", "", $profile->namars)),0, 0, $align);
                $pdf->FancyRow(array('Kepala Cabang,','Cashier,'),0, 0, $align);
                $pdf->ln(20);
                $pdf->FancyRow(array($profile->pejabat1,$header->username),0, 0, $align);

                $pdf->SetTitle($param);
                $pdf->AliasNbPages();
                $pdf->output($param.'.PDF','I');
            }
        }

    }
<?php

    defined("BASEPATH") or exit("No direct script access allowed");

    class Mutasi_pendapatan_kasir extends CI_Controller{

        public function __construct(){
            parent::__construct();
            $this->session->set_userdata('menuapp', '2000');
		    $this->session->set_userdata('submenuapp', '2307');
            $this->load->helper(array("app_global","simkeu_nota1","simkeu_nota"));
            $this->load->model(array("M_global"));
        }

        public function index(){
            $unit   = $this->session->userdata("unit");
            $cek    = $this->session->userdata("level");

            $period          = date("Y")."-".date("m")."-";
            $bulan           = $this->M_global->_periodebulan();
            $nbulan   	     = $this->M_global->_namabulan($bulan);
            
            $data["periode"] = 'Periode '.$nbulan.'-'.$this->M_global->_periodetahun();
            $data["listv"]   = $this->db->query("SELECT * FROM tbl_mutasikas WHERE koders = '$unit' AND keterangan LIKE '%Mutasi Pendapatan Kasir Tunai%' AND tglmutasi LIKE '%". date("Y-m") ."%'")->result();
            $data["unit"]    = $unit;

            if(empty($cek)){
                redirect("/");
            } else {
                $this->load->view("kasir/v_mutasi_pendapatan_kasir", $data);
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
                $this->load->view("kasir/v_mutasi_pendapatan_kasir_entri", $data);
            }
        }

        public function query(){
            $unit        = $this->session->userdata("unit");
            $user        = $this->input->post("user");
            $dari_tgl    = $this->input->post("fromdate");
            $sampai_tgl  = $this->input->post("todate");
            $shift       = $this->input->post("shift");

            $check_kasir = $this->db->query("SELECT username, shipt, tglbayar, koders FROM tbl_kasir WHERE username = '$user' AND koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$dari_tgl' AND '$sampai_tgl'");

            if($check_kasir->num_rows() == 0){
                echo json_encode(array("status" => 0));
            } else {
                echo json_encode(array("direct" => "/mutasi_pendapatan_kasir/result/?cashier=$user&from=$dari_tgl&to=$sampai_tgl&shift=$shift"), JSON_UNESCAPED_SLASHES);
            }
        }

        public function result(){
            $cek            = $this->session->userdata("level");
            $unit           = $this->session->userdata("unit");

            $cashier        = $this->input->get("cashier");
            $fromdate       = $this->input->get("from");
            $todate         = $this->input->get("to");
            $shift          = $this->input->get("shift");

            if($cashier == "" || $fromdate == "" || $todate == "" || $shift == ""){
                redirect("/mutasi_pendapatan_kasir");
            }

            if($shift == "--- Pilih Shift ---"){
                redirect(base_url() ."/mutasi_pendapatan_kasir/result/?cashier=$cashier&from=$fromdate&to=$todate&shift=0");
            }

            $get_user       = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$cashier'")->row();

            /*
            $penerimaan         = $this->db->query("SELECT SUM(b.totalcardrp)totalcardrp
            FROM tbl_kasir AS a
            right JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi AND a.koders=b.koders and a.jenisbayar=b.jenisbayar
            WHERE a.username = '$cashier' AND a.koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate'")->row();
            */

            $penerimaan         = $this->db->query("SELECT SUM(totalsemua) AS total FROM tbl_kasir AS a 
            WHERE a.koders = '$unit' 
            AND a.username = '$cashier' 
            AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
            AND a.shipt = $shift
            AND a.bayarcash > 0
            AND a.nomutasi = ''")->row();

            $pengeluaran        = $this->db->query("SELECT SUM(jmbayar) as total FROM tbl_hbayar 
            WHERE koders = '$unit' 
            AND tglbayar BETWEEN '$fromdate' AND '$todate' 
            AND username = '$cashier' 
            AND shipt = '$shift' 
            AND nomutasi = '0'")->row();

            $daftar_pengeluaran = $this->db->query("SELECT * FROM tbl_hbayar WHERE koders = '$unit' AND tglbayar BETWEEN '$fromdate' AND '$todate' AND username = '$cashier' AND shipt = '$shift' AND nomutasi = '0'");

            /*
            $daftar_peneriamaan = $this->db->query("SELECT a.posbayar, a.rekmed, a.namapasien, a.username, a.koders, a.nokwitansi, a.tglbayar, a.shipt, SUM(b.totalcardrp)totalcardrp
            FROM tbl_kasir AS a
            right JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi AND a.koders=b.koders and a.jenisbayar=b.jenisbayar
            WHERE a.username = '$cashier' AND a.koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate'
            GROUP BY a.koders, a.nokwitansi, a.tglbayar, a.shipt");
            */

            // $daftar_peneriamaan = $this->db->query("SELECT * FROM tbl_kasir AS a 
            // WHERE a.koders = '$unit' 
            // AND a.username = '$cashier' 
            // AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
            // AND a.shipt = '$shift'
            // AND a.bayarcash > 0
            // AND a.nomutasi = ''");

            $daftar_peneriamaan     = $this->db->query("SELECT a.nokwitansi, a.tglbayar, CONCAT('Transaksi ', a.nokwitansi) AS keterangan, a.totalsemua AS total FROM tbl_kasir AS a 
            WHERE a.koders = '$unit' 
            AND a.username = '$cashier' 
            AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
            AND a.shipt = $shift
            AND a.bayarcash > 0
            AND a.nomutasi = ''");

            // $data["listv"] = $this->db->query("SELECT * FROM tbl_kasir WHERE username = '$cashier' AND koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate' ORDER BY tglbayar DESC")->result();
            $data["listv"]       = $daftar_peneriamaan->result();
            $data["listx"]       = $daftar_pengeluaran->result();
            $data["penerimaan"]  = number_format($penerimaan->total, 2, '.', ',');
            $data["pengeluaran"] = number_format($pengeluaran->total, 2, '.', ',');
            $data["username"]    = $get_user->username;
            $data["uidlog"]    = $get_user->uidlogin;
            $data["shift"] = $shift;

            if(!empty($cek)){
                $this->load->view("kasir/v_mutasi_pendapatan_kasir_res", $data);
            } else {
                redirect("/mutasi_pendapatan_kasir");
            }
        }

        public function detail($param){
            $cek            = $this->session->userdata("level");
            $unit           = $this->session->userdata("unit");

            $get_data       = $this->db->query("SELECT * FROM tbl_mutasikas WHERE nomutasi = '$param'")->row();

            $get_user       = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$get_data->username'")->row();

            $acdari         = $this->db->query("SELECT * FROM tbl_accounting where accountno = '$get_data->acdari'")->row();
            $acke           = $this->db->query("SELECT * FROM tbl_accounting where accountno = '$get_data->acke'")->row();

            /*
            $penerimaan         = $this->db->query("SELECT SUM(b.totalcardrp)totalcardrp
            FROM tbl_kasir AS a
            right JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi AND a.koders=b.koders and a.jenisbayar=b.jenisbayar
            WHERE a.username = '$cashier' AND a.koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate'")->row();
            */

            $penerimaan         = $this->db->query("SELECT SUM(totalsemua) AS total 
            FROM tbl_kasir 
            WHERE nomutasi = '$get_data->nomutasi'")->row();

            $pengeluaran        = $this->db->query("SELECT SUM(jmbayar) as total 
            FROM tbl_hbayar 
            WHERE nomutasi = '$get_data->nomutasi'")->row();

            $daftar_pengeluaran = $this->db->query("SELECT * FROM tbl_hbayar WHERE nomutasi = '$get_data->nomutasi'");

            /*
            $daftar_peneriamaan = $this->db->query("SELECT a.posbayar, a.rekmed, a.namapasien, a.username, a.koders, a.nokwitansi, a.tglbayar, a.shipt, SUM(b.totalcardrp)totalcardrp
            FROM tbl_kasir AS a
            right JOIN tbl_kartukredit AS b ON b.nokwitansi = a.nokwitansi AND a.koders=b.koders and a.jenisbayar=b.jenisbayar
            WHERE a.username = '$cashier' AND a.koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate'
            GROUP BY a.koders, a.nokwitansi, a.tglbayar, a.shipt");
            */

            // $daftar_peneriamaan = $this->db->query("SELECT * FROM tbl_kasir AS a 
            // WHERE a.koders = '$unit' 
            // AND a.username = '$cashier' 
            // AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
            // AND a.shipt = '$shift'
            // AND a.bayarcash > 0
            // AND a.nomutasi = ''");

            $daftar_peneriamaan     = $this->db->query("SELECT a.nokwitansi, a.tglbayar, CONCAT('Transaksi ', a.nokwitansi) AS keterangan, a.totalsemua AS total FROM tbl_kasir AS a 
            WHERE nomutasi = '$get_data->nomutasi'");

            // $data["listv"] = $this->db->query("SELECT * FROM tbl_kasir WHERE username = '$cashier' AND koders = '$unit' AND shipt = '$shift' AND tglbayar BETWEEN '$fromdate' AND '$todate' ORDER BY tglbayar DESC")->result();
            $data["listv"]       = $daftar_peneriamaan->result();
            $data["listx"]       = $daftar_pengeluaran->result();
            $data["penerimaan"]  = number_format($penerimaan->total, 2, '.', ',');
            $data["pengeluaran"] = number_format($pengeluaran->total, 2, '.', ',');
            $data["username"]    = $get_user->username;
            $data["uidlog"]      = $get_user->uidlogin;
            $data["shift"]       = $get_data->shipt;
            $data["head"]        = $get_data;
            $data["acdari"]      = $acdari->acname;
            $data["acke"]        = $acke->acname;

            $this->load->view("kasir/v_mutasi_pendapatan_kasir_det", $data);
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
            $username        = $this->input->post("uidlog");
            $shift           = $this->input->post("shift");
            $fromdate        = $this->input->post("fromdate");
            $todate          = $this->input->post("todate");
            $tanggal         = date("Y-m-d");
            
            $nomutasi        = $this->input->post("nomutasi");
            // $saldoawal       = str_replace(",", "", $this->input->post("saldoawal"));
            $saldoawal       = 0;
            $penerimaan      = str_replace(",", "", $this->input->post("penerimaan"));
            // $selisih         = str_replace(",", "", $this->input->post("selisih"));
            $selisih         = 0;
            $pengeluaran     = str_replace(",", "", $this->input->post("pengeluaran"));
            $saldoakhir      = str_replace(",", "", $this->input->post("saldoakhir"));
            $acdari          = $this->input->post("acdari");
            $acke            = $this->input->post("acke");
            $keterangan      = $this->input->post("keterangan");
            $jumlahmutasi    = str_replace(",", "", $this->input->post("jumlahmutasi"));

            // $listpenerimaan  = $this->input->post("listpenerimaan");
            // $listpengeluaran = $this->input->post("listpengeluaran");

            $listpenerimaan  = $this->db->query("SELECT a.nokwitansi, a.tglbayar, CONCAT('Transaksi ', REPLACE(a.posbayar, '_', ' ')) AS keterangan, a.totalsemua AS total FROM tbl_kasir AS a 
            WHERE a.koders = '$unit' 
            AND a.username = '$username' 
            AND a.tglbayar BETWEEN '$fromdate' AND '$todate'
            AND a.shipt = $shift
            AND a.bayarcash > 0
            AND a.nomutasi = ''")->result();

            $listpengeluaran = $this->db->query("SELECT * FROM tbl_hbayar 
            WHERE koders = '$unit' 
            AND tglbayar BETWEEN '$fromdate' AND '$todate' 
            AND username = '$username' 
            AND shipt = '$shift' 
            AND nomutasi = '0'")->result();

            foreach($listpenerimaan as $lpval){
                $this->db->query("UPDATE tbl_kasir SET nomutasi = '$nomutasi' WHERE nokwitansi = '$lpval->nokwitansi'");
            }
            
            foreach($listpengeluaran as $lpgval){
                $this->db->query("UPDATE tbl_hbayar SET nomutasi = '$nomutasi' WHERE bayarno = '$lpgval->bayarno'");
            }

            $this->db->query("INSERT INTO tbl_mutasikas (koders,nomutasi,acdari,acke,keterangan,tglmutasi,mutasirp,username,shipt,saawal,selisih)
            VALUES ('$unit','$nomutasi','$acdari','$acke','$keterangan','$tanggal','$jumlahmutasi','$username','$shift','$saldoawal','$selisih')");

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

            $data["listv"]      = $this->db->query("SELECT * FROM tbl_mutasikas WHERE koders = '$unit' AND keterangan LIKE '%Mutasi Pendapatan Kasir Tunai%' AND tglmutasi BETWEEN '$dari_Periode' AND '$ke_periode'")->result();
            $dari_bulan   	    = $this->M_global->_namabulan($bulan_dari_priode);
            $ke_bulan           = $this->M_global->_namabulan($bulan);
            $data["periode"]    = 'Periode '.$dari_bulan.'-'.$tahun1[0].' s/d '.$ke_bulan.'-'.$tahun2[0];
            if(!empty($cek)){
                $this->load->view("kasir/v_mutasi_pendapatan_kasir", $data);
            } else {
                header("location:".base_url());
            }
        }

        public function cetak($param){
            $query_check = $this->db->query("SELECT * FROM tbl_mutasikas WHERE nomutasi = '$param'");

            if($query_check->num_rows() == 0){
                redirect("/mutasi_pendapatan_kasir");
            } else {
                $unit       = $this->session->userdata('unit');
                $user       = $this->session->userdata('username');

                $profile    = $this->db->query("SELECT * FROM tbl_namers WHERE koders = '$unit'")->row();
                $nama_usaha = $profile->namars;
                $alamat1    = $profile->alamat;
                $alamat2    = $profile->kota;
                $cabang     = str_replace("KLINIK ESTETIKA dr. Affandi ", "", $profile->namars);

                $userinfo   = $this->db->query("SELECT * FROM userlogin WHERE uidlogin = '$user'")->row();

                $header     = $this->db->query("SELECT * FROM tbl_mutasikas WHERE nomutasi = '$param'")->row();

                // $listmutasi = $this->db->query("SELECT nokwitansi AS notransaksi, tglbayar, CONCAT('Transaksi ',REPLACE(posbayar, '_', ' '),' ',rekmed,' ',namapasien) AS keterangan, FORMAT(totalsemua, 0) AS debit, totalsemua = null AS kredit, FORMAT(totalsemua, 0) AS saldoakhir FROM tbl_kasir WHERE nomutasi = '$header->nomutasi' UNION ALL SELECT bayarno, tglbayar, keterangan, jmbayar = null, FORMAT(jmbayar, 0), FORMAT(jmbayar, 0) FROM tbl_hbayar WHERE nomutasi = '$header->nomutasi'")->result();
                // TEMP
                // $listmutasi = $this->db->query("SELECT nokwitansi AS notransaksi, tglbayar, CONCAT('Transaksi ',' ',rekmed,' ',namapasien) AS keterangan, FORMAT(totalsemua, 0) AS debit, totalsemua = null AS kredit, FORMAT(totalsemua, 0) AS saldoakhir 
                // FROM tbl_kasir 
                // WHERE nomutasi = '$header->nomutasi' 
                
                // UNION ALL 
                
                // SELECT bayarno, tglbayar, keterangan, jmbayar = null, FORMAT(jmbayar, 0), FORMAT(jmbayar, 0) 
                // FROM tbl_hbayar 
                // WHERE nomutasi = '$header->nomutasi'")->result();

                $listmutasi = $this->db->query("SELECT nokwitansi AS notransaksi, tglbayar, CONCAT('Transaksi ',' ',rekmed,' ',namapasien) AS keterangan, FORMAT(totalsemua, 0) AS debit, totalsemua = null AS kredit, FORMAT(totalsemua, 0) AS saldoakhir 
                FROM tbl_kasir 
                WHERE nomutasi = '$header->nomutasi'")->result();

                $total_debit    = $this->db->query("SELECT SUM(totalsemua) AS total FROM tbl_kasir WHERE nomutasi = '$header->nomutasi'")->row();
                $total_kredit   = $this->db->query("SELECT SUM(jmbayar) AS total FROM tbl_hbayar WHERE nomutasi = '$header->nomutasi'")->row();
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
                $pdf->FancyRow(array('LAPORAN PENERIMAAN DAN PENGELUARAN KAS'),0,0,"C");
                $pdf->ln(5);

                $pdf->SetWidths(array(190));
                $pdf->setfont('arial','','9');
                $pdf->FancyRow(array('Tanggal '. date("d-m-Y")),0,0,"C");
                $pdf->FancyRow(array($userinfo->username),0,0,"C");
                $pdf->ln(8);

                // TEMP
                // $pdf->SetWidths(array(40,10,50));
                // $pdf->setfont('arial','','9');
                // $pdf->FancyRow(array('Saldo Awal',':', 'Rp '. number_format($header->saawal, 0, '.', ',')),0,0,"L");
                // $pdf->FancyRow(array('Selisih',':', 'Rp '. number_format($header->selisih, 0, '.', ',')),0,0,"L");
                // $pdf->ln(8);

                $pdf->SetWidths(array(10, 38, 25, 42, 25, 25, 25));
                $align  = array("C","C","C","C","C","C","C");
                $border = array("LTBR","LTBR","LTBR","LTBR","LTBR","LTBR","LTBR");
                $pdf->setfont('arial','B','9');
                $pdf->FancyRow(array('No','No Bukti','Tanggal','Keterangan','Debit','Kredit','Saldo AKhir'),0, $border,$align);

                $pdf->SetWidths(array(10, 38, 25, 42, 25, 25, 25));
                $align  = array("C","L","L","L","R","R","R");
                $border = array("LBR","LBR","LBR","LBR","LBR","LBR","LBR");
                $pdf->setfont('arial','','9');
                $no = 1;
                foreach($listmutasi as $lkey => $lval){
                    $pdf->FancyRow(array($no++,$lval->notransaksi,str_replace(" 00:00:00", "", $lval->tglbayar),$lval->keterangan,$lval->debit,$lval->kredit,$lval->saldoakhir),0, $border,$align);
                }

                $pdf->SetWidths(array(115, 25, 25, 25));
                $align  = array("L","R","R", "R");
                $border = array("LTBR","LTBR","LTBR","LTBR");
                $pdf->setfont('arial','B','9');
                // TEMP
                // $pdf->FancyRow(array('Total',number_format($total_debit->total, 0, '.', ','),number_format($total_kredit->total, 0, '.', ','),number_format($header->mutasirp, 0, '.', ',')),0, $border,$align);
                $pdf->FancyRow(array('Total',number_format($total_debit->total, 0, '.', ','),"",number_format($total_debit->total, 0, '.', ',')),0, $border,$align);
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
                $pdf->FancyRow(array($profile->pejabat1,$userinfo->username),0, 0, $align);

                $pdf->SetTitle($param);
                $pdf->AliasNbPages();
                $pdf->output($param.'.PDF','I');
            }
        }

    }
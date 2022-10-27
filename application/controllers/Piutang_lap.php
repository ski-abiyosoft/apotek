<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_lap extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->session->set_userdata('menuapp', '5300');
        $this->session->set_userdata('submenuapp', '5303');
        $this->load->model(array("M_bedah", "M_pasien_global", "M_cetak", "M_piutang"));
        $this->load->helper(array("app_global", "rsreport"));
		$this->load->library('export');
    }

    public function index()
    {
        $cek = $this->session->userdata('level');
        $unit = $this->session->userdata('unit');

        if (!empty($cek)) {

            $query_vendor    = $this->db->query("SELECT * FROM tbl_vendor");
            $query_cabang    = $this->db->query("SELECT * FROM tbl_namers ORDER BY koders ASC");
            $query_cust        = $this->db->query("SELECT cust_id, cust_nama FROM tbl_penjamin ORDER BY id DESC");

            $data    =    [
                "unit"        => $unit,
                "vendor"    => $query_vendor->result(),
                "penjamin"    => $query_cust->result(),
                "cabangrs"    => $query_cabang->result(),
            ];

            $this->load->view('penjualan/v_piutang_lap', $data);
        } else {
            redirect("/");
        }
    }

    //Print Header
    /**
     * Method untuk mencetak laporan
     * 
     * @param string
     */
    public function cetak()
    {
		setlocale(LC_ALL, 'id_ID');
		ini_set("memory_limit", "-1");
		ini_set("pcre.backtrack_limit", "100000000");
        ini_set("max_execution_time","300");
		
		$cek = $this->session->userdata('level');
        $unit = $this->session->userdata('unit');
        $kop = $this->M_cetak->kop($unit);

        if (!empty($cek)) {
            switch ($this->input->get('laporan')) {
                case '1':
                    $data = $this->M_piutang->get_ar_report_data((object) $this->input->get());

                    $judul = "Laporan Detail Piutang";
                    $this->export->pdf('L', 'A4', $judul, $this->load->view('laporan_akuntansi/laporan_detail_piutang', ['kop' => $kop, 'data' => $data, 'input' => (object) $this->input->get()], true), 'Laporan_Detail_Piutang.PDF', 10, 10, 10, 2);
                    break;
				case '2':
					$data = $this->M_piutang->get_ar_aging_data((object) $this->input->get());
					$judul = "Laporan Umur Piutang";
                    $this->export->pdf('L', 'A4', $judul, $this->load->view('laporan_akuntansi/laporan_umur_piutang', ['kop' => $kop, 'data' => $data, 'input' => (object) $this->input->get()], true), 'Laporan_Detail_Piutang.PDF', 10, 10, 10, 2);
                    break;
            }
        }
    }

    public function excel()
    {
        $cek     = $this->session->userdata('level');
        $unit    = $this->session->userdata('unit');

        if (!empty($cek)) {
            $data         = "";
            $printinit    = $this->M_cetak->kop2();
            $allcabang    = $this->input->get("allcabang");
            $cabang        = $this->input->get("cabang");
            $allvendor    = $this->input->get("allvendor");
            $vendor        = $this->input->get("vendor");
            $fromdate    = $this->input->get("fromdate");
            $todate        = $this->input->get("todate");
            $laporan    = $this->input->get("laporan");
            $jenis        = $this->input->get("jenis");

            switch ($laporan) {
                case 1:
                    $title = "LAPORAN DETAIL PIUTANG";
                    break;
                case 2:
                    $title = "REKAP UMUR PIUTANG";
                    break;
                case 5:
                    $title = "KARTU PIUTANG";
                    break;
                case 6:
                    $title = "";
                    break;
                case 7:
                    $title = "";
                    break;
                default:
                    redirect("/piutang_lap/");
                    break;
            }

            $comp_name    = $printinit->namars;
            $comp_addr    = $printinit->alamat;
            $comp_phone    = $printinit->phone;
            $comp_image    = base_url() . $printinit->image;

            if ($allvendor == 1 && $allcabang == 1) {
                $cbg    = "Semua Cabang";
                if ($jenis == 1) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.asal = 'POLI'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate'  
					AND a.asal = 'POLI' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 2) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.asal = 'INAP'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.asal = 'INAP' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 3 || $jenis == 4) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                }
            } else 
			if ($allcabang == 1) {
                $cbg    = "Semua Cabang";
                $getvendor    = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

                if ($jenis == 1) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'POLI' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'POLI' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 2) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'INAP' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'INAP' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 3 || $jenis == 4) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglinvoice BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'INAP' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                }
            } else 
			if ($allvendor == 1) {
                $cbg    = data_master("tbl_namers", array("koders"    => $unit))->namars;
                if ($jenis == 1) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit' 
					AND a.asal = 'POLI' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit' 
					AND a.asal = 'POLI' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 2) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit' 
					AND a.asal = 'INAP' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit' 
					AND a.asal = 'INAP' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 3 || $jenis == 4) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$unit' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                }
            } else {
                $cbg    = data_master("tbl_namers", array("koders"    => $unit))->namars;
                $getvendor    = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

                if ($jenis == 1) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'POLI' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate'
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'POLI' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 2) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor'
					AND a.asal = 'INAP' 
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate'
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor' 
					AND a.asal = 'INAP' 
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                } else 
				if ($jenis == 3 || $jenis == 4) {
                    $query_list_01    = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
					FROM tbl_pap AS a
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate' 
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor'
					GROUP BY a.invoiceno
					ORDER BY a.tglposting DESC")->result();

                    $fromdate = strtotime($fromdate);

                    $_1 = date("Y-m-d", strtotime('+1 day', $fromdate));
                    $_30 = date("Y-m-d", strtotime('+1 month', $fromdate));
                    $_31 = date("Y-m-d", strtotime('+31 day', $fromdate));
                    $_60 = date("Y-m-d", strtotime('+2 month', $fromdate));
                    $_61 = date("Y-m-d", strtotime('+61 day', $fromdate));
                    $_90 = date("Y-m-d", strtotime('+3 month', $fromdate));
                    $_181 = date("Y-m-d", strtotime('+181 day', $fromdate));
                    $_365 = date("Y-m-d", strtotime('+1 year', $fromdate));

                    $query_list_02    = $this->db->query("SELECT a.cust_id,b.cust_nama,
					sum(CASE WHEN a.tgljatuhtempo < '$fromdate' then a.jumlahhutang else 0 end) as belum_jt,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$fromdate' AND '$todate' then a.jumlahhutang else 0 end) as jatuh_tempo,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_1' AND '$_30' then a.jumlahhutang else 0 end) as b1_30,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_31' AND '$_60' then a.jumlahhutang else 0 end) as b31_60,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_61' AND '$_90' then a.jumlahhutang else 0 end) as b61_90,
					sum(CASE WHEN a.tgljatuhtempo BETWEEN '$_181' AND '$_365' then a.jumlahhutang else 0 end) as b181_365,
					sum(CASE WHEN a.tgljatuhtempo > '$_365' then a.jumlahhutang else 0 end) as lebih_1th
					FROM tbl_pap AS a 
					LEFT JOIN tbl_penjamin AS b ON b.cust_id = a.cust_id 
					WHERE a.tglposting BETWEEN '$fromdate' AND '$todate'
					AND a.koders = '$cabang' 
					AND a.cust_id = '$vendor'
					group by a.cust_id,b.cust_nama
					ORDER BY a.cust_id")->result();
                }
            }

            if ($laporan == 1) {
                $data    .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
					<thead>
						<tr>
							<th style='border-bottom:1px solid #222;width:10%' colspan='2'>
								<center><img src='" . $comp_image . "'  width='100' height='75'></center>
							</th>
							<th style='border-bottom:1px solid #222;width:90%;font-weight:normal;text-align:left' colspan='12'>
								<b>$comp_name</b><br />$comp_addr<br />Telp :$comp_phone
							</th>
						</tr> 
					</thead>

					<tbody>
						<tr>
							<td colspan='14'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='14' style='font-size:16px;text-align:center'><br /><b>" . $title . "</b><br />Dari&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("fromdate"))) . "</b>&emsp;s/d&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("todate"))) . "</b><br />Cabang : <b>" . $cbg . "</b><br /></td>
						</tr>
						<tr>
							<td colspan='14'>&nbsp;</td>
						</tr>
					</tbody>
				</table>";

                $data .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>
				<thead>
					<tr>
						<th style='border:1px solid #222'>No Faktur</th>
						<th style='border:1px solid #222'>No Reg</th>
						<th style='border:1px solid #222'>Rekmed</th>
						<th style='border:1px solid #222'>No Doc</th>
						<th style='border:1px solid #222'>Nama Pasien</th>
						<th style='border:1px solid #222'>Penjamin</th>
						<th style='border:1px solid #222'>Jenis</th>
						<th style='border:1px solid #222'>Tgl Posting</th>
						<th style='border:1px solid #222'>Jatuh Tempo</th>
						<th style='border:1px solid #222'>Total Tagihan</th>
						<th style='border:1px solid #222'>Inacbg</th>
						<th style='border:1px solid #222'>Total Bayar</th>
						<th style='border:1px solid #222'>Saldo Piutang</th>
						<th style='border:1px solid #222'>No Invoice</th>
					</tr>
				</thead>
				<tbody>";
                foreach ($query_list_01 as $rkey => $rval) {
                    $data    .= "<tr>
						<td style='border:1px solid #222'>" . $rval->fakturno . "</td>
						<td style='border:1px solid #222'>" . $rval->noreg . "</td>
						<td style='border:1px solid #222'>" . $rval->rekmed . "</td>
						<td style='border:1px solid #222'>" . $rval->nodoc . "</td>
						<td style='border:1px solid #222'>" . $rval->namapas . "</td>
						<td style='border:1px solid #222'>" . $rval->cust_nama . "</td>
						<td style='border:1px solid #222'>" . $rval->asal . "</td>
						<td style='border:1px solid #222'>" . date("Y-m-d", strtotime($rval->tglposting)) . "</td>
						<td style='border:1px solid #222'>" . date("Y-m-d", strtotime($rval->tgljatuhtempo)) . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->jumlahhutang, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->inacbg, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->jumlahbayar, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->saldopiutang, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . $rval->invoiceno . "</td>
					</tr>";
                }
                $data .= '<tbody></table>';
            } else 
			if ($laporan == 2) {
                $data    .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
					<thead>
						<tr>
							<th style='border-bottom:1px solid #222;width:10%' colspan='2'>
								<center><img src='" . $comp_image . "'  width='100' height='75'></center>
							</th>
							<th style='border-bottom:1px solid #222;width:90%;font-weight:normal;text-align:left' colspan='7'>
								<b>$comp_name</b><br />$comp_addr<br />Telp :$comp_phone
							</th>
						</tr> 
					</thead>

					<tbody>
						<tr>
							<td colspan='9'>&nbsp;</td>
						</tr>
						<tr>
							<td colspan='9' style='font-size:16px;text-align:center'><br /><b>" . $title . "</b><br />Dari&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("fromdate"))) . "</b>&emsp;s/d&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("todate"))) . "</b><br /></td>
						</tr>
						<tr>
							<td colspan='9'>&nbsp;</td>
						</tr>
					</tbody>
				</table>";

                $data .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>
				<thead>
					<tr>
						<th style='border:1px solid #222'>Pelanggan</th>
						<th style='border:1px solid #222'>Saldo Awal</th>
						<th style='border:1px solid #222'>Belum Jt tempo</th>
						<th style='border:1px solid #222'>1 s/d 30 Hari</th>
						<th style='border:1px solid #222'>31 s/d 60 Hari</th>
						<th style='border:1px solid #222'>61 s/d 90 Hari</th>
						<th style='border:1px solid #222'>181 s/d 365 Hari</th>
						<th style='border:1px solid #222'>> 1 Tahun</th>
						<th style='border:1px solid #222'>Total Piutang</th>
					</tr>
				</thead>
				<tbody>";
                $t0 = $t1 = $t2 = $t3 = $t4 = $t5 = $t6 = $t7 = 0;
                foreach ($query_list_02 as $rkey => $rval) {
                    $total    = $rval->belum_jt + $rval->b1_30 + $rval->b31_60 + $rval->b61_90 + $rval->b181_365 + $rval->lebih_1th;
                    $t0 += $rval->belum_jt;
                    $t1    += $rval->b1_30;
                    $t2    += $rval->b31_60;
                    $t4    += $rval->b61_90;
                    $t5    += $rval->b181_365;
                    $t6    += $rval->lebih_1th;
                    $t7    += $total;
                    $data    .= "<tr>
						<td style='border:1px solid #222'>" . $rval->cust_nama . "</td>
						<td style='border:1px solid #222'></td>
						<td style='border:1px solid #222'>" . number_format($rval->belum_jt, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->b1_30, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->b31_60, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->b61_90, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->b181_365, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($rval->lebih_1th, 0, ',', '.') . "</td>
						<td style='border:1px solid #222'>" . number_format($total, 0, ',', '.') . "</td>
					</tr>";
                }
                $data .= '<tr>
					<td style="border:1px solid #222;font-weight:bold">Total</td>
					<td style="border:1px solid #222;font-weight:bold"></td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t0, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t1, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t2, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t4, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t5, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t6, 0, ',', '.') . '</td>
					<td style="border:1px solid #222;font-weight:bold">' . number_format($t7, 0, ',', '.') . '</td>
				</tr><tbody></table>';
            } else {
                redirect("/hutang_lap/");
            }

            $excel['prev'] = $data;
            header("Cache-Control: no-cache, no-store, must-revalidate");
            header("Content-Type: application/vnd-ms-excel");
            header("Content-Disposition: attachment; filename= $title" . date("dmy") . "_" . date("His") . ".xls");
            $this->load->view('app/master_cetak', $excel);
        } else {
            redirect("/");
        }
    }
}
/* End of file Hutang_lap.php */
/* Location: ./application/controllers/Hutang_lap.php */
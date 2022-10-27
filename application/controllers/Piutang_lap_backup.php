<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Piutang_lap extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->session->set_userdata('menuapp', '5300');
    $this->session->set_userdata('submenuapp', '5303');
    $this->load->model(array("M_bedah", "M_pasien_global", "M_cetak"));
    $this->load->helper(array("app_global", "rsreport"));
  }

  public function index()
  {
    $cek = $this->session->userdata('level');
    $unit = $this->session->userdata('unit');

    if (!empty($cek)) {

      $query_vendor  = $this->db->query("SELECT * FROM tbl_vendor");
      $query_cabang  = $this->db->query("SELECT * FROM tbl_namers ORDER BY koders ASC");
      $query_cust    = $this->db->query("SELECT cust_id, cust_nama FROM tbl_penjamin ORDER BY id DESC");

      $data  =  [
        "unit"    => $unit,
        "vendor"  => $query_vendor->result(),
        "penjamin"  => $query_cust->result(),
        "cabangrs"  => $query_cabang->result(),
      ];

      $this->load->view('penjualan/v_piutang_lap', $data);
    } else {
      redirect("/");
    }
  }

  //Print Header

  public function cetak($param)
  {
    $cek   = $this->session->userdata('level');
    $unit  = $this->session->userdata('unit');

    if (!empty($cek)) {
      $allcabang    = ($this->input->post("allcabang") != null) ? $this->input->post("allcabang") : 0;
      $cabang      = ($this->input->post("cabang") != null) ? $this->input->post("cabang") : 0;
      $allvendor    = ($this->input->post("allvendor") != null) ? $this->input->post("allvendor") : 0;
      $vendor      = ($this->input->post("vendor") != null) ? $this->input->post("vendor") : 0;
      $fromdate    = $this->input->post("fromdate");
      $todate      = $this->input->post("todate");
      $laporan    = $this->input->post("laporan");
      $jenis      = $this->input->post("jenis");

      switch ($param) {
        case 1:
          $url  = "/piutang_lap/pdf/";
          break;
        case 2:
          $url  = "/piutang_lap/excel/";
          break;
      }

      $data    = (object) array(
        "allcabang"  => $allcabang,
        "cabang"  => $cabang,
        "allvendor"  => $allvendor,
        "vendor"  => $vendor,
        "fromdate"  => $fromdate,
        "todate"  => $todate,
        "laporan"  => $laporan,
        "jenis"    => $jenis,
      );

      $result    = array(
        "url"   => $url,
        "res"  => $data,
      );

      echo json_encode($result, JSON_UNESCAPED_SLASHES);
    } else {
      redirect("/");
    }
  }

  public function pdf()
  {
    $cek   = $this->session->userdata('level');
    $unit  = $this->session->userdata('unit');

    if (!empty($cek)) {
      $data     = "";
      $printinit  = $this->M_cetak->kop2();
      $allcabang  = $this->input->get("allcabang");
      $cabang    = $this->input->get("cabang");
      $allvendor  = $this->input->get("allvendor");
      $vendor    = $this->input->get("vendor");
      $fromdate  = $this->input->get("fromdate");
      $todate    = $this->input->get("todate");
      $laporan  = $this->input->get("laporan");
      $jenis    = $this->input->get("jenis");

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

      $comp_name  = $printinit->namars;
      $comp_addr  = $printinit->alamat;
      $comp_phone  = $printinit->phone;
      $comp_image  = base_url() . $printinit->image;

      if ($allvendor == 1 && $allcabang == 1) {
        $cbg  = "Semua Cabang";
        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = "Semua Cabang";
        $getvendor  = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = data_master("tbl_namers", array("koders"  => $unit))->namars;
        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = data_master("tbl_namers", array("koders"  => $unit))->namars;
        $getvendor  = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $data .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px}
					.title {font-size:18px;margin-top:10px}
					.separator {border:115px solid #222;}
					.date {font-size:12px}
				</style>";

        $data .= "<table class='table'>	
					<thead>
						<tr>
							<td rowspan='6' align='center'>
								<img src='" . $comp_image . "'  width='100' height='70' />
							</td>
							<td colspan='20'>
								<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
								<tr><td style='font-size:13px;'>$comp_addr</td></tr>
								<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
							</td>
						</tr> 
					</thead>
				</table>
				
				<br />

				<div class='title centered'>" . $title . "</div>
				<div class='date centered'>Dari&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("fromdate"))) . "</b>&emsp;s/d&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("todate"))) . "</b></div>
				<div class='date centered'>Cabang : <b>" . $cbg . "</b></div>
				
				<br />";

        $data .= '<table class="table">
				<thead>
					<tr>
						<th class="bordered centered">No Faktur</th>
						<th class="bordered centered">No Reg</th>
						<th class="bordered centered">Rekmed</th>
						<th class="bordered centered">No Doc</th>
						<th class="bordered centered">Nama Pasien</th>
						<th class="bordered centered">Penjamin</th>
						<th class="bordered centered">Jenis</th>
						<th class="bordered centered">Tgl Posting</th>
						<th class="bordered centered">Jatuh Tempo</th>
						<th class="bordered centered">Total Tagihan</th>
						<th class="bordered centered">Inacbg</th>
						<th class="bordered centered">Total Bayar</th>
						<th class="bordered centered">Saldo Piutang</th>
						<th class="bordered centered">No Invoice</th>
					</tr>
				</thead>
				<tbody>';
        $totaltagihan = 0;
        foreach ($query_list_01 as $rkey => $rval) {
          $totaltagihan += $rval->jumlahhutang;
          $data  .= "<tr>
						<td class='bordered centered'>" . $rval->fakturno . "</td>
						<td class='bordered'>" . $rval->noreg . "</td>
						<td class='bordered'>" . $rval->rekmed . "</td>
						<td class='bordered'>" . $rval->nodoc . "</td>
						<td class='bordered'>" . $rval->namapas . "</td>
						<td class='bordered'>" . $rval->cust_nama . "</td>
						<td class='bordered'>" . $rval->asal . "</td>
						<td class='bordered centered'>" . date("d-m-y", strtotime($rval->tglposting)) . "</td>
						<td class='bordered centered'>" . date("d-m-y", strtotime($rval->tgljatuhtempo)) . "</td>
						<td class='bordered'>" . number_format($rval->jumlahhutang, 0, ',', '.') . "</td>
						<td class='bordered'>" . number_format($rval->inacbg, 0, ',', '.') . "</td>
						<td class='bordered'>" . number_format($rval->jumlahbayar, 0, ',', '.') . "</td>
						<td class='bordered'>" . number_format($rval->saldopiutang, 0, ',', '.') . "</td>
						<td class='bordered'>" . $rval->invoiceno . "</td>
					</tr>";
        }
        $data .= '<tr>
					<td class="bordered" style="text-align:right;font-weight:bold" colspan="9">Total</td>
					<td class="bordered" colspan="5">' . number_format($totaltagihan, 0, ',', '.') . '</td>
				</tr>
				<tbody>
				</table>';
      } else 
			if ($laporan  == 2) {
        $data .= "<style>
					.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
					.bordered {padding:5px;border:1px solid #222}
					.centered {text-align:center;margin:auto}
					.bold {font-weight:bold}
					.subtitle {font-size:12px}
					.title {font-size:18px;margin-top:10px}
					.separator {border:115px solid #222;}
					.date {font-size:12px}
				</style>";

        $data .= "<table class='table'>	
					<thead>
						<tr>
							<td rowspan='6' align='center'>
								<img src='" . $comp_image . "'  width='100' height='70' />
							</td>
							<td colspan='20'>
								<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
								<tr><td style='font-size:13px;'>$comp_addr</td></tr>
								<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
							</td>
						</tr> 
					</thead>
				</table>
				
				<br />

				<div class='title centered'>" . $title . "</div>
				<div class='date centered'>Dari&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("fromdate"))) . "</b>&emsp;s/d&emsp;<b>" . date("d-m-Y", strtotime($this->input->get("todate"))) . "</b></div>
				
				<br />";

        $data .= '<table class="table">
				<thead>
					<tr>
						<th class="bordered centered">Pelanggan</th>
						<th class="bordered centered">Saldo Awal</th>
						<th class="bordered centered">Belum Jt Tempo</th>
						<th class="bordered centered">1 s/d 30 Hari</th>
						<th class="bordered centered">31 s/d 60 Hari</th>
						<th class="bordered centered">61 s/d 90 Hari</th>
						<th class="bordered centered">181 s/d 365 Hari</th>
						<th class="bordered centered">> 1 Tahun</th>
						<th class="bordered centered">Total Piutang</th>
					</tr>
				</thead>
				<tbody>';

        $t0 = $t1 = $t2 = $t3 = $t4 = $t5 = $t6 = $t7 = 0;
        foreach ($query_list_02 as $rkey => $rval) {
          $total  = $rval->belum_jt + $rval->b1_30 + $rval->b31_60 + $rval->b61_90 + $rval->b181_365 + $rval->lebih_1th;
          $t0 += $rval->belum_jt;
          $t1  += $rval->b1_30;
          $t2  += $rval->b31_60;
          $t4  += $rval->b61_90;
          $t5  += $rval->b181_365;
          $t6  += $rval->lebih_1th;
          $t7  += $total;
          // $tanggalsekarang	= new DateTime($todate);
          // $tanggaljt			= new DateTime($rval->duedate);

          // $betweendate		= $tanggaljt->diff($tanggalsekarang)->format("%r%a");

          // if($lapora == 1){
          // 	$belumjt	= $this->db->query("");
          // }

          // $belum_jt	= $this->db->query("SELECT SUM(jumlahhutang) AS total FROM tbl_apoap 
          // WHERE duedate BETWEEN '". date("Y-m-d", strtotime($fromdate)) ."'")->row();

          // if($)

          // $data		.= "<tr>
          // 	<td class='bordered'>". $rval->vendor_name ."</td>
          // 	<td align='right' class='bordered'>". $belum_jt->total ."</td>
          // 	<td align='right' class='bordered'></td>
          // 	<td align='right' class='bordered'></td>
          // 	<td align='right' class='bordered'></td>
          // 	<td align='right' class='bordered'></td>
          // 	<td align='right' class='bordered'></td>
          // 	<td align='right' class='bordered'></td>
          // </tr>";					
          $data    .= "<tr>
						<td class='bordered'>" . $rval->cust_nama . "</td>
						<td class='bordered'></td>
						<td align=\"right\" class='bordered'>" . number_format($rval->belum_jt, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($rval->b1_30, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($rval->b31_60, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($rval->b61_90, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($rval->b181_365, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($rval->lebih_1th, 0, ',', '.') . "</td>
						<td align=\"right\" class='bordered'>" . number_format($total, 0, ',', '.') . "</td>
					</tr>";
        }
        $data  .= '<tr>
				<td class="bordered">Total</td>
				<td align="right" class="bordered"></td>
				<td align="right" class="bordered">' . number_format($t0, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t1, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t2, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t4, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t5, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t6, 0, ',', '.') . '</td>
				<td align="right" class="bordered">' . number_format($t7, 0, ',', '.') . '</td>
			</tr></tbody></table>';
      }
      // if($laporan == 5){
      // 	$data .= "<style>
      // 		.table {border-collapse:collapse;font-family: Century Gothic;font-size:14px;color:#000;width:100%;margin:auto}
      // 		.bordered {padding:5px;border:1px solid #222}
      // 		.centered {text-align:center;margin:auto}
      // 		.bold {font-weight:bold}
      // 		.subtitle {font-size:12px}
      // 		.title {font-size:18px;margin-top:10px}
      // 		.separator {border:115px solid #222;}
      // 		.date {font-size:12px}
      // 	</style>";

      // 	$data .= "<table class='table'>	
      // 		<thead>
      // 			<tr>
      // 				<td rowspan='6' align='center'>
      // 					<img src='" . $comp_image . "'  width='100' height='70' />
      // 				</td>
      // 				<td colspan='20'>
      // 					<tr><td style='font-size:14px;border-bottom: none;'><b>$comp_name</b></td></tr>
      // 					<tr><td style='font-size:13px;'>$comp_addr</td></tr>
      // 					<tr><td style='font-size:13px;'>Telp :$comp_phone </td></tr>
      // 				</td>
      // 			</tr> 
      // 		</thead>
      // 	</table>

      // 	<br />

      // 	<div class='title centered'>". $title ."</div>
      // 	<div class='date centered'>Dari&emsp;<b>". date("d-m-Y", strtotime($fromdate)) ."</b>&emsp;s/d&emsp;<b>". date("d-m-Y", strtotime($todate)) ."</b></div>

      // 	<br />";

      // 	$data .= '
      // 	<p>Pemasok/Supplier : <b>'. data_master("tbl_vendor", array("vendor_id" => $vendor))->vendor_name .'</b></p><br /><table class="table">
      // 	<thead>
      // 		<tr>
      // 			<th class="bordered centered" style="width:15%">No Bukti</th>
      // 			<th class="bordered centered" style="width:10%">Tanggal</th>
      // 			<th class="bordered centered" style="width:15%">Keterangan</th>
      // 			<th class="bordered centered" style="width:15%">Saldo Awal</th>
      // 			<th class="bordered centered" style="width:15%">Pembelian</th>
      // 			<th class="bordered centered" style="width:15%">Pembayaran</th>
      // 			<th class="bordered centered" style="width:15%">Saldo Akhir</th>
      // 		</tr>
      // 	</thead>
      // 	<tbody>';
      // 	foreach($query_list_03 as $rkey => $rval){
      // 		$data	.= "<tr>
      // 			<td class='bordered'>". $rval->nobukti ."</td>
      // 			<td class='bordered'>". date("Y-m-d", strtotime($rval->tanggal)) ."</td>
      // 			<td class='bordered'>". $rval->ketarangan ."</td>
      // 			<td class='bordered'>". number_format($rval->saldoawal, 0, ',', '.') ."</td>
      // 			<td class='bordered'>". number_format($rval->pembelian, 0, ',', '.') ."</td>
      // 			<td class='bordered'>". number_format($rval->pembayaran, 0, ',', '.') ."</td>
      // 			<td class='bordered'>". number_format($rval->saldoakhir, 0, ',', '.') ."</td>
      // 		</tr>";
      // 	}
      // 	$data	.= "</tbody></table>";
      // } else {
      // 	redirect("/hutang_lap/");
      // }

      $this->M_cetak->mpdf('L', 'LEGAL', $title . " " . date("dmy") . "_" . date("His"), $data, 'LAPORAN_DETAIL_HUTANG_' . date("Ymd") . '.PDF', 0, 0, 9, 2);
    } else {
      redirect("/");
    }
  }

  public function excel()
  {
    $cek   = $this->session->userdata('level');
    $unit  = $this->session->userdata('unit');

    if (!empty($cek)) {
      $data     = "";
      $printinit  = $this->M_cetak->kop2();
      $allcabang  = $this->input->get("allcabang");
      $cabang    = $this->input->get("cabang");
      $allvendor  = $this->input->get("allvendor");
      $vendor    = $this->input->get("vendor");
      $fromdate  = $this->input->get("fromdate");
      $todate    = $this->input->get("todate");
      $laporan  = $this->input->get("laporan");
      $jenis    = $this->input->get("jenis");

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

      $comp_name  = $printinit->namars;
      $comp_addr  = $printinit->alamat;
      $comp_phone  = $printinit->phone;
      $comp_image  = base_url() . $printinit->image;

      if ($allvendor == 1 && $allcabang == 1) {
        $cbg  = "Semua Cabang";
        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = "Semua Cabang";
        $getvendor  = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = data_master("tbl_namers", array("koders"  => $unit))->namars;
        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $cbg  = data_master("tbl_namers", array("koders"  => $unit))->namars;
        $getvendor  = $this->db->query("SELECT * FROM tbl_vendor WHERE vendor_id = '$vendor'")->row();

        if ($jenis == 1) {
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
          $query_list_01  = $this->db->query("SELECT a.fakturno, a.noreg, a.rekmed, CONCAT('nodoc') AS nodoc , a.namapas, b.cust_nama, IF(asal = 'POLI', 'RAJAL', 'RANAP') AS asal, a.tglposting, a.tgljatuhtempo, a.jumlahhutang, a.inacbg, a.jumlahbayar, a.jumlahhutang - a.jumlahbayar AS saldopiutang, a.invoiceno
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

          $query_list_02  = $this->db->query("SELECT a.cust_id,b.cust_nama,
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
        $data  .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
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
          $data  .= "<tr>
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
        $data  .= "<table style='border-collapse:collapse;font-family: Century Gothic;font-size:12px;color:#000;width:100%;margin:auto'>	
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
          $total  = $rval->belum_jt + $rval->b1_30 + $rval->b31_60 + $rval->b61_90 + $rval->b181_365 + $rval->lebih_1th;
          $t0 += $rval->belum_jt;
          $t1  += $rval->b1_30;
          $t2  += $rval->b31_60;
          $t4  += $rval->b61_90;
          $t5  += $rval->b181_365;
          $t6  += $rval->lebih_1th;
          $t7  += $total;
          $data  .= "<tr>
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
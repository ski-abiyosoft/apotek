<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kas_bank extends CI_Model {

	var $table = 'tbl_accounting';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}

	public function getPembayaranHutang($unit, $startdate, $enddate){
		$tgl = "
            AND YEAR(h.pay_date) = YEAR(NOW())
            AND MONTH(h.pay_date) = MONTH(NOW())";

        if($startdate != '' && $enddate != ''){
            $tgl = "AND h.pay_date >= '".$startdate."' AND h.pay_date <= '".$enddate."'";
        }

        $query = "SELECT h.*, b.vendor_name, a.`acname`
				FROM tbl_hap h
					LEFT JOIN tbl_vendor b ON h.vendor_id=b.vendor_id
					INNER JOIN tbl_accounting a ON h.`accountno` = a.`accountno`
				WHERE				   
					h.koders = '$unit'
                    $tgl
                ORDER BY
				h.pay_date, h.ap_id DESC;"
			;
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function getPembayaranHutanById($id, $unit){
        $query = "SELECT h.*, b.vendor_name, a.`acname`
				FROM tbl_hap h
					LEFT JOIN tbl_vendor b ON h.vendor_id=b.vendor_id
					INNER JOIN tbl_accounting a ON h.`accountno` = a.`accountno`
				WHERE				   
					h.koders = '$unit' AND ap_id = '$id'
                ORDER BY
				h.pay_date, h.ap_id DESC;"
			;
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}
	
	public function getDetailPembayaranHutangById($id, $unit){
        $query = "SELECT *
				FROM tbl_dap
				WHERE				   
					koders = '$unit' AND ap_id = '$id'
                "
			;
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function getHutang($koders, $vendor, $startdate, $enddate){	
		$vndr = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}

        $query = "SELECT a.*, v.vendor_name
			FROM tbl_apoap a INNER JOIN tbl_vendor v ON a.`vendor_id` =  v.`vendor_id`
			WHERE a.koders = '$koders' AND tglinvoice >= '$startdate' AND tglinvoice <= '$enddate' 
				$vndr AND tukarfaktur = 1 AND lunas = 0
			;";
			// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}

	public function getTotalBayar($terima_no, $koders){
		
        $query = "SELECT totaltagihan, totalbayar
			FROM tbl_apoap
			WHERE koders = '$koders' AND terima_no = '$terima_no'
			;";
			// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}
}
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hutang extends CI_Model {

	var $table = 'tbl_apoap';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}

	public function getHutangById($id){
		$query = "
			SELECT a.*, v.`vendor_name`
			FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
			WHERE a.id = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function getTukarFakturById($id){
		$query = "
			SELECT a.*, v.`vendor_name`
			FROM tbl_hfaktur a LEFT JOIN tbl_vendor v ON BINARY a.`vendor_id` = v.`vendor_id`
			WHERE a.id = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	
	public function getDetailTukarFakturById($id){
		$query = "
			SELECT a.*, v.vendor_name
			FROM tbl_apoap a
				LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
			WHERE BINARY a.notukar = (
				SELECT notukar
				FROM tbl_hfaktur
				WHERE id = '$id'
			);
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
			return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	public function totalHutang($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT COALESCE(SUM(totaltagihan - totalbayar),0) AS total_hutang
            FROM tbl_apoap
            WHERE koders = '$koders' AND lunas = 0 $vndr 
            ;";
        return $this->db->query($query)->result();
    }

	public function detailTotalHutang($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
            FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
            WHERE koders = '$koders' AND lunas = 0 $vndr 
            ;";
        return $this->db->query($query)->result();
    }

    public function hutangJatuhTempo($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT COALESCE(SUM(COALESCE(totaltagihan,0) - COALESCE(totalbayar,0)),0) AS hutang_jatuh_tempo
            FROM tbl_apoap
            WHERE koders = '$koders' AND lunas = 0 $vndr 
                AND duedate <= DATE(NOW())
            ;";
        return $this->db->query($query)->result();
    }

	public function detailHutangJatuhTempo($koders, $vendor, $startdate="", $enddate="")
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
		FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
            WHERE koders = '$koders' AND lunas = 0 $vndr 
                AND duedate <= DATE(NOW())
            ;";
        return $this->db->query($query)->result();
    }

    
    public function rencanaBayar($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		$tgl_rencana_bayar = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl 				= "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
			$tgl_rencana_bayar  = "AND DATE(tglrencanabayar) >= '$startdate' AND DATE(tglrencanabayar) <= '$enddate'";
		}

        $query = "SELECT COALESCE(SUM(totaltagihan - totalbayar),0) AS rencana_bayar
            FROM tbl_apoap
            WHERE koders = '$koders' AND lunas = 0 $vndr $tgl_rencana_bayar $tgl";
        return $this->db->query($query)->result();
    }

	public function detailRencanaBayar($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		$tgl_rencana_bayar = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl 				= "AND DATE(tglinvoice) >= '$startdate' AND DATE(tglinvoice) <= '$enddate'";
			$tgl_rencana_bayar  = "AND DATE(tglrencanabayar) >= '$startdate' AND DATE(tglrencanabayar) <= '$enddate'";
		}

        $query = "SELECT a.*, v.vendor_name
			FROM tbl_apoap a LEFT JOIN tbl_vendor v ON a.`vendor_id` = v.`vendor_id`
            WHERE koders = '$koders' AND lunas = 0 $vndr $tgl_rencana_bayar $tgl";
        return $this->db->query($query)->result();
    }

    public function realisasiPembayaran($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND pay_date >= '$startdate' AND pay_date <= '$enddate'";
		}
		
        $query = "SELECT SUM(totalbayar) as realisasi_pembayaran
            FROM tbl_hap
            WHERE koders = '$koders' $vndr $tgl";
        
        return $this->db->query($query)->result();
    }

	
    public function detailRealisasiPembayaran($koders, $vendor, $startdate, $enddate)
	{
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND h.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND pay_date >= '$startdate' AND pay_date <= '$enddate'";
		}
		
        $query = "SELECT h.*, h.ket AS keterangan, d.*, d.due_date AS duedate, d.`totalhutang` AS totaltagihan, v.vendor_name 
			FROM tbl_hap h 
				LEFT JOIN tbl_dap d ON h.`ap_id` = d.`ap_id`
				LEFT JOIN tbl_vendor v ON h.`vendor_id` = v.`vendor_id`
            WHERE h.koders = '$koders' $vndr $tgl";
        
        return $this->db->query($query)->result();
    }

	public function getTukarFaktur($koders, $startdate, $enddate, $vendor){
		$vndr = '';
		$tgl = '';
		if($vendor != ''){
			$vndr = "AND h.vendor_id = '$vendor'";
		}
		if($startdate != '' && $enddate != ''){
			$tgl = "AND tanggal >= '$startdate' AND tanggal <= '$enddate'";
		} else {
			$tgl = "AND tanggal >= DATE(NOW())";
		}
		
        $query = "SELECT h.*, v.vendor_name
			FROM tbl_hfaktur h INNER JOIN tbl_vendor v ON BINARY h.`vendor_id` =  v.`vendor_id`
			WHERE koders = '$koders' $tgl $vndr
			;";
		return $this->db->query($query)->result();
		
	}

	public function getTagihan($koders, $vendor, $startdate, $enddate){	
		$vndr = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}
		$tgl = '';
		if($startdate != '' && $enddate != ''){
			$tgl = "AND tglinvoice >= '$startdate' AND tglinvoice <= '$enddate'";
		}

        $query = "SELECT *
			FROM tbl_apoap a INNER JOIN tbl_vendor v ON BINARY a.`vendor_id` =  v.`vendor_id`
			WHERE koders = '$koders' $vndr $tgl
			;";
			// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}

	public function getTagihanById($unit, $dt, $vendor){		
		$terima_no = '';
		for($i=0; $i<count($dt); $i++){
			if($i==0) $terima_no .= "'$dt[$i]'"; 
			else $terima_no .= ",'$dt[$i]'";
		}

		$terima_no = " AND terima_no IN (".$terima_no.")";

		$vndr = '';
		if($vendor != ''){
			$vndr = "AND a.vendor_id = '$vendor'";
		}

		$query = "SELECT *
		FROM tbl_apoap a INNER JOIN tbl_vendor v ON BINARY a.`vendor_id` =  v.`vendor_id`
		WHERE koders = '$unit'  $terima_no $vndr
		;";
		// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}


}

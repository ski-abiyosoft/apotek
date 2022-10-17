<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_penjualan_penerimaan extends CI_Model {

	var $table = 'tbl_papinvoice';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}

	public function getTagihan($koders, $vendor, $startdate, $enddate){	
		$vndr = '';
		if($vendor != ''){
			$vndr = "AND cust_id = '$vendor'";
		}
		$tgl = '';
		if($startdate != '' && $enddate != ''){
			$tgl = "AND invoicedate >= '$startdate' AND invoicedate <= '$enddate'";
		}

        $query = "SELECT *
			FROM tbl_papinvoice 
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

		$terima_no = "invoiceno IN (".$terima_no.")";

		$query = "SELECT *
		FROM tbl_pap
		WHERE $terima_no
		;";
		// AND tukarfaktur = 0
		return $this->db->query($query)->result();	
	}

}

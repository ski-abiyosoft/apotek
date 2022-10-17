<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_rs extends CI_Model {

	var $table = 'tbl_namers';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();	
	}

	public function getNamaRsById($id){
		$query = "
			SELECT *
			FROM tbl_namers
			WHERE koders = '$id'
		";
		$qry = $this->db->query($query)->num_rows();

		if($qry > 0){
		      	return $this->db->query($query)->result();
		} else {
			return false;
		}
	}

	/**
	 * Method untuk mendapatkan infor RS
	 * 
	 * @param string $koders
	 * @return object
	 */
	public function get_hospital_info(string $koders)
	{
		$result = $this->db->select('pejabat1, jabatan1, ketbank, ketbank2, kota, pkpno')->get('tbl_namers')->row();
		$result->kota_full = $result->kota;
		$result->kota = str_replace('KOTA ', '', $result->kota);
		
		$keterangan = ['UTARA', 'TIMUR', 'SELATAN', 'BARAT'];

		foreach($keterangan as $ket){
			$result->kota = ucfirst(strtolower(str_replace($ket, '', $result->kota)));
		}

		return $result;
	}

}
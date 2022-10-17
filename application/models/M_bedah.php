<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_bedah extends CI_Model {

    public function __construct(){
        parent::__construct();
		$this->load->database();
    }

    public function get_data(){
		// $tgl = date('2022-03-26 00:00:00');
		$unit 	= $this->session->userdata("unit");
		$tgl 	= date('Y-m-d');
        $query_data = $this->db->query("SELECT 
		tbl_hbedahjadwal.koders, 
		tbl_hbedahjadwal.nojadwal, 
		tbl_hbedahjadwal.id, 
		tbl_hbedahjadwal.tgloperasi, 
		tbl_hbedahjadwal.noreg, 
		tbl_hbedahjadwal.rekmed, 
		tbl_hbedahjadwal.asdroperator, 
		tbl_hbedahjadwal.droperator, 
		tbl_hbedahjadwal.asdroperatar, 
		tbl_hbedahjadwal.asdrinstrum, 
		tbl_hbedahjadwal.asdrsirkule, 
		tbl_hbedahjadwal.dranestasi, 
		tbl_hbedahjadwal.asdranestasi, 
		tbl_hbedahjadwal.asoperasi, 
		tbl_hbedahjadwal.jam, 
		tbl_hbedahjadwal.jenisok, 
		tbl_hbedahjadwal.ruangok, 
		tbl_hbedahjadwal.userbuat, 
		tbl_hbedahjadwal.status_operasi, 
		tbl_hbedahjadwal.namapas AS namapasien, 
		tbl_regist.jenispas, 
		tbl_regist.tujuan, 
		tbl_regist.kodepos, 
		tbl_regist.kelas, 
		tbl_regist.cust_id, 
		tbl_regist.koderuang, 
		tbl_regist.kodekamar, 
		tbl_ruang.namaruang, 
		tbl_namapos.namapost, 
		tbl_tarifh.tindakan, 
		tbl_dokter.nadokter,
		tbl_pasien.namapas

		FROM
			tbl_hbedahjadwal
			INNER JOIN
			tbl_regist
			ON 
				tbl_hbedahjadwal.noreg = tbl_regist.noreg
			LEFT JOIN
			tbl_ruang
			ON 
				tbl_regist.koderuang = tbl_ruang.koderuang
			LEFT JOIN
			tbl_namapos
			ON 
				tbl_regist.kodepos = tbl_namapos.kodepos
			LEFT JOIN
			tbl_tarifh
			ON 
				tbl_hbedahjadwal.kodetarif = tbl_tarifh.kodetarif
			LEFT JOIN
			tbl_dokter
			ON   
				tbl_hbedahjadwal.droperator = tbl_dokter.kodokter
			LEFT JOIN 
			tbl_pasien
			ON tbl_hbedahjadwal.rekmed = tbl_pasien.rekmed
			where 
			tgloperasi ='$tgl'
			AND tbl_hbedahjadwal.koders = '$unit'
			ORDER BY tbl_hbedahjadwal.nojadwal DESC
			");
		return $query_data->result();
    }

	public function get_data_filter($tgloperasi, $tglakhir){
		$unit 	= $this->session->userdata("unit");
        $query_data = $this->db->query("SELECT 
		tbl_hbedahjadwal.koders, 
		tbl_hbedahjadwal.nojadwal, 
		tbl_hbedahjadwal.id, 
		tbl_hbedahjadwal.tgloperasi, 
		tbl_hbedahjadwal.noreg, 
		tbl_hbedahjadwal.rekmed, 
		tbl_hbedahjadwal.asdroperator, 
		tbl_hbedahjadwal.droperator, 
		tbl_hbedahjadwal.asdroperatar, 
		tbl_hbedahjadwal.asdrinstrum, 
		tbl_hbedahjadwal.asdrsirkule, 
		tbl_hbedahjadwal.dranestasi, 
		tbl_hbedahjadwal.asdranestasi, 
		tbl_hbedahjadwal.asoperasi, 
		tbl_hbedahjadwal.jam, 
		tbl_hbedahjadwal.jenisok, 
		tbl_hbedahjadwal.ruangok, 
		tbl_hbedahjadwal.userbuat, 
		tbl_hbedahjadwal.status_operasi, 
		tbl_hbedahjadwal.namapas AS namapasien, 
		tbl_regist.jenispas, 
		tbl_regist.tujuan, 
		tbl_regist.kodepos, 
		tbl_regist.kelas, 
		tbl_regist.cust_id, 
		tbl_regist.koderuang, 
		tbl_regist.kodekamar, 
		tbl_ruang.namaruang, 
		tbl_namapos.namapost, 
		tbl_tarifh.tindakan, 
		tbl_dokter.nadokter,
		tbl_pasien.namapas
		FROM
			tbl_hbedahjadwal
			INNER JOIN
			tbl_regist
			ON 
				tbl_hbedahjadwal.noreg = tbl_regist.noreg
			LEFT JOIN
			tbl_ruang
			ON 
				tbl_regist.koderuang = tbl_ruang.koderuang
			LEFT JOIN
			tbl_namapos
			ON 
				tbl_regist.kodepos = tbl_namapos.kodepos
			LEFT JOIN
			tbl_tarifh
			ON 
				tbl_hbedahjadwal.kodetarif = tbl_tarifh.kodetarif
			LEFT JOIN
			tbl_dokter
			ON   
				tbl_hbedahjadwal.droperator = tbl_dokter.kodokter
			LEFT JOIN 
			tbl_pasien
			ON tbl_hbedahjadwal.rekmed = tbl_pasien.rekmed
			where 
			tbl_hbedahjadwal.tgloperasi BETWEEN '$tgloperasi' AND '$tglakhir'
			AND tbl_hbedahjadwal.koders = '$unit'");
		return $query_data->result();
    }
	
    public function get_data_cek($id=null){
		// $this->db->from('tbl_hbedahjadwal');
        // if ($id != null) {
        //     $this->db->where('id', $id);
        // }
		// $this->db->limit(5,10);
        // $query = $this->db->get();
        // return $query->result();
		$query = $this->db->query("SELECT tbl_hbedahjadwal.nojadwal, tbl_hbedahjadwal.status_operasi, tbl_hbedahjadwal.noreg, tbl_hbedahjadwal.userbuat, 
		tbl_hbedahjadwal.droperator,tbl_hbedahjadwal.rekmed,tbl_hbedahjadwal.tgloperasi, tbl_tarifh.tindakan , tbl_dokter.nadokter,tbl_regist.jenispas
		FROM tbl_hbedahjadwal
		INNER JOIN tbl_tarifh ON tbl_hbedahjadwal.kodetarif = tbl_tarifh.kodetarif 
		INNER JOIN tbl_dokter ON tbl_hbedahjadwal.droperator = tbl_dokter.kodokter  
		INNER JOIN tbl_regist ON tbl_hbedahjadwal.noreg = tbl_regist.noreg  
		
		limit 5,5");
		// $this->db->limit(5,10);
		return $query->result();
		
    }
	
}
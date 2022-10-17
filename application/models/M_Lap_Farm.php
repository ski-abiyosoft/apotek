<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_Lap_Farm extends CI_Model {

    public function __construct(){
        parent::__construct();
		$this->load->database();
    }
    public function get_data_lapFarm3(){
        $query = $this->db->query("SELECT a.kodebarang, a.namabarang, b.satuan, b.qty_terima, b.koders FROM tbl_barang AS a
        LEFT JOIN tbl_barangdterima AS b ON a.kodebarang = b.kodebarang
        WHERE b.koders = koders ")->result();
        // var_dump($query);die;
    }
	
}
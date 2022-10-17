<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_jurnall extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '205');
	}
			
	public function _tanggal()
	{
		return date('d-m-Y');
	}
		
	
	function manualQuery($q)
	{
		return $this->db->query($q);
	}
	
	function insertData($table,$data)
	{
		$this->db->insert($table,$data);
	}
		
	public function hapus_jurnal($no)
	{			    
	    $this->db->where('nojurnal', $no);
		$this->db->delete('tr_jurnald');
		$this->db->where('nojurnal', $no);
        $this->db->delete('tr_jurnalh');		
	}

}

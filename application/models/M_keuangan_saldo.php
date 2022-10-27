<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan_saldo extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->session->set_userdata('menuapp', '100');
		$this->session->set_userdata('submenuapp', '112');
	}
		
	public function update_saldo($tahun,$bulan,$bank,$saldo)
	{	        
		$q   = $this->db->query("select * from ms_banksaldo where tahun = '$tahun' and bulan='$bulan' and bank_kode = '$bank'");				
		if($q->num_rows()==0)
		{
		  return $this->db->query("insert into ms_banksaldo(tahun, bulan, bank_kode, saldo_awal) values('$tahun','$bulan','$bank','$saldo')");		  
		} 
		else
		{	        
		  return $this->db->query("update ms_banksaldo set saldo_awal='$saldo' where tahun = '$tahun' and bulan='$bulan' and bank_kode = '$bank'");	    
		} 	
     
	}


}

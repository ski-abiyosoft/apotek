<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_saldo extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		
	}
		
	public function update_saldo($tahun,$bulan,$akun,$debet,$kredit)
	{	        
		$q   = $this->db->query("select * from ms_akunsaldo where tahun = '$tahun' and bulan='$bulan' and kodeakun = '$akun'");				
		if($q->num_rows()==0)
		{
		  return $this->db->query("insert into ms_akunsaldo(tahun, bulan, kodeakun, debet, kredit) values('$tahun','$bulan','$akun','$debet','$kredit')");		  
		} 
		else
		{	        
		  return $this->db->query("update ms_akunsaldo set debet='$debet', kredit='$kredit' where tahun = '$tahun' and bulan='$bulan' and kodeakun = '$akun'");	    
		} 	
     
	}


}

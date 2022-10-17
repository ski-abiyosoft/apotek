<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_formatlap extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();		
	}
		
	public function update_formatlap($kode, $nama, $jenis)
	{	        
		$q   = $this->db->query("select * from ms_format where kode= '$kode'");				
		if($q->num_rows()==0)
		{
		  return $this->db->query("insert into ms_format(kode,nama,jenis) values('$kode', '$nama', '$jenis')");		  
		} 
		else
		{	        
		  return $this->db->query("update ms_format set nama= '$nama', jenis='$jenis' where kode='$kode'");	    
		} 	   
	}
	
	public function delete_by_id($id)
	{
		$this->db->where('kode', $id);
		$this->db->delete('ms_format');
	}


}

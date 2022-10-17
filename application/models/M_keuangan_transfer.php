<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan_transfer extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
		
	}
		
	
	public function nomor_register()
	{
		
         $query = "select nilai from nomor_auto where id=5";
         $hasil = $this->db->query($query)->result();
		 foreach($hasil as $row){
		 $no = $row->nilai;	    
         }
		 
		 $thn    = date("y");
		 $bln    = date("m");
		 $unit   = $this->session->userdata('unit');

	     $trn    = "TR";
         $nomor  = "$unit$trn$thn$bln$no";

         return $nomor;
    }
	
	public function update()
	{			
	   $this->M_global->_updatecounter1('KT');
	}
	
	function input_data($data,$table){
		$this->db->insert($table,$data);
	}
 
	function hapus_data($where,$table){
		$this->db->where($where);
		$this->db->delete($table);
	}
	
	function update_data($where,$data,$table){
		$this->db->where($where);
		$this->db->update($table,$data);
	}	
	
	public function hapus_transfer($no)
	{			    
	    $this->db->where('nomor', $no);
		$this->db->delete('tr_transfer');
			
	}


}

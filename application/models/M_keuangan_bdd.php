<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_keuangan_bdd extends CI_Model {
	public function __construct()
	{
		 parent::__construct();
		 $this->session->set_userdata('menuapp', '100');
		 $this->session->set_userdata('submenuapp', '102');
	}
		
	public function nomor_register()
	{
		 $query = "select nilai from nomor_auto where id=8";
		 $hasil = $this->db->query($query)->result();
		 foreach($hasil as $row){
		 $no = $row->nilai;	 
	 }  	
     return $no;	
    }
	
	public function update($jn)
	{			
		$this->db->query('update nomor_auto set nilai=nilai+1 where id=8');						
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
	
	public function hapus_bdd($no)
	{			    
	    $this->db->where('nomor_register', $no);
		$this->db->delete('tr_bdd');
		$this->db->where('nomor_register', $no);
        $this->db->delete('tr_bddd');		
	}


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_jurnal extends CI_Model {
    
    
	
	public function __construct()
	{
		parent::__construct();
		//$this->load->database();
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '204');
	}
		
	
	public function nomor_jurnal($unit, $jenis, $thn, $bln)
	{
		$q = $this->db->query("select nilai from nomor_auto where id=1");
		$kd = "";
		if($q->num_rows()>0)
		{
			foreach($q->result() as $k)
			{
				$tmp = ((int)$k->nilai)+1;
				$kd = sprintf("%06s", $tmp);
			}
		}
		else
		{
			$kd = "000001";
		}							
		return "$unit$jenis$thn$bln$kd";
	}
	
	public function _tanggal()
	{
		return date('d-m-Y');
	}
		
	
	function manualQuery($q)
	{
		return $this->db->query($q);
	}
	
	public function update()
	{			    
	    $this->db->query('update nomor_auto set nilai=nilai+1 where id=1');				
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

}

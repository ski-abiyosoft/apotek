<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akuntansi_ju extends CI_Model {

   
	var $table = 'tbl_jurnalh';
	var $column_order = array('id', 'koders','nobukti','tgljurnal','keterangan','tutup','user_name',null); 
	var $column_search = array('id', 'koders','nobukti','tgljurnal','keterangan','tutup','user_name'); 
	var $order = array('id' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query($jns, $bulan, $tahun )
	{   
	    
		if($jns==1){
			$tanggal = date('Y-m-d');
			$this->db->select('tbl_jurnalh.*')->from('tbl_jurnalh');
			$this->db->where(array('tgljurnal' => $tanggal));
			$this->db->order_by('tgljurnal, nobukti');
		} else {
		    $this->db->select('tbl_jurnalh.*')->from('tbl_jurnalh');
			$this->db->where(array('tgljurnal >=' => $bulan,'tgljurnal<= ' => $tahun));
			$this->db->order_by('tgljurnal, nobukti');
			
			
		}
		
		
		$i = 0;
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{				
				if($i===0) // first loop
				{
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
					
			}
			$i++;
		}
		
		if(isset($_POST['order'])) // here order processing
		{
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	
	function get_datatables( $jns,  $bulan, $tahun )
	{
		
		$this->_get_datatables_query($jns, $bulan, $tahun );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);	
		$query = $this->db->get();
		return $query->result();
	}
	
	function count_filtered( $jns, $bulan, $tahun )
	{
		$this->_get_datatables_query( $jns, $bulan, $tahun );
		$query = $this->db->get();
		return $query->num_rows();
	}
	

	
	public function count_all( $jns, $bulan, $tahun )
	{
		if($jns==1){
			$this->db->select('tbl_jurnalh.*')->from('tbl_jurnalh');
			$this->db->where(array('year(tgljurnal)' => $tahun,'month(tgljurnal)' => $bulan));
		} else {
		    $this->db->select('tbl_jurnalh.*')->from('tbl_jurnalh');
			$this->db->where(array('tgljurnal >=' => $bulan,'tgljurnal<= ' => $tahun));
			
		}
		
		return $this->db->count_all_results();
	}
	
	
	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}


}


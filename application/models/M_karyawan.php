<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_karyawan extends CI_Model {

	var $table = 'hrd_karyawan';
	var $column_order = array('id','nama','noktp',null); 
	var $column_search = array('id','nama','noktp');
	var $order = array('id' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);
		/*
		$this->db->select( 'hrd_karyawan.*, hrd_jabatan.nama as namajabatan, ms_unit.nama as namacabang ');
		$this->db->from( 'hrd_karyawan');
		$this->db->join( 'hrd_jabatan', 'hrd_karyawan.jabatan_id = hrd_jabatan.kode', 'left' );	
		$this->db->join( 'ms_unit', 'hrd_karyawan.unit_id = ms_unit.kode', 'left' );	
		*/
		
		$i = 0;
		
	    
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
			{
				$this->db->like($item, $_POST['search']['value']);
				
				if($i===0) 
				{
					
					$this->db->like($item, $_POST['search']['value'],'both');
				}
				else
				{
					$this->db->or_like($item, $_POST['search']['value'],'both');
				}
                
				
				
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

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
		
		/*$this->db->select( 'hrd_karyawan.*' );
		$this->db->join( 'hrd_jabatan', 'hrd_karyawan.jabatan_id = hrd_jabatan.kode', 'left' );		
		$this->db->order_by( 'hrd_karyawan.id', 'desc' );
		return $this->db->where( 'hrd_karyawan', array( '' ) )->count_all_results();
		*/
		
		
		
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

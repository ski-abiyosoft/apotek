<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kasir extends CI_Model {

	var $table = 'ar_customer';
	var $column_order = array('kode','nama','alamat1','kota','contactname',null); 
	var $column_search = array('kode','nama','alamat1','kota','contactname');
	var $order = array('kode' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->session->set_userdata('menuapp', '400');
		$this->session->set_userdata('submenuapp', '410');
	}

	private function _get_datatables_query()
	{
		
		$this->db->from($this->table);
		
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
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->from($this->table);
		$this->db->where('kode',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save_detail($data)
	{
		$this->db->insert('sidetail', $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_detail_by_id($id)
	{
		$this->db->where('id', $id);
		$this->db->delete('sidetail');
	}
	
	public function save_data($data)
	{
		$this->db->insert('kasirtrn', $data);
		return $this->db->insert_id();
	}
	
	public function delete_by_id($id)
	{
		$this->db->where('kodesi', $id);
		$this->db->delete('sidetail');
		$this->db->where('kodesi', $id);
		$this->db->delete('sihdrfile');				
	}
	
	public function update_kirim($where, $data)
	{
		$this->db->update('sidetail', $data, $where);
		return $this->db->affected_rows();
	}
	
	
	
	
	
	


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_harga_barang extends CI_Model {

	var $table = 'inv_barang_harga a';
	var $column_order = array('a.kodeitem','b.namabarang','a.hargajual_net','a.kode_customer','c.nama',null); 
	var $column_search = array('a.kodeitem','b.namabarang','a.hargajual_net','a.kode_customer','c.nama');
	var $order = array('a.kodeitem' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		$this->db->join('inv_barang b','a.kodeitem=b.kodeitem','left');
		$this->db->join('ar_customer c','a.kode_customer=c.kode','left');
		/*
		$this->db->query("select inv_barang_harga.*, inv_barang.kodeitem, inv_barang.namabarang, ar_customer.nama as namacustomer
		from inv_barang_harga inner join inv_barang on inv_barang_harga.id_barang=inv_barang.id
		inner join ar_customer on inv_barang_harga.kode_customer=ar_customer.kode
		
		");
		*/
		
		$i = 0;
		
	    
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
			{
				
				if($i===0) 
				{
					
					$this->db->like($item, $_POST['search']['value']);
				}
				
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
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
		$this->db->where('kodeitem',$id);
		$query = $this->db->get();

		return $query->row();
	}
	
	public function getmarginsetting()
	{
		return $this->db->get('ms_identity')->row();				
	}

	public function save($data)
	{
		$this->db->insert('inv_barang_harga', $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update('inv_barang_harga', $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('kodeitem', $id);
		$this->db->delete($this->table);
	}


}

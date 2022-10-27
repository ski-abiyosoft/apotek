<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_promo extends CI_Model {

	var $table = 'inv_promo';
	var $column_order = array('tglawal','tglakhir','kodeitem','qty','satuan','hrg1','hrg2','hrg3',null); 
	var $column_search = array('tglawal','tglakhir','kodeitem','qty','satuan','hrg1','hrg2','hrg3');
	var $order = array('kodeitem' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('M_barang');
		$this->load->model('M_param');
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);

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
		return $query->nuM_rows();
	}

	public function count_all()
	{
		$this->db->from($this->table);
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
	
	public function get_by_tglberlaku($tanggal)
	{
		$this->db->from($this->table);
		$this->db->where('tglberlaku',$tanggal);
		$query = $this->db->get();
		return $query->result();
	}
	
	
	
		


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hargajualm extends CI_Model {

	var $table = 'inv_hrgjualm';
	var $column_order = array('kodeitem','hargajual1',null); 
	var $column_search = array('kodeitem','hargajual1');
	var $order = array('kodeitem' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->session->set_userdata('menuapp', '500');
		$this->session->set_userdata('submenuapp', '522');
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
		$this->db->where('tglhrgjualm',$tanggal);
		$query = $this->db->get();
		return $query->result();
	}
	
	public function process_sethargam()
	{
		$tanggal = date('Y-m-d');
		$data = $this->get_by_tglberlaku($tanggal);
		foreach($data as $row){
		    $_item     = $row->kodeitem;
			$_harga1   = $row->hargajual1;
			$_harga2   = $row->hargajual2;
			$_harga3   = $row->hargajual3;
								
			$data_barang = array(
			'kodeitem'    => $_item,			
			'hargajual1'  => $_harga1,
			'hargajual2'  => $_harga2,
			'hargajual3'  => $_harga3,
			);
			
			$update = $this->m_barang->update(array('kodeitem' => $_item), $data_barang);
            			
			$data_setting = array(
			'tglhargajualm'=> $tanggal,
			'rubahhrgm'    => 'T',			
			);
												
			$this->m_param->updatedata_id($data_setting);			            						
		} 
	}


}

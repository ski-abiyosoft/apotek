<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_akun extends CI_Model {

	var $table = 'tbl_accounting';
	var $column_order = array('id','accountno','acname','aclevel','kasbank','aktif',null); 
	var $column_search = array('id','accountno','acname','aclevel','kasbank','aktif'); 
	var $order = array('id' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query()
	{
		$this->db->from($this->table);
		
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

	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	/**
	 * Method untuk mendapatkan seluruh data
	 * 
	 * @return array
	 */
	public function get_all()
	{
		return $this->db->get($this->table)->result();
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

	public function get_saldo_awal($koders, $accountno)
	{
		$query = "SELECT * 
					FROM tbl_acsaldo 
					WHERE accountno = '".$accountno."' AND koders = '".$koders."' 
					GROUP BY tahun";
		return $this->db->query($query)->result();
		// return ?$query->row();
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

	public function get_saldo_awal_by_tahun($koders, $accountno, $tahun){
		$query = "SELECT *
				FROM tbl_acsaldo
				WHERE koders = '$koders' AND accountno = '$accountno' AND tahun = '$tahun'";
		return $this->db->query($query)->result();
	}

	public function cek_tahun($koders, $accountno, $tahun){
		$query = "SELECT *
				FROM tbl_acsaldo
				WHERE koders = '$koders' AND accountno = '$accountno' AND tahun = '$tahun'";
		return $this->db->query($query)->num_rows();
	}

	public function insert_acsaldo($data){
		$this->db->insert('tbl_acsaldo', $data);
		return $this->db->insert_id();
	}

	public function update_acsaldo($where, $data)
	{
		$this->db->update('tbl_acsaldo', $data, $where);
		return $this->db->affected_rows();
	}

}

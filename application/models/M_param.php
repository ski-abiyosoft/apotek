<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_param extends CI_Model {

	var $table = 'ms_identity';
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
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
		$this->db->where('bank_kode',$id);
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
		$this->db->where('bank_kode', $id);
		$this->db->delete($this->table);
	}
	
	public function updatedata_id($data)
	{
		$this->db->update('ms_identity', $data);
		
	}
	
	public function updatedata_nomor($data,$id)
	{
		$this->db->where('id', $id);
		$this->db->update('nomor_auto', $data);
		
	}


}

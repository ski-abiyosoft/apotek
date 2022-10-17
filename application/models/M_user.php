<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

	var $table = 'userlogin';
	var $column_order = array('userlogin.uidlogin','userlogin.username','userlogin.cabang','ms_modul_grup.nmgrup','tbl_namers.namars',null); //set column field database for datatable orderable
	var $column_search = array('userlogin.uidlogin','userlogin.username','userlogin.cabang','ms_modul_grup.nmgrup','tbl_namers.namars'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('userlogin.uidlogin' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->session->set_userdata('menuapp', '900');
		$this->session->set_userdata('submenuapp', '901');
	}

	private function _get_datatables_query()
	{						
	    $unit 	  = $this->session->userdata('unit');
		$userid   = $this->session->userdata('username');
		$this->db->from($this->table);
		$this->db->join('ms_modul_grup','ms_modul_grup.nomor=userlogin.level');
		$this->db->join('tbl_namers','tbl_namers.koders=userlogin.cabang','left');
        //if($unit !="" && $userid!="admin"){
		//   $this->db->where('userlogin.cabang', $unit);	
		//} 
		$i = 0;
		
	    
		foreach ($this->column_search as $item) // loop column 
		{
			if($_POST['search']['value']) // if datatable send POST for search
			{
				
				if($i===0) // first loop
				{
					
					//$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				}
				
				else
				{
					$this->db->or_like($item, $_POST['search']['value']);
				}

				
				/*
				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
				*/
				
				
				
					
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

	// public function get_by_id($id)
	// {
	// 	$this->db->from($this->table);
	// 	$this->db->join('userlogincabang ulc', 'ulc.uidlogin = userlogin.uidlogin');
	// 	$this->db->where('userlogin.uidlogin',$id);
	// 	$query = $this->db->get();

	// 	return $query->row();
	// }

	public function get_by_id($id){
		
		$this->db->from($this->table);
		$this->db->where('uidlogin',$id);
		$query = $this->db->get();
		
		
		return $query->row();
	}

	public function save(){
		/*
		Rizki
		*/
		$cabang = array();

		foreach($this->input->post("cabang") as $cabangFinal){
			$cabang[] = $cabangFinal;
			// $this->db->query("INSERT INTO userlogincabang (uidlogin,koders) VALUES ('". $this->input->post('uidlogin') ."','". $cabangFinal ."')");
		}

		$data = array(
			'uidlogin'   => $this->input->post('uidlogin'),
			'username'   => $this->input->post('karyawan'),
			'job_role'   => $this->input->post('jobsrole'),
			'pwdlogin'   => md5($this->input->post('password')),
			'cabang'     => implode(",", $cabang),
			'level'      => $this->input->post('grup'),
			'user_level' => $this->input->post('ulev'),
			'locked'     => 0,
		);
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data){
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where('uidlogin', $id);
		$this->db->delete($this->table);
	}
	
	
	
	function input_data($data,$table){
		$this->db->insert($table,$data);
	}
	
	function manualquery($query){
		$this->db->query($query);
	}


}

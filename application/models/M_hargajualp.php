<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_hargajualp extends CI_Model {

	var $table = 'inv_discp3';
	var $column_order = array('kodeitem','pricelist','tglberlaku',null); 
	var $column_search = array('kodeitem','pricelist','tglberlaku');
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
	
	public function process_discp3()
	{
		$tanggal = date('Y-m-d');
		$data = $this->get_by_tglberlaku($tanggal);
		foreach($data as $row){
		    $_item     = $row->kodeitem;
			$_pricelist= $row->pricelist;
			$_disc11   = $row->disc11;
			$_disc12   = $row->disc12;
			$_disc13   = $row->disc13;
			$_disc21   = $row->disc21;
			$_disc22   = $row->disc22;
			$_disc23   = $row->disc23;
			$_disc31   = $row->disc31;
			$_disc32   = $row->disc32;
			$_disc33   = $row->disc33;
									
			$hrg1   = $_pricelist - ($_pricelist * ($_disc11/100));
			if($_disc12!=0){
			$hrg1   = $hrg1 - ($hrg1*($_disc12/100));
			}
			if($_disc13!=0){
			$hrg1   = $hrg1 - ($hrg1*($_disc13/100));
			}
			
			$hrg2   = $_pricelist - ($_pricelist * ($_disc21/100));
			if($_disc22!=0){
			$hrg2   = $hrg2 - ($hrg2*($_disc22/100));
			}
			if($_disc23!=0){
			$hrg2   = $hrg2 - ($hrg2*($_disc23/100));
			}
			
			$hrg3   = $_pricelist - ($_pricelist * ($_disc31/100));
			if($_disc32!=0){
			$hrg3   = $hrg3 - ($hrg3*($_disc32/100));
			}
			if($_disc33!=0){
			$hrg3   = $hrg3 - ($hrg3*($_disc33/100));
			}
						
			$data_barang = array(
			'kodeitem'    => $_item,			
			'hargajual1'  => $hrg1,
			'hargajual2'  => $hrg2,
			'hargajual3'  => $hrg3,
			);
			
			$update = $this->M_barang->update(array('kodeitem' => $_item), $data_barang);
            			
			$data_setting = array(
			'tglpro'      => $tanggal,
			'rubahhrg'    => 'T',			
			);
												
			$this->M_param->updatedata_id($data_setting);			            						
		} 
	}
	
	
		


}

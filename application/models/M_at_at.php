<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_at_at extends CI_Model {

	var $table = 'tbl_fixedasset';
	var $column_order = array('id','koders','kodefix','serialno','namafix','date(tglaku) as tglaku1',null); //set column field database for datatable orderable
	var $column_search = array('id','koders','kodefix','serialno','namafix'); //set column field database for datatable searchable just firstname , lastname , address are searchable
	var $order = array('id' => 'asc'); // default order 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('M_penyusutan', 'penyusutan');
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
		
		if($_POST['length'] != -1){
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		
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
		$this->db->select('tbl_fixedasset.*, date_format(tglaku, "%e-%m-%Y") as tglaku1,date_format(tglpakai, "%e-%m-%Y") as tglpakai1,
        date_format(tglkalibrasi, "%e-%m-%Y") as tglkalibrasi1');
		$this->db->from($this->table);
		$this->db->where('tbl_fixedasset.id',$id);
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

	/**
	 * Method untuk mendapatkan data aktiva tetap
	 * 
	 * @return array
	 */
	public function get_fa_data(string $cabang = null)
	{
		$fa = [];

		if ($cabang){
			$fa = $this->db->select('tbl_fixedasset.*, tbl_fixed.groupname, tbl_fixed.tahunsusut, tbl_fixed.fixrate, tbl_fixed.metode')
			->from('tbl_fixedasset')->join('tbl_fixed', 'tbl_fixedasset.fixid = tbl_fixed.fixid')
			->where('tbl_fixedasset.koders', $cabang)->get()->result();
		}else{
			$fa = $this->db->get('tbl_fixedasset')->join('tbl_fix', 'tbl_fixedasset.fixid = tbl_tblfix.fixid')
			->get()->result();
		}

		if (count($fa) > 0){
			for ($i = 0; $i < count($fa); $i++){
				$depreciation = $this->penyusutan->get_susut($fa[$i]->kodefix, date('Y-M-d'));

				if (isset($depreciation)){
					$disposable = count($depreciation->depreciation_list) == $fa[$i]->tahunsusut * 12;
					$fa[$i]->depreciation = $depreciation;
					$fa[$i]->disposable = $disposable;
				}else {
					$fa[$i]->depreciation = null;
					$fa[$i]->disposable = false;
				}
			}
		}

		return $fa;
	}

	/**
	 * Method untuk mendapatkan data laporan daftar aktiva
	 * 
	 * @return object
	 */
	public function get_fa_list_report_data(string $cabang)
	{
		$fa_data = array_filter($this->get_fa_data($cabang), function ($item){
						return $item->disposable == false;
					});
		$fa_group = $this->db->select('fixid, groupname')->get('tbl_fixed')->result();
		$result = (object) [];
		$groups = [];

		for ($i=0; $i < count($fa_group); $i++) { 
			$fa_list = array_filter($fa_data, function ($item) use($fa_group, $i) {
				return $item->fixid == $fa_group[$i]->fixid;
			});
			$fa_total = array_reduce($fa_list, function ($carry, $item) {
				$carry += $item->nilaiaktiva;
				return $carry;
			});
			$depreciation_total = array_reduce($fa_list, function ($carry, $item) {
				$carry += $item->depreciation->depreciation_total ?? 0;
				return $carry;
			});
			
			array_push($groups, (object) [
				'groupname' => $fa_group[$i]->groupname,
				'fa_list' => $fa_list,
				'group_fa_total' => $fa_total,
				'group_depreciation_total' => $depreciation_total,
				'group_bv_total' => $fa_total - $depreciation_total
			]);
		}

		$result->fa_groups = $groups;
		$result->fa_total = array_reduce($groups, function ($carry, $item){
			$carry += $item->group_fa_total;
			return $carry;
		});
		$result->fa_depreciation_total = array_reduce($groups, function ($carry, $item){
			$carry += $item->group_depreciation_total;
			return $carry;
		});
		$result->fa_bv_total = array_reduce($groups, function ($carry, $item){
			$carry += $item->group_bv_total;
			return $carry;
		});
		
		return $result;
	}

	/**
	 * Method untuk mendapatkan data laporan disposable fixed asset
	 * 
	 * @return object
	 */
	public function get_disposable_fa_report_data(string $cabang)
	{
		$fa_data = array_filter($this->get_fa_data($cabang), function ($item){
			return $item->disposable == true;
		});
		$fa_group = $this->db->select('fixid, groupname')->get('tbl_fixed')->result();
		$result = (object) [];
		$groups = [];

		for ($i=0; $i < count($fa_group); $i++) { 
		$fa_list = array_filter($fa_data, function ($item) use($fa_group, $i) {
			return $item->fixid == $fa_group[$i]->fixid;
		});
		$fa_total = array_reduce($fa_list, function ($carry, $item) {
			$carry += $item->nilaiaktiva;
			return $carry;
		});
		$depreciation_total = array_reduce($fa_list, function ($carry, $item) {
			$carry += $item->depreciation->depreciation_total ?? 0;
			return $carry;
		});

		array_push($groups, (object) [
			'groupname' => $fa_group[$i]->groupname,
			'fa_list' => $fa_list,
			'group_fa_total' => $fa_total,
			'group_depreciation_total' => $depreciation_total,
			'group_bv_total' => $fa_total - $depreciation_total
		]);
		}

		$result->fa_groups = $groups;
		$result->fa_total = array_reduce($groups, function ($carry, $item){
		$carry += $item->group_fa_total;
		return $carry;
		});
		$result->fa_depreciation_total = array_reduce($groups, function ($carry, $item){
		$carry += $item->group_depreciation_total;
		return $carry;
		});
		$result->fa_bv_total = array_reduce($groups, function ($carry, $item){
		$carry += $item->group_bv_total;
		return $carry;
		});

		return $result;
	}

	/**
	 * Method untuk mendapatkan nilai auto increment
	 * 
	 * @return string
	 */
	public function get_auto_increment(): string
	{
		return $this->db->query("SHOW table status LIKE 'tbl_fixedasset'")->row()->Auto_increment;
	}
}
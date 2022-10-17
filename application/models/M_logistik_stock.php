<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_logistik_stock extends CI_Model {

	var $table = 'tbl_apostocklog';
	var $column_order = array('tbl_apostocklog.koders', 'tbl_apostocklog.id','tbl_logbarang.satuan1','tbl_apostocklog.kodebarang','tbl_apostocklog.gudang',
	'tbl_logbarang.namabarang','tbl_apostocklog.tglso','tbl_apostocklog.periodedate',
	'tbl_apostocklog.saldoakhir',null); 
	var $column_search = array('tbl_apostocklog.koders','tbl_apostocklog.id','tbl_logbarang.satuan1','tbl_apostocklog.kodebarang','tbl_apostocklog.gudang',
	'tbl_logbarang.namabarang','tbl_apostocklog.tglso','tbl_apostocklog.periodedate',
	'tbl_apostocklog.saldoakhir');
	var $order = array('tbl_apostocklog.id' => 'asc'); 

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query( $cabang, $gudang = null ){
		$this->db->select('tbl_apostocklog.*,tbl_logbarang.kodebarang,tbl_logbarang.namabarang, tbl_logbarang.satuan1')->from('tbl_apostocklog');
		$this->db->join('tbl_logbarang','tbl_logbarang.kodebarang=tbl_apostocklog.kodebarang');
		$this->db->where('tbl_apostocklog.koders', $cabang);

		if($gudang!=""){
			$this->db->where('tbl_apostocklog.gudang', $gudang);	
		  }
		
		$i = 0;
		
		foreach ($this->column_search as $item) {
			if($_POST['search']['value']) {
				
				if($i===0){
					$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}

				if(count($this->column_search) - 1 == $i) //last loop
					$this->db->group_end(); //close bracket
			}
			$i++;
		}
		
		if(isset($_POST['order'])){
			$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else 
		if(isset($this->order)){
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables( $cabang, $gudang ){
		$this->_get_datatables_query( $cabang, $gudang );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered( $cabang ){
		$this->_get_datatables_query( $cabang );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all(){
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id){		
		$this->db->select('tbl_tarif.*,tbl_tarifh.tindakan, tbl_tarifh.kodepos')->from('tbl_tarif');
		$this->db->join('tbl_tarifh','tbl_tarifh.kodetarif=tbl_tarif.kodetarif');		
		$this->db->where('tbl_tarif.id',$id);
		$query = $this->db->get();

		return $query->row();
	}

	public function save($data){
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data){
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id){
		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}


}

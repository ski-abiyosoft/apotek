<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_farmasi_pbb extends CI_Model {

   
	var $table = 'tbl_apohmohon';
	var $column_order = array('id', 'koders','po_no','mohonno','tglmohon','dari','ke','keterangan',null); 
	var $column_search = array('id', 'koders','po_no','mohonno','tglmohon','dari','ke','keterangan'); 
	var $order = array('id' => 'asc'); 

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	public function list(){
		$unit 		= $this->session->userdata("unit");
		$tanggal 	= date("Y-m-d");
		return $this->db->query("SELECT a.id, a.koders, a.username, a.mohonno, a.tglmohon, a.dari, a.ke, a.keterangan
		FROM tbl_apohmohon AS a
		WHERE a.tglmohon LIKE '%$tanggal%'
		AND a.koders = '$unit'
		ORDER BY a.tglmohon, a.mohonno ASC");
	}

	public function list_by_date($dari, $ke){
		$unit 		= $this->session->userdata("unit");
		$tanggal 	= date("Y-m-d");
		return $this->db->query("SELECT a.id, a.koders, a.username, a.mohonno, a.tglmohon, a.dari, a.ke, a.keterangan
		FROM tbl_apohmohon AS a
		WHERE a.tglmohon BETWEEN '$dari' AND '$ke'
		AND a.koders = '$unit'
		ORDER BY a.tglmohon, a.mohonno ASC");
	}

	// private function _get_datatables_query($jns, $bulan, $tahun ){   
	//     $unit=$this->session->userdata('unit');	
	// 	if($jns==1){
	// 		$tanggal = date('Y-m-d');
	// 		$this->db->select('tbl_apohmohon.*, tbl_apohmohon.keterangan AS justket')->from('tbl_apohmohon');
	// 		$this->db->where(array('tglmohon' => $tanggal));
	// 		$this->db->where(array('koders' => $unit));
	// 		$this->db->order_by('tglmohon, mohonno');

	// 		// $this->db->query("SELECT  a.koders, a.username, a.mohonno, a.tglmohon, a.dari, a.ke, a.keterangan
	// 		// FROM tbl_apohmohon AS a
	// 		// WHERE a.tglmohon LIKE '%$tanggal%'
	// 		// AND a.koders = '$unit'
	// 		// ORDER BY a.tglmohon, a.mohonno ASC");
	// 	} else {
	// 	    $this->db->select('tbl_apohmohon.*, tbl_apohmohon.keterangan AS justket, tbl_depo.*')->from('tbl_apohmohon');
	// 		$this->db->join('tbl_depo', 'tbl_depo.depocode = tbl_apohmohon.id', 'left');
	// 		$this->db->where(array('tglmohon >=' => $bulan,'tglmohon<= ' => $tahun));
	// 		$this->db->where(array('koders' => $unit));
	// 		$this->db->order_by('tglmohon, mohonno');
	// 	}
		
		
	// 	$i = 0;
	// 	foreach ($this->column_search as $item) // loop column 
	// 	{
	// 		if($_POST['search']['value']) // if datatable send POST for search
	// 		{				
	// 			if($i===0) // first loop
	// 			{
	// 				$this->db->group_start(); // open bracket. query Where with OR clause better with bracket. because maybe can combine with other WHERE with AND.
	// 				$this->db->like($item, $_POST['search']['value']);
	// 			}
	// 			else
	// 			{
	// 				$this->db->or_like($item, $_POST['search']['value']);
	// 			}

	// 			if(count($this->column_search) - 1 == $i) //last loop
	// 				$this->db->group_end(); //close bracket
					
	// 		}
	// 		$i++;
	// 	}
		
	// 	if(isset($_POST['order'])) // here order processing
	// 	{
	// 		$this->db->order_by($this->column_order[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
	// 	} 
	// 	else if(isset($this->order))
	// 	{
	// 		$order = $this->order;
	// 		$this->db->order_by(key($order), $order[key($order)]);
	// 	}
	// }
	
	
	// function get_datatables( $jns,  $bulan, $tahun ){
		
	// 	$this->_get_datatables_query($jns, $bulan, $tahun );
	// 	if($_POST['length'] != -1)
	// 	$this->db->limit($_POST['length'], $_POST['start']);	
	// 	$query = $this->db->get();
	// 	return $query->result();
	// }
	
	// function count_filtered( $jns, $bulan, $tahun ){
	// 	$this->_get_datatables_query( $jns, $bulan, $tahun );
	// 	$query = $this->db->get();
	// 	return $query->num_rows();
	// }
	
	// public function count_all( $jns, $bulan, $tahun ){
	// 	if($jns==1){
	// 		$this->db->select('tbl_apohmohon.*')->from('tbl_apohmohon');
	// 		$this->db->where(array('year(tglmohon)' => $tahun,'month(tglmohon)' => $bulan));
	// 	} else {
	// 	    $this->db->select('tbl_apohmohon.*')->from('tbl_apohmohon');
	// 		$this->db->where(array('tglmohon >=' => $bulan,'tglmohon<= ' => $tahun));
			
	// 	}
		
	// 	return $this->db->count_all_results();
	// }
	
	
	// public function get_by_id($id){
	// 	$this->db->from($this->table);
	// 	$this->db->where('id',$id);
	// 	$query = $this->db->get();

	// 	return $query->row();
	// }

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
	
	// public function _datapo($nopo){
	// 	$q1=$this->db->query("select * from tbl_barangdpo where po_no = '$nopo'")->result();
	// 	if($q1){
	// 	$rowcount =1;
	// 	foreach ($q1 as $res1) {			
	// 		$info['item_kode']   = $res1->kodebarang;
	// 		$info['item_qty']    = $res1->qty_po;
	// 		$info['item_harga']    = $res1->price_po;
	// 		$info['item_satuan']    = $res1->satuan;
	// 		$info['item_diskon']    = $res1->discount;
	// 		$info['item_tax']    = $res1->vat;
	// 		$info['item_total']    = $res1->total;
	// 		$result = $this->return_row_with_data($rowcount++,$info);
	// 	}
	// 	return $result;
	// 	} else  return "";
	// }
	
	// public function return_row_with_data($rowcount,$info){
	// 	extract($info);		
		
	//
	


}
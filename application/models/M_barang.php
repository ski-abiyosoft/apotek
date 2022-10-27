<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_barang extends CI_Model {

	var $table = 'tbl_barang';
	var $column_order = array('id','kodebarang','namabarang','hargajual','satuan1',null); 
	var $column_search = array('id','kodebarang', 'namabarang','hargajual','satuan1');
	var $order = array('id' => 'asc'); 

	public function __construct(){
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query(){
		$this->db->from($this->table);
		
		$i = 0;
		
		foreach ($this->column_search as $item) 
		{
			if($_POST['search']['value']) 
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

	function get_datatables(){
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered(){
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all(){
		$this->db->from($this->table);
		return $this->db->count_all_results();
	}

	public function get_by_id($id){
		$this->db->from($this->table);
		$this->db->where('id',$id);
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
		$get_data_barang = $this->db->query("SELECT * FROM tbl_barang WHERE id = $id")->row();

		$this->db->query("DELETE FROM tbl_barangcabang WHERE kodebarang = '$get_data_barang->kodebarang'");

		$this->db->where('id', $id);
		$this->db->delete($this->table);
	}
	
	public function _datamargin($barang){
		$q1=$this->db->query("SELECT a.koders, b.margin, b.hargajual from tbl_namers AS a left outer join
        tbl_barangcabang AS b on a.koders=b.koders
		and b.kodebarang='$barang'")->result();
		if($q1){
		$rowcount =1;
		foreach ($q1 as $res1) {			
			$info['item_kode']  = $res1->koders;
			$info['item_margin']  = $res1->margin;
			$info['item_harga']  = $res1->hargajual;
			$result = $this->return_row_with_data($rowcount++,$info);
		}
		return $result;
		} else  return "";
	}
	
	public function return_row_with_data($rowcount,$info){
		extract($info);		
		
		?>
            <tr id="row_<?=$rowcount;?>" data-row='<?=$rowcount;?>'>
               <td id="td_<?=$rowcount;?>_1">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;" name="td_data_1[]" id="td_data_<?=$rowcount;?>_1" class="form-control no-padding" value='<?=$item_kode;?>' readonly >
               </td>
			   <td id="td_<?=$rowcount;?>_2">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_2[]" id="td_data_<?=$rowcount;?>_2" class="form-control no-padding" onchange="calculate(<?=$rowcount;?>, this.value)" value='<?=number_format($item_margin,0,'.',',');?>'  >
               </td>			   
			   <td id="td_<?=$rowcount;?>_3">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_3[]" id="td_data_<?=$rowcount;?>_3" class="form-control no-padding" value='<?=number_format($item_harga,0,'.',',');?>'  >
               </td>			   
               
            </tr>
		<?php

	}
	


}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_farmasi_bpp extends CI_Model {

   
	var $table = 'tbl_baranghpo';
	var $column_order = array('id', 'koders','po_no','tglpo','vendor_id','tglkirim','username','dibuatoleh','proses',null); 
	var $column_search = array('id', 'koders','po_no','tglpo','vendor_id','tglkirim','username','dibuatoleh','proses'); 
	var $order = array('id' => 'asc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		
	}

	private function _get_datatables_query($jns, $bulan, $tahun )
	{   
	    $unit=$this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$this->db->select('tbl_baranghpo.*')->from('tbl_baranghpo');
			$this->db->where(array('tglpo' => $tanggal));
			$this->db->where(array('koders' => $unit));
			$this->db->order_by('tglpo, po_no');
		} else {
		    $this->db->select('tbl_baranghpo.*')->from('tbl_baranghpo');
			$this->db->where(array('tglpo >=' => $bulan,'tglpo<= ' => $tahun));
			$this->db->where(array('koders' => $unit));
			$this->db->order_by('tglpo, po_no');
			
			
		}
		
		
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
	
	
	function get_datatables( $jns,  $bulan, $tahun )
	{
		
		$this->_get_datatables_query($jns, $bulan, $tahun );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);	
		$query = $this->db->get();
		return $query->result();
	}
	
	function count_filtered( $jns, $bulan, $tahun )
	{
		$this->_get_datatables_query( $jns, $bulan, $tahun );
		$query = $this->db->get();
		return $query->num_rows();
	}
	

	
	public function count_all( $jns, $bulan, $tahun )
	{
		if($jns==1){
			$this->db->select('tbl_baranghpo.*')->from('tbl_baranghpo');
			$this->db->where(array('year(tglpo)' => $tahun,'month(tglpo)' => $bulan));
		} else {
		    $this->db->select('tbl_baranghpo.*')->from('tbl_baranghpo');
			$this->db->where(array('tglpo >=' => $bulan,'tlpo<= ' => $tahun));
			
		}
		
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
	
	public function _datapo($nopo){
		$q1=$this->db->query("select * from tbl_barangdpo where po_no = '$nopo'")->result();
		if($q1){
		$rowcount =1;
		foreach ($q1 as $res1) {			
			$info['item_kode']   = $res1->kodebarang;
			$info['item_qty']    = $res1->qty_po;
			$info['item_harga']    = $res1->price_po;
			$info['item_satuan']    = $res1->satuan;
			$info['item_diskon']    = $res1->discount;
			$info['item_tax']    = $res1->vat;
			$info['item_total']    = $res1->total;
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
                  <select name="td_data_<?=$rowcount;?>_1" id="td_data_<?=$rowcount;?>_1" class="form-control select2_el_farmasi_barang">
				    <option value="<?= $item_kode;?>"><?= $item_kode;?></option>
				  </select>
			   </td>
			   <td id="td_<?=$rowcount;?>_2">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_2" id="td_data_<?=$rowcount;?>_2" class="form-control no-padding" value='<?=number_format($item_qty,0,'.',',');?>' readonly >
               </td>	
			   <td id="td_<?=$rowcount;?>_3">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_3" id="td_data_<?=$rowcount;?>_3" class="form-control no-padding" value='<?= $item_satuan;?>' readonly >
               </td>
               <td id="td_<?=$rowcount;?>_4">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_4" id="td_data_<?=$rowcount;?>_4" class="form-control no-padding" value='<?=number_format($item_harga,0,'.',',');?>' readonly >
               </td>	

               <td id="td_<?=$rowcount;?>_5">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_4" id="td_data_<?=$rowcount;?>_5" class="form-control no-padding" value='<?=$item_diskon;?>' readonly >
               </td>
               <td id="td_<?=$rowcount;?>_6">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_4" id="td_data_<?=$rowcount;?>_6" class="form-control no-padding" value='<?=number_format($item_tax,0,'.',',');?>' readonly >
               </td>
			   <td id="td_<?=$rowcount;?>_7">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:right" name="td_data_<?=$rowcount;?>_4" id="td_data_<?=$rowcount;?>_7" class="form-control no-padding" value='<?=number_format($item_total,0,'.',',');?>' readonly >
               </td>
			   
               
            </tr>
		<?php

	}
	


}


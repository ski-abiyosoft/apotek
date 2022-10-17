<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_emr extends CI_Model {

	var $table = 'tbl_rekammedisrs';
	var $column_order = array('id','nokwitansi','koders','noreg','rekmed','tglbayar','jambayar','jenisbayar',
	'uangmuka','dibayaroleh','namapasien','username','totalsemua','totalbayar',null); 
	var $column_search = array('id','nokwitansi','koders','noreg','rekmed','tglbayar','jambayar','jenisbayar',
	'uangmuka','dibayaroleh','namapasien','username','totalsemua','totalbayar');
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();				
	}

	private function _get_datatables_query( $filter ="")
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('posbayar', 'RAWAT_JALAN');
				
		/*		
		if($filter !=""){
		   $param = explode('~',$filter);
		   $nama  = $param[0];
		   $alamat= $param[1];
		   $noid  = $param[2];
		
			 
		   if($nama!="" || $alamat!="" || $noid!=""){	 
		   if($nama !=""){	
		     $this->db->like('namapas', $nama);			
		   }

           if($alamat !=""){	
		     $this->db->like('alamat', $alamat);			
		   }		   
		   
		   if($noid !=""){	
		     $this->db->like('noidentitas', $noid);			
		   }		   
		   } else {
			 $this->db->where('idtr=-1');	  
		   }
		   
		   
		} 
        */
		
		
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

	function get_datatables( $filter )
	{
		$this->_get_datatables_query( $filter );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered( $filter )
	{
		$this->_get_datatables_query( $filter );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all( $filter )
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('posbayar', 'RAWAT_JALAN');
		
		/*
		if($filter !=""){
		   $param = explode('~',$filter);
		   $nama  = $param[0];
		   $alamat= $param[1];
		   $noid  = $param[2];
		
			 
		   if($nama!="" || $alamat!="" || $noid!=""){	 
		   if($nama !=""){	
		     $this->db->like('namapas', $nama);			
		   }

           if($alamat !=""){	
		     $this->db->like('alamat', $alamat);			
		   }		   
		   
		   if($noid !=""){	
		     $this->db->like('noidentitas', $noid);			
		   }		   
		   } else {
			 $this->db->where('idtr=-1');	  
		   }
		   
		   
		} 
		*/
		
		
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
	
	public function _datakonsul($rekmed){
		$q1=$this->db->query("select * from tbl_rekammedisrs where rekmed = '$rekmed'")->result();
		if($q1){
		$rowcount =1;
		foreach ($q1 as $res1) {			
			$info['item_tanggal']   = date('d-m-Y',strtotime($res1->tglperiksa));
			$info['item_tanggal1']  = date('Y-m-d',strtotime($res1->tglperiksa));
			$info['item_cabang']    = $res1->koders;
			$info['item_dokter']    = $res1->kodokter;
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
                  <input type="text" style="font-weight: bold-;" name="td_data_<?=$rowcount;?>_1" id="td_data_<?=$rowcount;?>_1" class="form-control no-padding" value='<?=$item_tanggal;?>' readonly >
               </td>
			   <td id="td_<?=$rowcount;?>_2">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:left" name="td_data_<?=$rowcount;?>_2" id="td_data_<?=$rowcount;?>_2" class="form-control no-padding" value='<?=$item_cabang;?>' readonly >
               </td>	
               <td id="td_<?=$rowcount;?>_3">
                  <!-- item name  -->
                  <input type="text" style="font-weight: bold-;text-align:left" name="td_data_<?=$rowcount;?>_3" id="td_data_<?=$rowcount;?>_3" class="form-control no-padding" value='<?=$item_dokter;?>' readonly >
               </td> 
			   <td id="td_<?=$rowcount;?>_4">
                  <!-- item name  -->
                  <input type="button" style="font-weight: bold-;text-align:left" name="td_data_<?=$rowcount;?>_3" id="td_data_<?=$rowcount;?>_4" class="btn btn-info" value='Info' onclick="getdetil('<?= $item_tanggal1;?>')">
               </td> 
               
            </tr>
		<?php

	}
	
	
	public function _dataemr($rekmed)
	{		
		$query = $this->db->query( "select tbl_rekammedisrs.*, tbl_pasien.namapas, tbl_pasien.rekmed as rekmedpas from tbl_rekammedisrs
		right outer join tbl_pasien on tbl_rekammedisrs.rekmed=tbl_pasien.rekmed 
		where tbl_pasien.rekmed = '$rekmed'");
		
		$row = $query->row();
		if($row){
          return $row;
		} else {
		  return '';	
		}
	}
	
	public function _dataemr_detil($rekmed, $tanggal)
	{		
	   $query = $this->db->query( "select tbl_rekammedisrs.*, tbl_pasien.namapas, tbl_pasien.rekmed as rekmedpas from tbl_rekammedisrs
		right outer join tbl_pasien on tbl_rekammedisrs.rekmed=tbl_pasien.rekmed 
		where tbl_pasien.rekmed = '$rekmed' and tbl_rekammedisrs.tglperiksa = '$tanggal'");
		
		$row = $query->row();
		if($row){
          return $row;
		} else {
		  return '';	
		}
	}
	
	
	


}

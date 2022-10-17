<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_pendaftaran extends CI_Model {

	var $table = 'tbl_regist';
	var $column_order = array('id','koders','noreg','rekmed','keluar','batal',
	'tglmasuk','jam','tujuan','kodokter','antrino','namapas','nadokter',null); 
	var $column_search = array('tbl_regist.id','tbl_regist.koders','tbl_regist.noreg','tbl_regist.rekmed','tbl_regist.keluar','batal',
	'tbl_regist.tglmasuk','tbl_regist.jam','tbl_regist.tujuan','tbl_regist.kodokter','tbl_regist.antrino','tbl_pasien.namapas',
	'tbl_dokter.nadokter');
	var $order = array('tbl_regist.id' => 'desc'); 
	var $column_search_histori = array(
		'tbl_regist.id',
		'tbl_regist.koders',
		'tbl_regist.noreg',
		'tbl_regist.rekmed',
		'tbl_regist.tglmasuk',
		'tbl_regist.jam',
		'tbl_regist.jenispas',
		'tbl_regist.tujuan',
		'tbl_regist.kodepos',
		'tbl_regist.kelas',
		'tbl_regist.cust_id',
		'tbl_regist.kodokter',
		'tbl_regist.antrino',
		'tbl_regist.baru',
		'tbl_regist.keluar',
		'tbl_regist.tglkeluar',
		'tbl_regist.jamkeluar',
		'tbl_regist.drpengirim',
		'tbl_regist.username',
		'tbl_regist.shipt',
		'tbl_regist.batal',
		'tbl_regist.batalkarena',
		'tbl_regist.diperiksa_perawat',
		'tbl_regist.diperiksa_dokter',
		'tbl_regist.koderuang',
		'tbl_regist.kodekamar',
		'tbl_regist.mjkn_token',
		'tbl_regist.nobpjs',
		'tbl_regist.norujukan',
		'tbl_regist.nosep',
		'tbl_dokter.nadokter', 
		'tbl_namapos.namapost');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();				
	}

	private function _get_datatables_query( $jns, $bulan, $tahun)
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->select('tbl_regist.*, tbl_pasien.namapas, tbl_dokter.nadokter');
		$this->db->from($this->table);
		$this->db->join('tbl_pasien','tbl_pasien.rekmed=tbl_regist.rekmed','left');
		$this->db->join('tbl_dokter','tbl_dokter.kodokter=tbl_regist.kodokter','left');
		$this->db->where('tbl_regist.koders', $cabang);
		// $this->db->where('tbl_dokter.koders', $cabang);
		// $this->db->where('tbl_regist.keluar', 0);
				
		if($jns==1){
			$tanggal = date('Y-m-d');
			$this->db->where(array('tbl_regist.tglmasuk' => $tanggal));
			
		} else {
		    $this->db->where(array('tbl_regist.tglmasuk >=' => $bulan,'tbl_regist.tglmasuk<= ' => $tahun));
			
		}
        
		
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

	function get_datatables( $jns,  $bulan, $tahun )
	{
		$this->_get_datatables_query( $jns,  $bulan, $tahun );
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered( $jns,  $bulan, $tahun )
	{
		$this->_get_datatables_query( $jns,  $bulan, $tahun );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('keluar', 0);
		
		if($jns==1){
			$this->db->where(array('year(tglmasuk)' => $tahun,'month(tglmasuk)' => $bulan));
		} else {
			$this->db->where(array('tglmasuk >=' => $bulan,'tglmasuk<= ' => $tahun));
			
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
	
	private function _get_histori_datatables_query($rekmed)
	{
		$cabang = $this->session->userdata('unit');	
		$this->db->select('tbl_regist.*, tbl_dokter.nadokter, tbl_namapos.namapost');
		$this->db->from('tbl_regist');
		$this->db->join('tbl_dokter','tbl_regist.kodokter=tbl_dokter.kodokter');
		$this->db->join('tbl_namapos','tbl_regist.kodepos=tbl_namapos.kodepos');
		// $this->db->where('tbl_regist.koders', $cabang);
		$this->db->where('tbl_regist.rekmed', $rekmed);
		//$this->db->where('tbl_regist.keluar', 0);
		
		$i = 0;
		
		foreach ($this->column_search_histori as $item) 
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

				if(count($this->column_search_histori) - 1 == $i) //last loop
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

	public function _datahistori_datatable($rekmed){
		// $q1=$this->db->query("select tbl_regist.*,
        // tbl_dokter.nadokter, tbl_namapos.namapost 
		// from tbl_regist inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter
		// inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos
		// where rekmed = '$rekmed' order by tbl_regist.tglmasuk desc limit 5");
		
		$q1 = $this->_get_histori_datatables_query($rekmed);
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();

		$this->db->last_query();
		return $query->result();

		// if($q1->num_rows() > 0){
		// 	return $q1->result();
		// } else {
		// 	return false;
		// }
	}

	
	public function _datahistori_datatable_count_all($rekmed){
		// $q1=$this->db->query("select tbl_regist.*,
        // tbl_dokter.nadokter, tbl_namapos.namapost 
		// from tbl_regist inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter
		// inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos
		// where rekmed = '$rekmed' order by tbl_regist.tglmasuk desc limit 5");
		
		$q1 = $this->_get_histori_datatables_query($rekmed);
		
		return $this->db->count_all_results();
		// return $q1->num_rows();
	}

	
	function _datahistori_datatable_count_filtered($rekmed){
		// $this->_get_datatables_query( $jns,  $bulan, $tahun );
		$this->_get_histori_datatables_query($rekmed);
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function _datahistori($rekmed){
		$q1=$this->db->query("select tbl_regist.*,
        tbl_dokter.nadokter, tbl_namapos.namapost 
		from tbl_regist inner join tbl_dokter on tbl_regist.kodokter=tbl_dokter.kodokter
		inner join tbl_namapos on tbl_regist.kodepos=tbl_namapos.kodepos
		where rekmed = '$rekmed' order by tbl_regist.tglmasuk desc limit 5")->result();
		if($q1){
		$rowcount =1;
		foreach ($q1 as $res1) {			
		    $info['item_cabang'] = $res1->koders;
			$info['item_tanggal'] = $res1->tglmasuk;
			$info['item_jam'] = $res1->jam;
			$info['item_noreg']  = $res1->noreg;
			$info['item_poli']  = $res1->namapost;
			$info['item_dokter']  = $res1->nadokter;
			$result = $this->return_row_with_data($rowcount++,$info);
		}
		return $result;
		} else  return "";
	}
	
	public function return_row_with_data($rowcount,$info){
		extract($info);		
		
		?>
            <tr id="row_<?=$rowcount;?>" data-row='<?=$rowcount;?>'>
              <td style="text-align:center" id="td_<?=$rowcount;?>_1">
                  <?= $item_cabang;?>
                  
               </td>
			   <td style="text-align:center" id="td_<?=$rowcount;?>_2">
                  <?= tanggal($item_tanggal);?>
               </td>			   
			   <td style="text-align:center" id="td_<?=$rowcount;?>_2">
                  <?= ($item_jam);?>
               </td>			   
               <td style="text-align:center" id="td_<?=$rowcount;?>_3">
                  <a href="#" onclick="getHistory('<?= $item_noreg;?>')"> <?= $item_noreg;?><a/>
               </td>			 
               <td id="td_<?=$rowcount;?>_4">
                  <?= $item_poli;?>
               </td>			
               <td id="td_<?=$rowcount;?>_5">
                  <?= $item_dokter;?>
               </td>			      			   
			   
			   
            </tr>
		<?php

	}


}

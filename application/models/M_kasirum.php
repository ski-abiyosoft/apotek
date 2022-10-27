<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_kasirum extends CI_Model {

	var $table = 'tbl_kasir';
	var $column_order = array('id','nokwitansi','koders','noreg','rekmed','tglbayar','jambayar',
	'uangmuka','dibayaroleh','namapasien','username','totalsemua','totalbayar',null); 
	var $column_search = array('id','nokwitansi','koders','noreg','rekmed','tglbayar','jambayar',
	'uangmuka','dibayaroleh','namapasien','username','totalsemua','totalbayar');
	var $order = array('id' => 'desc'); 

	public function __construct()
	{
		parent::__construct();
		$this->load->database();				
	}

	function _get_datatables_query2( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglbayar = '$tanggal'";			
		} else {
		    $tgll= "and tglbayar >= '$bulan' AND tglbayar<= '$tahun'";
			
		}

		if($_POST['search']['value']) 
		{
				$search = $_POST['search']['value'];
				$search2= 
				"AND ( id LIKE '%$search%' OR
				 nokwitansi LIKE '%$search%' OR
				 koders LIKE '%$search%' OR
				 noreg LIKE '%$search%' OR
				 rekmed LIKE '%$search%' OR
				 tglbayar LIKE '%$search%' OR
				 jambayar LIKE '%$search%' OR
				 uangmuka LIKE '%$search%' OR
				 dibayaroleh LIKE '%$search%' OR
				 namapasien LIKE '%$search%' OR
				 username LIKE '%$search%' OR
				 totalsemua LIKE '%$search%' OR
				 totalbayar LIKE '%$search%' )";				
		}else{
			$search2='';
		}

		if($_POST['length'] != -1)
		{

			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}
		
		$sql = "SELECT id,koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar,
		jenisbayar,SUM(totalbayar)totalbayar,SUM(totalsemua)totalsemua 
		FROM $this->table 
		WHERE koders = '$cabang' AND posbayar = 'UANG_MUKA' $tgll $search2
		GROUP BY koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar
		ORDER BY id,nokwitansi DESC $limm
		";
		// $query = $this->db->query($sql);
		// return $query->result();
		return $sql;
	}

	private function _get_datatables_query( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		// $this->db->select('SUM(totalsemua) AS semua, umuka, id, koders, nokwitansi, rekmed, noreg, tglbayar, jenisbayar, totalsemua, username, totalbayar'); //saya tambah 1 baris ini karena saya menambah sum total semua dan umuka
		$this->db->from($this->table);
		$this->db->where('koders', $cabang);
		$this->db->where('posbayar', 'UANG_MUKA');
		// $this->db->group_by("nokwitansi"); //saya tambahkan group by
				
		if($jns==1){
			$tanggal = date('Y-m-d');
			$this->db->where(array('tglbayar' => $tanggal));			
		} else {
		    $this->db->where(array('tglbayar >=' => $bulan,'tglbayar<= ' => $tahun));
			
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

	function get_datatables( $jns, $bulan, $tahun )
	{
		
		// if($_POST['length'] != -1)
		// $this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->query($this->_get_datatables_query2( $jns, $bulan, $tahun ));
		return $query->result();
	}

	function count_filtered( $jns, $bulan, $tahun )
	{
		
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglbayar = '$tanggal'";			
		} else {
		    $tgll= "and tglbayar >= '$bulan' AND tglbayar<= '$tahun'";
			
		}

		if($_POST['search']['value']) 
		{
				$search = $_POST['search']['value'];
				$search2= 
				"AND ( id LIKE '%$search%' OR
				 nokwitansi LIKE '%$search%' OR
				 koders LIKE '%$search%' OR
				 noreg LIKE '%$search%' OR
				 rekmed LIKE '%$search%' OR
				 tglbayar LIKE '%$search%' OR
				 jambayar LIKE '%$search%' OR
				 uangmuka LIKE '%$search%' OR
				 dibayaroleh LIKE '%$search%' OR
				 namapasien LIKE '%$search%' OR
				 username LIKE '%$search%' OR
				 totalsemua LIKE '%$search%' OR
				 totalbayar LIKE '%$search%' )";				
		}else{
			$search2='';
		}

		if($_POST['length'] != -1)
		{
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}
		
		$sql = "SELECT id,koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar,
		jenisbayar,SUM(totalbayar)totalbayar,SUM(totalsemua)totalsemua 
		FROM $this->table 
		WHERE koders = '$cabang' AND posbayar = 'UANG_MUKA' $tgll $search2
		GROUP BY koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar
		ORDER BY id,nokwitansi
		";
		$query = $this->db->query($sql);
		// return $query->result();
		// return $sql;
		// $query = $this->db->query($this->_get_datatables_query2( $jns, $bulan, $tahun ));
		// $this->_get_datatables_query( $jns, $bulan, $tahun );
		// $query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all( $jns, $bulan, $tahun )
	{
		$cabang = $this->session->userdata('unit');	
		// $this->db->from($this->table);
		// $this->db->where('koders', $cabang);
		// $this->db->where('posbayar', 'UANG_MUKA');
		// if($jns==1){
		// 	$this->db->where(array('year(tglbayar)' => $tahun,'month(tglbayar)' => $bulan));
		// } else {
		// 	$this->db->where(array('tglbayar >=' => $bulan,'tglbayar<= ' => $tahun));
			
		// }
		$cabang = $this->session->userdata('unit');	
		if($jns==1){
			$tanggal = date('Y-m-d');
			$tgll= "and tglbayar = '$tanggal'";			
		} else {
		    $tgll= "and tglbayar >= '$bulan' AND tglbayar<= '$tahun'";
			
		}

		if($_POST['search']['value']) 
		{
				$search = $_POST['search']['value'];
				$search2= 
				"AND ( id LIKE '%$search%' OR
				 nokwitansi LIKE '%$search%' OR
				 koders LIKE '%$search%' OR
				 noreg LIKE '%$search%' OR
				 rekmed LIKE '%$search%' OR
				 tglbayar LIKE '%$search%' OR
				 jambayar LIKE '%$search%' OR
				 uangmuka LIKE '%$search%' OR
				 dibayaroleh LIKE '%$search%' OR
				 namapasien LIKE '%$search%' OR
				 username LIKE '%$search%' OR
				 totalsemua LIKE '%$search%' OR
				 totalbayar LIKE '%$search%' )";				
		}else{
			$search2='';
		}

		if($_POST['start'] != -1)
		{
			$lim1 = $_POST['start'];
			$lim2 = ','.$_POST['length'];
			$limm = "LIMIT $lim1 $lim2";
		}else{
			$limm = "";
		}
		
		$sql = "SELECT COUNT(*) as num_rows FROM (SELECT id,koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar,
		jenisbayar,SUM(totalbayar)totalbayar,SUM(totalsemua)totalsemua 
		FROM $this->table 
		WHERE koders = '$cabang' AND posbayar = 'UANG_MUKA' $tgll $search2
		GROUP BY koders,nokwitansi,noreg,rekmed,username,tglbayar,jambayar
		ORDER BY id,nokwitansi)p
		";
		$query = $this->db->query($sql);
		// return $this->db->count_all_results();
		return $query->num_rows();
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


}

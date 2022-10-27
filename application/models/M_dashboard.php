<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class M_dashboard extends CI_Model {

	
	function months() {
	return array('Jan','Peb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nop','Des');
			
    }

	
	function jcustomer()
	{
		$unit = $this->session->userdata('unit');				 
		$this->db->select('COALESCE(COUNT(idtr),0) as jml');
		$this->db->from( 'tbl_pasien' );		
		$this->db->where('koders',$unit);
		$query = $this->db->get();
		// return $query->num_rows();
		return $query->result();		
	}
	
	function dsaset()
	{
	   //return $this->db->query('select sum(hargabeli*stok) as total from inv_barang')->row()->total;
	   return 0;
	}
	
	function dshutang()
	{
	   $unit = $this->session->userdata('unit');				 	 
	   return $this->db->query("select sum(totaltagihan-totalbayar) as total from tbl_apoap where koders = '$unit'")->row()->total;
	}
	
	function dspiutang()
	{
	   return 0;
	   
	}
	
	function report(){
		$tahun = date('Y');
		$cabang = $this->session->userdata('unit');
		$q ="
		    select 'Jan' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=1 and koders = '$cabang'
			union
			select 'Peb' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=2 and koders = '$cabang'
			union
			select 'Mar' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=3 and koders = '$cabang'
			union
			select 'Apr' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=4 and koders = '$cabang'
			union
			select 'Mei' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=5 and koders = '$cabang'
			union
			select 'Jun' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=6 and koders = '$cabang'
			union
			select 'Jul' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=7 and koders = '$cabang'
			union
			select 'Agu' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=8 and koders = '$cabang'
			union
			select 'Sep' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=9 and koders = '$cabang'
			union
			select 'Okt' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=10 and koders = '$cabang'
			union
			select 'Nop' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=11 and koders = '$cabang'
			union
			select 'Des' as bulan, koders, count(*) as jumlah from tbl_regist where year(tglmasuk) = '$tahun' and month(tglmasuk)=12 and koders = '$cabang'";

			
			  
			// $cabang1 = " and koders = '$cabang'";
        $query = $this->db->query($q);
         
        if($query->num_rows() > 0){
            foreach($query->result() as $data){
                $hasil[] = $data;
            }
            return $hasil;
        }
		
		
    }

    

}
?>
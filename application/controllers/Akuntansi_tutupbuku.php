<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_tutupbuku extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '207');
		//$this->load->model('akuntansi/M_akuntansi_saldo','M_akuntansi_saldo');
	}

	public function index()
	{
	   $cek = $this->session->userdata('level');		
	   if(!empty($cek))
	   {	
		$unit = $this->session->userdata('unit');	
		
		$bulan = $this->M_global->_periodebulan();
		$tahun = $this->M_global->_periodetahun();
		
		if ($bulan==12)
		  {
			 $bulan_buka = 1;
			 $tahun_buka = $tahun+1;
		  } else
		  {
			 $bulan_buka = $bulan+1;
			 $tahun_buka = $tahun;
		  }
  
		$d['bulan']=$bulan;
		$d['tahun']=$tahun;
		$d['bulan_buka']=$bulan_buka;
		$d['tahun_buka']=$tahun_buka;
		$this->load->view('akuntansi/v_akuntansi_tutupbuku',$d);
	   } else
	   {
		   header('location:'.base_url());
	   }		   
	}
		
		
	public function tutupbuku()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
             $thntutup       = trim($this->input->post('thntutup'));
			 $thnbuka        = trim($this->input->post('thnbuka'));
			 $blntutup       = trim($this->input->post('blntutup'));
			 $blnbuka        = trim($this->input->post('blnbuka'));
			   
			 $userid         = $this->session->userdata('userid');		
			 $tgl_proses     = date("Y-m-d");

           
			 $query = "select count(*) as jumdata from ms_akunsaldo where tahun = '$thnbuka' and bulan= '$blnbuka'";			 
			 $data  = $this->db->query($query)->row();

			 if ($data->jumdata>0)
			 {
				echo "not ok";
			 } else
			 {
			 
			    $bulan_awal = $blntutup;
			    $tahun_awal = $thntutup;
				$query = "select kodeakun, jenis from ms_akun where kodeakun < '4'";											
				$hasil = $this->db->query($query)->result();
				foreach ($hasil as $rowD)
				{
				   $jenisakun = $rowD->jenis;
				   $akun      = $rowD->kodeakun;

				   $query = "select debet,kredit from ms_akunsaldo where kodeakun = '$akun' and tahun = '$tahun_awal' and bulan = '$bulan_awal'";
				   $data  = $this->db->query($query)->row();				   
				   if ($jenisakun=='D')
				   {
					 $saldo_awal = $row->debet-$row->kredit;
				   } else
				   {
					 $saldo_awal = $row->kredit-$row->debet;
				   }

				   $query = "select sum(debet) as debet, sum(kredit) as kredit from tr_jurnal where year(tanggal)='$thntutup' and month(tanggal)='$blntutup' and kodeakun = '$akun'";
				   $row   = $this->db->query($query)->row();				   				   
				   $debet = $row->debet;
				   $kredit= $row->kredit;

				   if ($jenisakun=='D')
				   {
					 $saldo_akhir = ($saldo_awal + $debet - $kredit);
				   } else
				   {
					 $saldo_akhir = $saldo_awal - $debet + $kredit;
				   }

				   if ($jenisakun=='D')
				   {
					   $saldo_debet  = $saldo_akhir;
					   $saldo_kredit =0;

				   } else
				   {
					   $saldo_debet   = 0;
					   $saldo_kredit  = $saldo_akhir;
					   
					   if(substr($akun,0,1)=='1')
					   {
						 $saldo_debet  = $saldo_akhir * -1;
						 $saldo_kredit =0;
					   }

				   }

				   $saldo=$saldo_debet+$saldo_kredit;
				   if ($saldo!=0)
				   {
					$qtutup = "insert into ms_akunsaldo(kodeakun, tahun, bulan, debet, kredit, userid, tanggal)
					values('$akun','$thnbuka','$blnbuka','$saldo_debet','$saldo_kredit','$userid','$tgl_proses')";

					$this->db->query($qtutup);
				   }
				 }
				  //update lr berjalan
				  //update status jurnal
				  
				  $query = "update tr_jurnal set posted='Y' where posted<>'Y' and year(tanggal)='$thntutup' and month(tanggal)='$blntutup'";
				  $this->db->query($query);
				  				  
				  $query = "select akunlrlalu from ms_identity";				  
				  $row   = $this->db->query($query)->row();
				  $akunlrlalu= $row->akunlrlalu;

				  $query = "select akunlrberjalan from ms_identity";
				  $row   = $this->db->query($query)->row();
				  $akunlr= $row->akunlrberjalan;

				  $query = "select kredit from ms_akunsaldo where kodeakun = '$akunlr' and tahun = '$thntutup' and bulan = '$blntutup'";
				  $row   = $this->db->query($query)->row();
				  $saldoberjalanlalu = $row->kredit;
				  
				  $query = "select kredit from ms_akunsaldo where kodeakun = '$akunlrlalu' and tahun = '$thntutup' and bulan = '$blntutup'";
				  $row   = $this->db->query($query)->row();
				  $saldolalu = $row->kredit;
				  
				  $query = "select sum(debet) as debet, sum(kredit) as kredit from tr_jurnal where year(tanggal)='$thntutup' and month(tanggal)='$blntutup' and kodeakun = '$akunlrlalu'";
				  $row   = $this->db->query($query)->row();
				  $debet = $row->debet;
				  $kredit= $row->kredit;
				  
				  $saldolalu = $saldolalu + $kredit - $debet;
				   

				  if($blntutup==12)
				  {
					$saldo = $saldoberjalanlalu+$saldolalu;
				  } else
				  {
					$saldo = $saldoberjalanlalu;
				  }


				  $query = "delete from ms_akunsaldo where tahun = '$thnbuka' and bulan= '$blnbuka' and kodeakun='$akunlrlalu'";
				  $this->db->query($query);
				  
				  $query = "delete from ms_akunsaldo where tahun = '$thnbuka' and bulan= '$blnbuka' and kodeakun='$akunlr'";
				  $this->db->query($query);
		 
				  //
					$query = "select sum(kredit-debet) as jumlah from tr_jurnal a,ms_akun b where a.kodeakun=b.kodeakun and year(a.tanggal)='$thntutup' and  month(a.tanggal)='$blntutup' and a.kodeakun like '4%'";
					$row   = $this->db->query($query)->row();
					$pendapatan = $row->jumlah;

					$query = "select sum(debet-kredit) as jumlah from tr_jurnal a,ms_akun b where a.kodeakun=b.kodeakun and year(a.tanggal)='$thntutup' and  month(a.tanggal)='$blntutup' and a.kodeakun like '5%'";
					$row   = $this->db->query($query)->row();
					$biaya = $row->jumlah;

					$query = "select sum(kredit-debet) as jumlah from tr_jurnal a,ms_akun b where a.kodeakun=b.kodeakun and year(a.tanggal)='$thntutup' and  month(a.tanggal)='$blntutup' and a.kodeakun like '61%'";
					$row   = $this->db->query($query)->row();
					$pendapatanL = $row->jumlah;

					$query = "select sum(debet-kredit) as jumlah from tr_jurnal a,ms_akun b where a.kodeakun=b.kodeakun and year(a.tanggal)='$thntutup' and  month(a.tanggal)='$blntutup' and a.kodeakun like '62%'";
					$row   = $this->db->query($query)->row();
					$biayaL = $row->jumlah;

					$query = "select sum(kredit-debet) as jumlah from tr_jurnal a,ms_akun b where a.kodeakun=b.kodeakun and year(a.tanggal)='$thntutup' and  month(a.tanggal)='$blntutup' and a.kodeakun like '9%'";
					$row   = $this->db->query($query)->row();
					$pajak = $row->jumlah;

					$lrj   = $pendapatan-$biaya+$pendapatanL-$biayaL+$pajak;

					$debetlr =0;
					$kreditlr=$lrj+$saldo;
					

					if($blntutup==12)
					{
					 $qlr = "insert into ms_akunsaldo(kodeakun, tahun, bulan, debet, kredit, userid, tanggal)
					 values('$akunlrlalu','$thnbuka','$blnbuka','$debetlr','$kreditlr','$userid','$tgl_proses')";
					} else
					{
					 $qlr = "insert into ms_akunsaldo(kodeakun, tahun, bulan, debet, kredit, userid, tanggal)
					 values('$akunlrlalu','$thnbuka','$blnbuka',0,'$saldolalu','$userid','$tgl_proses')";
					 $this->db->query($qlr);

					 $qlr = "insert into ms_akunsaldo(kodeakun, tahun, bulan, debet, kredit, userid, tanggal)
					 values('$akunlr','$thnbuka','$blnbuka','$debetlr','$kreditlr','$userid','$tgl_proses')";
					}
					
					$this->db->query($qlr);

					echo "ok";

					$qtutup = "update ms_identity set periode_tahun= '$thnbuka', periode_bulan= '$blnbuka'";
					$this->db->query($qtutup);
				  
				  
		   }		  
		  
		}
		else
		{			
			header('location:'.base_url());		
		}
	}	
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Akuntansi_saldo extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk modul saldo awal keuangan
	 **/
	
	
	
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_akuntansi_saldo','M_akuntansi_saldo');
		$this->session->set_userdata('menuapp', '200');
		$this->session->set_userdata('submenuapp', '212');
	}

	public function index()
	{
	   $cek = $this->session->userdata('level');		
	   if(!empty($cek))
	   {	
		$unit = $this->session->userdata('unit');	
		if(!empty($unit))
		{
		  $q1 = 
			
			   "select A.kodeakun, A.namaakun, A.jenis, B.debet, B.kredit from
				ms_akun A
				LEFT JOIN
				(select kodeakun, debet as debet, kredit as kredit from ms_akunsaldo
				 where tahun=(select periode_tahun from ms_identity) and bulan=(select periode_bulan from ms_identity)
				 and cabang = '$unit'
				) B
				USING(kodeakun)
				WHERE A.kodeakun < '4' 
				order by A.kodeakun";
		} else
		{
			$q1 = 
			   "select A.kodeakun, A.namaakun, A. jenis, B.debet, B.kredit from
				ms_akun A
				LEFT JOIN
				(select kodeakun, debet as debet, kredit as kredit from ms_akunsaldo
				 where tahun=(select periode_tahun from ms_identity) and bulan=(select periode_bulan from ms_identity)											 
				) B
				USING(kodeakun)
				WHERE A.kodeakun < '4' 
				order by A.kodeakun";  	
		}
		
		$this->load->helper('url');		
		$d['acc'] = $this->db->query($q1);		
		$this->load->view('akuntansi/v_akuntansi_saldo',$d);
	   } else
	   {
		   header('location:'.base_url());
	   }		   
	}
		
		
	public function cetak()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/bank/v_master_bank_prn',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function export()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{				  
		  $page=$this->uri->segment(3);
		  $limit=$this->config->item('limit_data');	
          $d['master_bank'] = $this->db->get("ms_bank");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('master/bank/v_master_bank_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	public function saldo_save($x)
	{
		
		$cek = $this->session->userdata('level');
		if(!empty($cek))
		{						
	            				
				$data  = explode("~",$x);
				$kode  = $data[0];
				$debet = $data[1];				
				$kredit= $data[2];
				$debet = str_replace(',','',$debet);
				$kredit= str_replace(',','',$kredit);
							
				$bulan = $this->M_global->_periodebulan();
				$tahun = $this->M_global->_periodetahun();
				$this->M_akuntansi_saldo->update_saldo($tahun,$bulan,$kode,$debet,$kredit);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
}

/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */
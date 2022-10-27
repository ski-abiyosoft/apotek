<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Keuangan_saldo extends CI_Controller {

	
	
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('M_keuangan_saldo','M_keuangan_saldo');
		$this->session->set_userdata('menuapp', '900');
		$this->session->set_userdata('submenuapp', '901');
	}

	public function index()
	{
		$unit = $this->session->userdata('unit');	
		if(!empty($unit))
		{
		  $q1 = 
			" select a.bank_kode, a.bank_nama, a.bank_norek, b.saldo_awal from
			ms_bank a 
			LEFT JOIN
			(select bank_kode, saldo_awal from ms_banksaldo
			where tahun=(select periode_tahun from ms_identity) and bulan=(select periode_bulan from ms_identity)) b
			USING(bank_kode)
			where a.bank_pasar = '$unit'
			order by a.bank_kode";	
		} else
		{
			$q1 = 
			" select a.bank_kode, a.bank_nama, a.bank_norek, b.saldo_awal from
			ms_bank a 
			LEFT JOIN
			(select bank_kode, saldo_awal from ms_banksaldo
			where tahun=(select periode_tahun from ms_identity) and bulan=(select periode_bulan from ms_identity)) b
			USING(bank_kode)
			order by a.bank_kode";		
		}
		
		$this->load->helper('url');		
		$d['keu'] = $this->db->query($q1);		
		$this->load->view('keuangan/v_keuangan_saldo',$d);
	}
		
	public function ajax_delete($id)
	{
		$this->M_bank->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
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
				$saldo = $data[1];				
				$saldo = str_replace(',','',$saldo);
							
				$bulan = $this->M_global->_periodebulan();
				$tahun = $this->M_global->_periodetahun();
				$this->M_keuangan_saldo->update_saldo($tahun,$bulan,$kode,$saldo);			
		}
		else
		{
			header('location:'.base_url());
		}
	}
	
	
}

/* End of file keuangan_saldo.php */
/* Location: ./application/controllers/keuangan_saldo.php */
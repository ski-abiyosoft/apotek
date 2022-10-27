<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payroll_pegawai extends CI_Controller {

	/**
	 * @author : Enjang RK
	 * @web : http://e-soft.comli.com
	 * @keterangan : Controller untuk manajemen bank (CRUD master bank)
	 **/
		
	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_payroll_pegawai','M_payroll_pegawai');
		$this->load->helper('simkeu_rpt');
		$this->session->set_userdata('menuapp', '700');
		$this->session->set_userdata('submenuapp', '701');
	}

	public function index()
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{
			$this->load->helper('url');
			$d['pegawai'] = $this->db->get("ms_pegawai")->result();						
			$this->load->view('payroll/v_payroll_pegawai',$d);
		} else
		{
			header('location:'.base_url());
		}			
	}
	
	public function rincian($id)
	{
		$cek = $this->session->userdata('level');		
		if(!empty($cek))
		{			
	        $query = "select * from ms_pegawai where id = $id";			
			$d['pegawai'] = $this->db->query($query)->result();
			$d['unit'] = $this->db->get('ms_pasar')->result();
			$d['ptkp'] = $this->db->get('ms_ptkp')->result();
			
			$this->load->view('payroll/v_payroll_pegawaid',$d);
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
          $d['at'] = $this->db->get("ms_at");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_at_prn',$d);				
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
          $d['at'] = $this->db->get("ms_at");
          $d['nama_usaha']=$this->config->item('nama_perusahaan');
		  $d['alamat']=$this->config->item('alamat_perusahaan');
		  $d['motto']=$this->config->item('motto');
		  
		  $this->load->view('at/at_at_exp',$d);				
		}
		else
		{
			
			header('location:'.base_url());
			
		}
	}
	
	
}

/* End of file master_bank.php */
/* Location: ./application/controllers/master_bank.php */